<?php

namespace Framework\Core;

class Validator
{
  public function __construct(
    private array $rules = [],
    private array $data = [],
    private array $errors = [],
  ) {
  }

  public function parse(array $data): self
  {
    foreach ($this->rules as $field => $rule) {
      $value = $data[$field] ?? null;
      $parsedRule = $this->parseRule($rule);

      $type = $parsedRule['type'];
      $ops = $parsedRule['ops'];
      $literals = $parsedRule['literals'];
      $isOptional = $parsedRule['optional'];

      if (null === $value && !$isOptional) {
        $this->errors[$field] = 'Field is required.';
        continue;
      }

      if ('string' === $type) {
        if (!is_string($value)) {
          $this->errors[$field] = 'Value must be a string.';
          continue;
        }
        if (!empty($literals) && $this->validateLiteral($field, $value, $literals)) continue;
        if (isset($ops['pattern']) && $this->validatePattern($field, $value, $ops['pattern'])) continue;
        if ($this->validateLengthOrValue($type, $field, $value, $ops)) continue;
      }

      if ('int' === $type || 'float' === $type) {
        if ('int' === $type && !is_int($value)) {
          $this->errors[$field] = 'Value must be an integer.';
          continue;
        }
        if ('float' === $type && !is_float($value) && !is_int($value)) {
          $this->errors[$field] = 'Value must be a float.';
          continue;
        }
        if ($this->validateLengthOrValue($type, $field, $value, $ops)) continue;
      }

      if ('boolean' === $type && $this->validateBoolean($field, $value)) continue;
    }

    return $this;
  }

  public function isValid(): bool
  {
    return !$this->errors;
  }

  public function errors(): array
  {
    return $this->errors;
  }

  public function data(): array
  {
    return $this->data;
  }

  private function parseRule(string $rule): array
  {
    $result = [
      'type' => null,
      'ops' => [],
      'literals' => null,
      'optional' => false,
    ];

    // Optional field (e.g., string?)
    if (str_ends_with($rule, '?')) {
      $result['optional'] = true;
      $rule = substr($rule, 0, -1);
    }

    // Literal values (e.g., "value1"|"value2")
    if (preg_match('/^("[^"]+"(\|"?[^"]+"?)*)$/', $rule)) {
      $result['type'] = 'string';
      $result['literals'] = array_map(fn ($v) => trim($v, '"'), explode('|', $rule));

      return $result;
    }

    // Regex pattern (e.g., /pattern/)
    if (preg_match('/^\/.*\/[a-zA-Z]*$/', $rule)) {
      $result['type'] = 'string';
      $result['ops']['pattern'] = $rule;

      return $result;
    }

    // Type and operators (string, string.email, string.date, int, float, boolean with operators like >=, <=, >, <, =)
    if (preg_match('/^(string)(?:\.(email|date))?(?:([<>]=?|=)(\d+))?(?:([<>]=?|=)(\d+))?$|^(int|float)(?:([<>]=?|=)(\d+))?(?:([<>]=?|=)(\d+))?$|^(boolean)$/', $rule, $matches)) {
      if (!empty($matches[1])) {
        $result['type'] = $matches[1];
        if (isset($matches[2])) {
          if ('email' === $matches[2]) {
            $result['ops']['pattern'] = '/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,}$/';
          } elseif ('date' === $matches[2]) {
            $result['ops']['pattern'] = '/^\d{4}-\d{2}-\d{2}$/';
          }

          if (isset($matches[3]) && isset($matches[4])) {
            $result['ops'][$matches[3]] = (int) $matches[4];
          }
          if (isset($matches[5]) && isset($matches[6])) {
            $result['ops'][$matches[5]] = (int) $matches[6];
          }
        }
      } elseif (!empty($matches[7])) {
        $result['type'] = $matches[7];
        if (isset($matches[8]) && isset($matches[9])) {
          $result['ops'][$matches[8]] = (int) $matches[9];
        }
        if (isset($matches[10]) && isset($matches[11])) {
          $result['ops'][$matches[10]] = (int) $matches[11];
        }
      } elseif (!empty($matches[12])) {
        $result['type'] = $matches[12];
      }

      return $result;
    }

    return $result;
  }

  private function validateLiteral(string $field, string $value, array $literals): bool
  {
    if (!in_array($value, $literals, true)) {
      $this->errors[$field] = 'Value must be one of: '.implode(', ', $literals).'.';

      return false;
    }

    $this->data[$field] = $value;

    return true;
  }

  private function validateBoolean(string $field, $value): bool
  {
    if (!is_bool($value)) {
      $this->errors[$field] = 'Value must be a boolean.';

      return false;
    }

    $this->data[$field] = $value;

    return true;
  }

  private function validatePattern(string $field, string $value, string $pattern): bool
  {
    if (!preg_match($pattern, $value)) {
      $this->errors[$field] = 'Value does not match the required pattern.';

      return false;
    }

    $this->data[$field] = $value;

    return true;
  }

  private function validateLengthOrValue(string $type, string $field, $value, array $ops): bool
  {
    $comparisons = [
      '>=' => [fn () => isset($ops['>='])
        && 'string' === $type ? strlen($value) < @$ops['>='] : $value < @$ops['>='],
        'string' === $type ? 'String length must be at least '.@$ops['>='].' characters.' : 'Value must be at least '.@$ops['>='].'.',
      ],
      '>' => [fn () => isset($ops['>'])
        && 'string' === $type ? strlen($value) <= @$ops['>'] : $value <= @$ops['>'],
        'string' === $type ? 'String length must be greater than '.@$ops['>'].' characters.' : 'Value must be greater than '.@$ops['>'].'.',
      ],
      '<=' => [fn () => isset($ops['<='])
        && 'string' === $type ? strlen($value) > @$ops['<='] : $value > @$ops['<='],
        'string' === $type ? 'String length must be at most '.@$ops['<='].' characters.' : 'Value must be at most '.@$ops['<='].'.',
      ],
      '<' => [fn () => isset($ops['<'])
        && 'string' === $type ? strlen($value) >= @$ops['<'] : $value >= @$ops['<'],
        'string' === $type ? 'String length must be less than '.@$ops['<'].' characters.' : 'Value must be less than '.@$ops['<'].'.',
      ],
      '=' => [fn () => isset($ops['=']) && $value !== @$ops['='],
        'string' === $type ? 'String length must be exactly '.@$ops['='].' characters.' : 'Value must be exactly '.@$ops['='].'.',
      ],
    ];

    foreach ($comparisons as $op => [$failCheck, $msg])
      if (isset($ops[$op]) && $failCheck()) {
        $this->errors[$field] = $msg;

        return false;
      }

    $this->data[$field] = $value;

    return true;
  }
}
