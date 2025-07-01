<?php

declare(strict_types=1);

namespace Traits;

use Exception;

trait PasswordValidator {
    /**
     * @throws \Exception
     */
    public function validatePassword(string $password): bool
    {
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception('Password must include at least one uppercase letter.');
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception('Password must include at least one number.');
        }

        if (!preg_match('/[\W]/', $password)) {
          throw new Exception('Password must include at least one special character (e.g., !@#$%).');
        }

        return true;
    }
}