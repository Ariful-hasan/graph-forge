<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function login(array $args): array
    {
        $credentials = [
            'email'    => $args['email'],
            'password' => $args['password'],
        ];

        if (!$token = Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        return [
            'token' => $token,
            'user'  => Auth::user(),
        ];
    }

    public function register(array $args): array
    {
        // validation logic lives here
        if ($args['password'] !== $args['password_confirmation']) {
            throw ValidationException::withMessages([
                'password' => ['Passwords do not match.'],
            ]);
        }

        // db interaction delegated to repository
        $user  = $this->userRepository->create($args);
        $token = Auth::login($user);

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }

    public function logout(): array
    {
        Auth::logout();

        return ['message' => 'Successfully logged out.'];
    }
}
