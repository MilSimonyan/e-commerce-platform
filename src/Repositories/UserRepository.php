<?php

declare(strict_types=1);

namespace Repositories;

use PDO;
use PDOException;

class UserRepository extends BaseRepository
{
    public function __construct(
        private PDO $conn
    ) {
        parent::__construct($this->conn);
    }

    /**
     * Find user by email.
     *
     * @param string $email
     *
     * @return array|false
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');
            return $stmt->execute(
                [$data['first_name'], $data['last_name'], $data['email'], $data['password']]
            );
        } catch (PDOException $e) {
            throw new \RuntimeException('Failed to create user: '.$e->getMessage());
        }
    }

    /**
     * Check if email already exits.
     *
     * @param string $email
     *
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);

        return (bool)$stmt->fetchColumn();
    }

    /**
     * Get user.
     * @param int $id
     *
     * @return array
     */
    public function get(int $id): array
    {
        $stmt = $this->conn->prepare("SELECT id, first_name, last_name, email FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(array $data): array
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement get() method.
    }

    public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
    }
}
