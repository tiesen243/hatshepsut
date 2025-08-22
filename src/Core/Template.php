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
    private string $viteUrl,
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
    string $templateDir,
    string $cacheDir,
    string $viteUrl,
  ): static {
    if (self::$instance === null) {
      self::$instance = new static($templateDir, $cacheDir, $viteUrl);
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

    extract($data, EXTR_SKIP);

    $cachedFile = $this->compile($template);
    ob_start();
    include $cachedFile;
    $content = ob_get_clean();

    if (!empty($this->extends)) {
      $cachedFile = $this->compile($this->extends);
      ob_start();
      include $cachedFile;
      $content = ob_get_clean();
    }

    return $content;
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
     * Usage:
     *  - @include('partial')
     */
    $content = preg_replace(
      "/@include\([\"'](.+?)[\"']\)/",
      '<?php echo $this->render("$1"); ?>',
      $content,
    );

    /**
     * {{ $variable }} syntax for escaping variables
     * Usage:
     *  - {{ $variable }}
     */
    $content = preg_replace(
      '/\{\{\s*(.+?)\s*\}\}/',
      '<?php echo htmlspecialchars($1); ?>',
      $content,
    );

    /**
     * {{!! $variable !!}} syntax for unescaped variables
     * Usage:
     *  - {{!! $variable !!}}
     */
    $content = preg_replace(
      '/\{\{\!\!\s*(.+?)\s*\!\!\}\}/',
      '<?php echo $1; ?>',
      $content,
    );

    /**
     * @foreach directive
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
     * Usage:
     *  - @vite
     *    -> <script type="module" src="http://localhost:5173/@vite/client"></script>
     *  - @vite(['resources/js/app.js', 'resources/css/app.css'])
     *    -> <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
     *    -> <link rel="stylesheet" href="http://localhost:5173/resources/css/app.css">
     */
    $content = preg_replace_callback(
      '/@vite(?:\(\s*\[([^]]*)\]\s*\))?/', // capture optional [ ... ]
      function ($matches) {
        // Case: plain @vite -> HMR client
        if (empty($matches[1])) {
          return "<?php echo '<script type=\"module\" src=\"' . \$this->viteUrl . '/@vite/client\"></script>'; ?>";
        }

        // Case: @vite([...])
        $files = explode(',', $matches[1]);
        $files = array_map('trim', $files);
        $files = array_map(
          fn($file) => trim($file, " \t\n\r\0\x0B\"'"),
          $files,
        );

        $tags = [];
        foreach ($files as $file) {
          $ext = pathinfo($file, PATHINFO_EXTENSION);

          switch ($ext) {
            case 'js':
              $tags[] = "<?php echo '<script type=\"module\" src=\"' . \$this->viteUrl . '/' . '$file' . '\"></script>'; ?>";
              break;
            case 'css':
              $tags[] = "<?php echo '<link rel=\"stylesheet\" href=\"' . \$this->viteUrl . '/' . '$file' . '\">'; ?>";
              break;
            default:
              $tags[] =
                "<?php echo '<!-- [vite] Unsupported file type: " .
                htmlspecialchars($file, ENT_QUOTES) .
                " -->'; ?>";
              break;
          }
        }

        return implode("\n", $tags);
      },
      $content,
    );

    return $content;
  }
}
