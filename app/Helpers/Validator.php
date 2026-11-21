<?php
namespace App\Helpers;

class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data): self
    {
        return new self($data);
    }

    public function required(string $field, string $message = ''): self
    {
        $val = trim((string)($this->data[$field] ?? ''));
        if ($val === '') {
            $this->addError($field, $message ?: "Trường {$field} là bắt buộc.");
        }
        return $this;
    }

    public function email(string $field, string $message = ''): self
    {
        $val = trim((string)($this->data[$field] ?? ''));
        if ($val !== '' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message ?: "Địa chỉ email không hợp lệ.");
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $message = ''): self
    {
        $val = (string)($this->data[$field] ?? '');
        if ($val !== '' && mb_strlen($val) < $min) {
            $this->addError($field, $message ?: "Trường {$field} phải có ít nhất {$min} ký tự.");
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $message = ''): self
    {
        $val = (string)($this->data[$field] ?? '');
        if ($val !== '' && mb_strlen($val) > $max) {
            $this->addError($field, $message ?: "Trường {$field} không được vượt quá {$max} ký tự.");
        }
        return $this;
    }

    public function matches(string $field, string $otherField, string $message = ''): self
    {
        $val = (string)($this->data[$field] ?? '');
        $otherVal = (string)($this->data[$otherField] ?? '');
        if ($val !== $otherVal) {
            $this->addError($field, $message ?: "Trường {$field} và {$otherField} không khớp nhau.");
        }
        return $this;
    }

    public function numeric(string $field, string $message = ''): self
    {
        $val = $this->data[$field] ?? null;
        if ($val !== null && $val !== '' && !is_numeric($val)) {
            $this->addError($field, $message ?: "Trường {$field} phải là chữ số.");
        }
        return $this;
    }

    public function minVal(string $field, float $min, string $message = ''): self
    {
        $val = $this->data[$field] ?? null;
        if ($val !== null && $val !== '' && (float)$val < $min) {
            $this->addError($field, $message ?: "Giá trị phải lớn hơn hoặc bằng {$min}.");
        }
        return $this;
    }

    public function phone(string $field, string $message = ''): self
    {
        $val = trim((string)($this->data[$field] ?? ''));
        if ($val !== '' && !preg_match('/^[0-9+() -]{9,15}$/', $val)) {
            $this->addError($field, $message ?: "Số điện thoại không hợp lệ.");
        }
        return $this;
    }

    public function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    public static function sanitize(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = self::sanitize($v);
            }
            return $data;
        }
        if (is_string($data)) {
            return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
        }
        return $data;
    }
}
