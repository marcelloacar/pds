<?php

namespace App\Shop\Posts\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Posts\Post;
use Illuminate\Support\Collection;

interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function listPosts(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createPost(array $params) : Post;

    public function updatePost(array $params) : Post;

    public function findPostById(int $id) : Post;

    public function deletePost() : bool;

    public function deleteFile(array $file, $disk = null) : bool;

    public function findPostBySlug(array $slug) : Post;

}
