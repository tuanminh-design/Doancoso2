<?php
namespace App\Models;

class User extends Model
{
    public function findById(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, phone, password, address, role, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->execute($sql, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['address'] ?? null,
            $data['role'] ?? 'customer',
            $data['status'] ?? 'active'
        ]);
        return (int)$this->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool
    {
        $sql = 'UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?';
        return $this->execute($sql, [
            $data['name'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $id
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->execute('UPDATE users SET password = ? WHERE id = ?', [$hashed, $id]);
    }

    public function getAll(string $search = '', int $limit = 20, int $offset = 0): array
    {
        $sql = 'SELECT id, name, email, phone, address, role, status, created_at FROM users';
        $params = [];

        if ($search !== '') {
            $sql .= ' WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?';
            $term = "%{$search}%";
            $params = [$term, $term, $term];
        }

        $sql .= ' ORDER BY id DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        return $this->fetchAll($sql, $params);
    }

    public function countAll(string $search = ''): int
    {
        $sql = 'SELECT COUNT(*) FROM users';
        $params = [];

        if ($search !== '') {
            $sql .= ' WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?';
            $term = "%{$search}%";
            $params = [$term, $term, $term];
        }

        return (int)$this->fetchColumn($sql, $params);
    }

    public function updateRole(int $id, string $role): bool
    {
        if (!in_array($role, ['customer', 'admin'])) return false;
        return $this->execute('UPDATE users SET role = ? WHERE id = ?', [$role, $id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['active', 'blocked'])) return false;
        return $this->execute('UPDATE users SET status = ? WHERE id = ?', [$status, $id]);
    }
}
