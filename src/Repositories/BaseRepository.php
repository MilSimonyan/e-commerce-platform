<?php

declare(strict_types=1);

namespace Repositories;

use PDO;

abstract class BaseRepository {

    /**
     * @param \PDO $conn
     */
    public function __construct(
        private PDO $conn
    ) {
    }

    /**
     * Create model.
     * @param array $data
     *
     * @return bool
     */
    abstract public function create(array $data): bool;

    /**
     * Update model.
     *
     * @param array $data
     *
     * @return array
     */
    abstract public function update(array $data): array;

    /**
     * Get array of Model.
     *
     * @return array
     */
    abstract public function getAll(): array;

    /**
     * Get one model.
     *
     * @param int $id
     *
     * @return array
     */
    abstract public function get(int $id): array;

    /**
     * Delete model
     *
     * @param int $id
     *
     * @return bool
     */
    abstract public function delete(int $id): bool;
}