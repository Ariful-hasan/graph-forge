<?php

namespace App\GraphQL\Mutations\Post;

use App\Models\Post;
use App\Services\PostService;

class UpdatePost
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function __invoke($rootValue, array $args): Post
    {
        return $this->postService->update($args);
    }
}
