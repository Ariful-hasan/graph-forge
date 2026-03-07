<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PostService
{
    public function __construct(
        protected PostRepository $postRepository
    ) {}

    public function create(array $args): Post
    {
        return $this->postRepository->create($args);
    }

    public function update(array $args): Post
    {
        $post = $this->postRepository->findOrFail($args['id']);

        Auth::user()->can('update', $post) || abort(403, 'Unauthorized');

        return $this->postRepository->update($post, $args);
    }

    public function delete(array $args): Post
    {
        $post = $this->postRepository->findOrFail($args['id']);

        Auth::user()->can('delete', $post) || abort(403, 'Unauthorized.');

        return $this->postRepository->delete($post);
    }
}
