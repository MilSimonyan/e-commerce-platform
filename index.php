<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Controllers/AuthController.php';
require_once __DIR__ . '/src/Controllers/ProductController.php';
require_once __DIR__ . '/src/Repositories/UserRepository.php';
require_once __DIR__ . '/src/Repositories/ProductRepository.php';

use Controllers\ProductController;
use Dotenv\Dotenv;
use Controllers\AuthController;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database config
$config = require __DIR__ . '/config/database.php';
$db = new Database($config);
$conn = $db->getConnection();

// Init Controller
$auth = new AuthController($conn);
$product = new ProductController($conn);

// ===== Router =====
header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ("$method $uri") {
    case 'GET /':
        echo json_encode(['message' => 'Home']);
        break;

    case 'POST /register':
        $auth->register();
        break;

    case 'POST /login':
        $auth->login();
        break;

    case 'POST /logout':
        $auth->logout();
        break;

    case 'GET /me':
        $auth->me();
        break;

    case 'POST /product':
        $product->create();
        break;

    case 'GET /products':
        $product->getAll();
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => '404 - Route not found']);
        break;
}
