<?php
namespace App\Models;

class ContactMessage extends Model
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)';
        $this->execute($sql, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['subject'],
            $data['message']
        ]);
        return (int)$this->lastInsertId();
    }

    public function getAll(int $limit = 20, int $offset = 0): array
    {
        return $this->fetchAll('SELECT * FROM contact_messages ORDER BY id DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset);
    }

    public function countAll(): int
    {
        return (int)$this->fetchColumn('SELECT COUNT(*) FROM contact_messages');
    }

    public function markAsRead(int $id): bool
    {
        return $this->execute('UPDATE contact_messages SET status = "read" WHERE id = ?', [$id]);
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM contact_messages WHERE id = ?', [$id]);
    }
}
