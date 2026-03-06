<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostRepository
{
    public function create(array $data): Post
    {
        return Post::create([
            ...$data,
            'slug'         => $this->generateUniqueSlug($data['title']),
            'user_id'      => Auth::id(),
        ]);
    }

    public function findOrFail(int $id): Post
    {
        return Post::findOrFail($id);
    }

    public function update(Post $post, array $data): Post
    {
        // regenerate slug only if title changed
        if (isset($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        $post->update($data);

        return $post->fresh();
    }

    public function delete(Post $post): Post
    {
        $post->delete();

        return $post;
    }

     private function generateUniqueSlug(string $title): string
    {
        $slug          = Str::slug($title);
        $originalSlug  = $slug;
        $count         = 1;

        // keep checking until slug is unique
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
