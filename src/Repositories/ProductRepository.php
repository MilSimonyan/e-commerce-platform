<?php

declare(strict_types=1);

namespace Repositories;

use PDO;
use PDOException;

class ProductRepository extends BaseRepository
{
    public function __construct(
        private PDO $conn
    ) {
        parent::__construct($this->conn);
    }

    /**
     * Create product.
     *
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO products (name, description, price) VALUES (?, ?, ?)');
            return $stmt->execute(
                [$data['name'], $data['description'], $data['price']]
            );
        } catch (PDOException $e) {
            throw new \RuntimeException('Failed to create product: '.$e->getMessage());
        }
    }

    public function update(array $data): array
    {
        // TODO: Implement update() method.
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute([]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function get(int $id): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        return (bool)$stmt->fetch();
    }
}
