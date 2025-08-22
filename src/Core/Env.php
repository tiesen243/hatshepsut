<?php

namespace Framework\Core;

class Env
{
  private static $variables = [];

  public static function load(string $filePath): void
  {
    if (!file_exists($filePath)) {
      throw new \Exception("Environment file not found: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      // Ignore comments and empty lines
      if (strpos($line, '#') === 0 || trim($line) === '') {
        continue;
      }

      // Parse the line into key-value pairs
      [$key, $value] = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value, " \t\n\r\0\x0B\"'");

      // Store the variable in the static array
      self::$variables[$key] = $value;
      putenv("$key=$value");
    }
  }

  public static function get(string $key, ?string $fallback = null): ?string
  {
    $value = $fallback;
    if (array_key_exists($key, self::$variables)) {
      $value = self::$variables[$key];
    } elseif (getenv($key) !== false) {
      $value = getenv($key);
    }

    return $value;
  }
}
