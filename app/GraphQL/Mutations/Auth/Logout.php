<?php

namespace App\GraphQL\Mutations\Auth;

use App\Services\AuthService;

class Logout
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function __invoke($rootValue, array $args): array
    {
        return $this->authService->logout();
    }
}
