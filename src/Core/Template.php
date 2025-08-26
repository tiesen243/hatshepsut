<?php

namespace Framework\Core;

class Template
{
  private static $instance = null;

  protected string $extends = '';
  protected array $sections = [];
  protected array $resourceDeps = [];

  public function __construct(
    private string $templateDir,
    private string $cacheDir,
    private string $manifestPath,
    private string $viteUrl,
    private bool $isDev = false,
  ) {
    if (!is_dir($this->cacheDir)) {
      if (!mkdir($this->cacheDir, 0775, true) && !is_dir($this->cacheDir)) {
        throw new \RuntimeException(
          "Cannot create cache directory: {$this->cacheDir}",
        );
      }
    }
  }

  public static function create(
    string $basePath,
    string $viteUrl,
    bool $isDev,
  ): static {
    if (self::$instance === null) {
      self::$instance = new static(
        $basePath . '/resources/views',
        $basePath . '/.cache/views',
        $basePath . '/public/build/.vite/manifest.json',
        $viteUrl,
        $isDev,
      );
    }
    return self::$instance;
  }

  public static function getInstance(): static
  {
    if (self::$instance === null) {
      throw new \RuntimeException('Template instance not created yet.');
    }
    return self::$instance;
  }

  public function render(string $template, array $data = []): string
  {
    $this->extends = '';
    $content = $this->renderPartial($template, $data);
    if (!empty($this->extends)) {
      $content = $this->renderPartial($this->extends, $data);
    }

    return $content;
  }

  public function renderPartial(string $template, array $data = []): string
  {
    extract($data, EXTR_SKIP);

    $cachedFile = $this->compile($template);
    ob_start();
    include $cachedFile;
    return ob_get_clean();
  }

  private function compile(string $template): string
  {
    $templateFile =
      $this->templateDir . '/' . str_replace('.', '/', $template) . '.tpl.php';
    $cachedFile = $this->cacheDir . '/' . md5($templateFile) . '.php';

    if (
      !file_exists($cachedFile) ||
      filemtime($cachedFile) < filemtime($templateFile)
    ) {
      if (file_exists($templateFile)) {
        $content = file_get_contents($templateFile);
        $parsed = $this->parse($content);
        file_put_contents($cachedFile, $parsed);
      } else {
        echo 'could not find template file: ' . $templateFile;
      }
    }

    return $cachedFile;
  }

  private function parse(string $content): string
  {
    /**
     * @extends directive
     *
     * Usage:
     *  - @extends('layout')
     */
    $content = preg_replace(
      "/@extends\([\"'](.+?)[\"']\)/",
      '<?php $this->extends = "$1" ?>',
      $content,
    );

    /**
     * @yield with optional default value
     *
     * Usage:
     *  - @yield('section_name')
     *  - @yield('section_name', 'default value')
     */
    $content = preg_replace_callback(
      '/@yield\(\s*[\"\'](.+?)[\"\']\s*(?:,\s*[\"\'](.*?)[\"\'])?\s*\)/',
      function ($matches) {
        $name = $matches[1];
        $default = isset($matches[2]) ? $matches[2] : '';
        return '<?php echo $this->sections["' .
          $name .
          '"] ?? "' .
          addslashes($default) .
          '"; ?>';
      },
      $content,
    );

    /**
     * @section directive
     *
     * Usage:
     *  - @section('section_name')
     *  - @endsection
     */
    $content = preg_replace(
      "/@section\([\"'](.+?)[\"']\)/",
      '<?php ob_start(); $name = "$1"; ?>',
      $content,
    );
    $content = preg_replace(
      '/@endsection/',
      '<?php $this->sections[$name] = ob_get_clean(); ?>',
      $content,
    );

    /**
     * @include directive
     *
     * Usage:
     *  - @include('partial')
     *  - @include('partial', ['var' => $value])
     */
    $content = preg_replace_callback(
      "/@include\(\s*['\"]([^'\"\)]+)['\"]\s*(?:,\s*(\[.*?\]))?\s*\)/s",
      function ($matches) {
        $view = $matches[1];
        $props = $matches[2] ?? '[]';
        return "<?php echo \$this->renderPartial('{$view}', {$props}); ?>";
      },
      $content,
    );

    /**
     * {{ $variable }} syntax for escaping variables
     */
    $content = preg_replace(
      '/\{\{\s*(.+?)\s*\}\}/',
      '<?php echo htmlspecialchars($1); ?>',
      $content,
    );

    /**
     * {{!! $variable !!}} syntax for unescaped variables
     */
    $content = preg_replace(
      '/\{\{\!\!\s*(.+?)\s*\!\!\}\}/',
      '<?php echo $1; ?>',
      $content,
    );

    /**
     * @foreach directive
     *
     * Usage:
     *  - @foreach($items as $item)
     *  - @endforeach
     */
    $content = preg_replace(
      '/@foreach\s*\((.+?)\)/',
      '<?php foreach($1): ?>',
      $content,
    );
    $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

    /**
     * @if directive
     *
     * Usage:
     *  - @if($condition)
     *  - @elseif($anotherCondition)
     *  - @else
     *  - @endif
     */
    $content = preg_replace('/@if\s*\((.+?)\)/', '<?php if($1): ?>', $content);
    $content = preg_replace(
      '/@elseif\s*\((.+?)\)/',
      '<?php elseif($1): ?>',
      $content,
    );
    $content = preg_replace('/@else/', '<?php else: ?>', $content);
    $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

    /**
     * @vite directive for Vite asset management
     *
     * Usage:
     *  - Development mode:
     *       @vite
     *         -> <script type="module" src="http://[::0]:5173/@vite/client"></script>
     *       @vite(['resources/js/app.js', 'resources/css/app.css'])
     *         -> <script type="module" src="http://[::0]:5173/resources/js/app.js"></script>
     *         -> <link rel="stylesheet" href="http://[::0]:5173/resources/css/app.css">
     *
     *   - Production mode:
     *       @vite
     *         -> (no output)
     *       @vite(['resources/js/app.js', 'resources/css/app.css'])
     *         -> <script type="module" src="/build/app-somehash.js"></script>
     *         -> <link rel="stylesheet" href="/build/app-somehash.css">
     *
     * Supports .js, .ts, .jsx, .tsx for scripts and .css, .sass, .scss, .less, .styl for styles.
     */
    $content = preg_replace_callback(
      '/@vite(?!ReactRefresh)(?:\(\s*\[([^]]*)\]\s*\))?/',
      function ($matches) {
        if (empty($matches[1])) {
          if ($this->isDev) {
            return "<?php echo '<script type=\"module\" src=\"' . \$this->viteUrl . '/@vite/client\"></script>'; ?>";
          } else {
            return '';
          }
        }

        $assets = array_map(
          'trim',
          explode(',', str_replace(['"', "'"], '', $matches[1])),
        );

        if ($this->isDev) {
          $tags = [];
          foreach ($assets as $asset) {
            if (preg_match('/\.(js|ts|jsx|tsx)$/i', $asset)) {
              $tags[] = "<?php echo '<script type=\"module\" src=\"' . \$this->viteUrl . '/' . '$asset' . '\"></script>'; ?>";
            } elseif (preg_match('/\.(css|sass|scss|less|styl)$/i', $asset)) {
              $tags[] = "<?php echo '<link rel=\"stylesheet\" href=\"' . \$this->viteUrl . '/' . '$asset' . '\">'; ?>";
            }
          }
          return implode("\n", $tags);
        }

        if (!file_exists($this->manifestPath)) {
          throw new \RuntimeException(
            "Vite manifest file not found: {$this->manifestPath}",
          );
        }

        $manifest = json_decode(file_get_contents($this->manifestPath), true);
        $tags = [];
        foreach ($assets as $asset) {
          if (!isset($manifest[$asset])) {
            throw new \RuntimeException(
              "Vite asset not found in manifest: $asset",
            );
          }
          $entry = $manifest[$asset];
          $file = $entry['file'];
          if (preg_match('/\.(js|ts|jsx|tsx)$/i', $asset)) {
            $tags[] = "<?php echo '<script type=\"module\" src=\"/build/$file\"></script>'; ?>";
          } elseif (preg_match('/\.(css|sass|scss|less|styl)$/i', $asset)) {
            $tags[] = "<?php echo '<link rel=\"stylesheet\" href=\"/build/$file\">'; ?>";
          }
        }
        return implode("\n", $tags);
      },
      $content,
    );

    /**
     * @viteReactRefresh directive
     *
     * Usage:
     *   - Development mode:
     *       @viteReactRefresh
     *         -> react-refresh preamble
     *   - Production mode:
     *       @viteReactRefresh
     *         -> (no output)
     */
    $content = preg_replace_callback(
      '/@viteReactRefresh/',
      function () {
        if ($this->isDev) {
          return <<<HTML
          <script type="module">
            import RefreshRuntime from "<?php echo \$this->viteUrl; ?>/@react-refresh"
            RefreshRuntime.injectIntoGlobalHook(window)
            window.\$RefreshReg$ = () => {}
            window.\$RefreshSig$ = () => (type) => type
            window.__vite_plugin_react_preamble_installed__ = true
          </script>
          HTML;
        }
        return '';
      },
      $content,
    );

    return $content;
  }
}
