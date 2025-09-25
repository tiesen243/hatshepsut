<?php

namespace Framework\Core;

class Template
{
  private static ?Template $instance = null;

  private string $extends = '';

  private function __construct(
    private string $viewDir,
    private string $cacheDir,
    private array $appConfig = [],
  ) {
    if (!is_dir($viewDir) || !is_readable($viewDir)) {
      throw new \InvalidArgumentException("View directory '{$viewDir}' does not exist or is not readable.");
    }
    if (!is_dir($cacheDir) || !is_writable($cacheDir)) {
      throw new \InvalidArgumentException("Cache directory '{$cacheDir}' does not exist or is not writable.");
    }

    $this->viewDir = rtrim($viewDir, '/\\').DIRECTORY_SEPARATOR;
    $this->cacheDir = rtrim($cacheDir, '/\\').DIRECTORY_SEPARATOR;
  }

  public static function create(
    string $viewDir,
    string $cacheDir,
    array $appConfig,
  ): self {
    if (null === self::$instance) {
      self::$instance = new self($viewDir, $cacheDir, $appConfig);
    }

    return self::$instance;
  }

  public static function getInstance(): self
  {
    if (null === self::$instance) {
      throw new \Exception('Template not initialized. Call create() first.');
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

  private function renderPartial(string $template, array $data): string
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
      $this->viewDir.
      str_replace('.', DIRECTORY_SEPARATOR, $template).
      '.tpl.php';
    $cachedFile = $this->cacheDir.md5($template).'.php';

    if (
      !file_exists($cachedFile)
      || filemtime($cachedFile) < filemtime($templateFile)
    ) {
      if (file_exists($templateFile)) {
        $content = file_get_contents($templateFile);
        $parsed = $this->parse($content);
        file_put_contents($cachedFile, $parsed);
      } else {
        echo 'could not find template file: '.$templateFile;
      }
    }

    return $cachedFile;
  }

  private function parse(string $content): string
  {
    // Handle @extends('layout')
    $content = preg_replace(
      '/@extends\([\'"](.+?)[\'"]\)/',
      '<?php $this->extends = "$1"; ?>',
      $content,
    );

    // Handle @yield('section', 'default')
    $content = preg_replace_callback(
      '/@yield\(\s*[\'"](.+?)[\'"]\s*(?:,\s*[\'"](.*?)[\'"])?\s*\)/',
      function ($matches) {
        [, $name, $default] = $matches + [null, null, ''];

        return '<?php echo $this->sections["'.
          $name.
          '"] ?? "'.
          addslashes($default).
          '"; ?>';
      },
      $content,
    );

    // Handle @section('section') ... @endsection
    $content = preg_replace(
      ['/@section\([\'"](.+?)[\'"]\)/', '/@endsection/'],
      [
        '<?php ob_start(); $name = "$1"; ?>',
        '<?php $this->sections[$name] = ob_get_clean(); ?>',
      ],
      $content,
    );

    // Handle @if, @elseif, @else, @endif
    $content = preg_replace(
      '/@if\s*\((.*)\)\s*$/m',
      '<?php if ($1): ?>',
      $content,
    );
    $content = preg_replace(
      '/@elseif\s*\((.*?)\)/',
      '<?php elseif ($1): ?>',
      $content,
    );
    $content = preg_replace('/@else/', '<?php else: ?>', $content);
    $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

    // Handle @foreach, @endforeach
    $content = preg_replace(
      '/@foreach\s*\((.*?)\)/',
      '<?php foreach ($1): ?>',
      $content,
    );
    $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

    // Handle {{ variable }}
    $content = preg_replace(
      '/\{\{\s*(.+?)\s*\}\}/',
      '<?php echo htmlspecialchars($1, ENT_QUOTES, \'UTF-8\'); ?>',
      $content,
    );

    // Handle {!! variable !!}
    $content = preg_replace(
      '/\{\!\!\s*(.+?)\s*\!\}/',
      '<?php echo $1; ?>',
      $content,
    );

    // Handle {{-- comment --}}
    $content = preg_replace(
      '/\{\-\-\s*(.*?)\s*\-\-\}/s',
      '<?php /* $1 */ ?>',
      $content,
    );

    // Vite asset handling
    return preg_replace_callback(
      '/@vite(?!ReactRefresh)(?:\(\s*\[([^]]*)\]\s*\))?/',
      function ($matches) {
        $viteUrl = rtrim($this->appConfig['vite_url'], '/');
        $assets = [];
        $tags = [];

        if (empty($matches[1]) && 'development' === $this->appConfig['env']) {
          return "<script type=\"module\" src=\"{$viteUrl}/@vite/client\"></script>";
        }

        if (!empty($matches[1]))
          $assets = array_map(
            'trim',
            explode(',', str_replace(['"', "'"], '', $matches[1])),
          );
        if (empty($assets)) {
          return '';
        }

        if ('development' === $this->appConfig['env']) {
          foreach ($assets as $asset) {
            if (preg_match('/\.(js|ts|jsx|tsx)$/i', $asset)) {
              $tags[] = "<script type=\"module\" src=\"{$viteUrl}/{$asset}\"></script>";
            } elseif (preg_match('/\.(css|sass|scss|less|styl)$/i', $asset)) {
              $tags[] = "<link rel=\"stylesheet\" href=\"{$viteUrl}/{$asset}\" />";
            }
          }
        } else {
          $manifestPath =
            $this->appConfig['base_path'].'/public/build/.vite/manifest.json';
          if (!file_exists($manifestPath)) {
            throw new \Exception("Vite manifest file not found at '{$manifestPath}'.");
          }
          $manifest = json_decode(file_get_contents($manifestPath), true);

          foreach ($assets as $asset) {
            if (!isset($manifest[$asset])) {
              continue;
            }
            $entryFile = $manifest[$asset]['file'];
            if (preg_match('/\.(js|ts|jsx|tsx)$/i', $asset)) {
              $tags[] = "<script type=\"module\" src=\"/build/{$entryFile}\"></script>";
            } elseif (preg_match('/\.(css|sass|scss|less|styl)$/i', $asset)) {
              $tags[] = "<link rel=\"stylesheet\" href=\"/build/{$entryFile}\" />";
            }
          }
        }

        return implode("\n", $tags);
      },
      $content,
    );
  }
}
