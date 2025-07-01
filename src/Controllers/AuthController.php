<?php

declare(strict_types=1);

namespace Controllers;

use PDO;
use Repositories\UserRepository;
use Throwable;
use Traits\PasswordValidator;

class AuthController
{
    use PasswordValidator;

    /**
     * @var \Repositories\UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param \PDO $conn
     */
    public function __construct(PDO $conn)
    {
        $this->userRepository = new UserRepository($conn);
    }

    /**
     * User Registration.
     *
     * @return void
     * @throws \Exception
     */
    public function register(): void
    {
        $data = $this->input();

        $data = [
            'first_name' => trim($data['first_name'] ?? ''),
            'last_name'  => trim($data['last_name'] ?? ''),
            'email'      => trim($data['email'] ?? ''),
            'password'   => trim($data['password'] ?? ''),
        ];

        if (!$data['email'] || !$data['password'] || !$data['first_name'] || !$data['last_name']) {
            $this->response(['error' => 'Required data is missing'], 400);
            return;
        }

        $validatedPassword = $this->validatePassword($data['password']);
        
        $isEmailAlreadyInUse = $this->userRepository->emailExists($data['email']);

        if ($isEmailAlreadyInUse) {
            $this->response(['message' => 'Email already in use']);
            return;
        }

        if ($validatedPassword) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

            try {
                $this->userRepository->create($data);
                $this->login();
                $this->response(['message' => 'User registered successfully']);
            } catch (Throwable $e) {
                $this->response(['error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * LogIn user to system.
     *
     * @return void
     */
    public function login(): void
    {
        $data = $this->input();

        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$email || !$password) {
            $this->response(['error' => 'Email and password are required'], 400);
            return;
        }

        try {
            $user = $this->userRepository->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $this->response(['error' => 'Invalid credentials'], 401);
                return;
            }

            session_start();
            $_SESSION['user_id'] = $user['id'];

            $this->response(['message' => 'Logged in successfully']);
        } catch (Throwable $e) {
            $this->response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * LogOut user from system.
     *
     * @return void
     */
    public function logout(): void
    {
        session_start();
        session_destroy();
        $this->response(['message' => 'Logged out successfully']);
    }

    /**
     * @return void
     */
    public function me(): void
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            $this->response(['error' => 'Unauthorized'], 401);
            return;
        }

        try {
            $user = $this->userRepository->get((int)$userId);

            if (!$user) {
                $this->response(['error' => 'User not found'], 404);
                return;
            }

            $this->response($user);
        } catch (Throwable $e) {
            $this->response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get input data.
     *
     * @return array
     */
    private function input(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

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
