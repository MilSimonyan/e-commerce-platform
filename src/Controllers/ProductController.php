<?php

declare(strict_types=1);

namespace Controllers;

use PDO;
use Repositories\ProductRepository;
use Throwable;

class ProductController {
    /**
     * @var \Repositories\ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @param \PDO $conn
     */
    public function __construct(PDO $conn)
    {
        $this->productRepository = new ProductRepository($conn);
    }

    /**
     * @return void
     */
    public function create(): void {
        $userId = $_SESSION['user_id'] ?? null;

        // not good way to check authorization
        if (!isset($userId)) {
            $this->response(['message' => 'Unauthorized', 401]);
            return;
        }

        $data = $this->input();

        $data = [
            'name'        => trim($data['name'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'price'       => $data['price']
        ];

        if (!$data['name'] || !$data['price']) {
            $this->response(['error' => 'Required data is missing'], 400);
            return;
        }

        try {
            $this->productRepository->create($data);
            $this->response(['message' => 'Product created successfully']);
        } catch (Throwable $e) {
            $this->response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @return void
     */
    public function getAll(): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        // not good way to check authorization
        if (!isset($userId)) {
            $this->response(['message' => 'Unauthorized', 401]);
            return;
        }

        $products = $this->productRepository->getAll();

        $this->response($products);
    }


    public function get(): void
    {
        // TODO implement
    }

    // todo move to trait or another service
    /**
     * Get input data.
     *
     * @return array
     */
    private function input(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }


    // todo move to trait or another service
    /**
     * @param array $data
     * @param int   $status
     *
     * @return void
     */
    private function response(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
