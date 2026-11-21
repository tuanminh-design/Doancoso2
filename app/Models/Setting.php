<?php
namespace App\Models;

class Setting extends Model
{
    private static ?array $cached = null;

    public function getAll(): array
    {
        if (self::$cached === null) {
            $rows = $this->fetchAll('SELECT setting_key, setting_value FROM site_settings');
            $map = [];
            foreach ($rows as $r) {
                $map[$r['setting_key']] = $r['setting_value'];
            }
            self::$cached = $map;
        }
        return self::$cached;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $all = $this->getAll();
        return $all[$key] ?? $default;
    }

    public function set(string $key, ?string $value, string $group = 'general'): bool
    {
        $sql = 'INSERT INTO site_settings (setting_key, setting_value, group_name) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)';
        $ok = $this->execute($sql, [$key, $value, $group]);
        self::$cached = null; // Clear cache
        return $ok;
    }

    public function updateMultiple(array $settings): bool
    {
        $this->beginTransaction();
        try {
            foreach ($settings as $key => $val) {
                $this->set($key, (string)$val);
            }
            $this->commit();
            self::$cached = null;
            return true;
        } catch (\Exception $e) {
            $this->rollBack();
            return false;
        }
    }
}
