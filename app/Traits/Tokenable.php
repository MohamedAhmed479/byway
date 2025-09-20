<?php

namespace App\Traits;

use App\Models\PersonalAccessToken;
use App\Models\User;

Trait Tokenable
{
    public function createToken(int $userId, string $tokenName = 'default', array $abilities = [], int $expiresInMinutes = null): array
    {
        $userModel = new User();

        $user = $userModel->find($userId);
        if (!$user) {
            throw new \InvalidArgumentException('User not found or inactive');
        }

        $expiresAt = null;
        if ($expiresInMinutes) {
            $expiresAt = date('Y-m-d H:i:s', time() + ($expiresInMinutes * 60));
        }

        $tokenModel = new PersonalAccessToken();
        $tokenResult = $tokenModel->createToken($userId, $tokenName, $abilities, $expiresAt);

        if (empty($tokenResult)) {
            throw new \RuntimeException('Failed to create token');
        }

        return $tokenResult;
    }
}