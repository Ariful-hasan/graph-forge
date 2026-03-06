<?php

namespace App\GraphQL\Mutations\Post;

use App\Models\Post;
use App\Services\PostService;

class CreatePost
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function __invoke($rootValue, array $args): Post
    {
        try {
            return $this->postService->create($args);
        } catch (\Exception $e) {
            throw new \GraphQL\Error\UserError($e->getMessage());
        }
    }
}
