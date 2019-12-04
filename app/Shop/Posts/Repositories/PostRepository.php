<?php

namespace App\Shop\Posts\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Posts\Post;
use App\Shop\Posts\Exceptions\PostInvalidArgumentException;
use App\Shop\Posts\Exceptions\PostNotFoundException;
use App\Shop\Posts\Repositories\Interfaces\PostRepositoryInterface;
use App\Shop\Tools\UploadableTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    use UploadableTrait;

    /**
     * PostRepository constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        parent::__construct($post);
        $this->model = $post;
    }

    /**
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return \Illuminate\Support\Collection
     */
    public function listPosts(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * Create the post
     *
     * @param array $params
     *
     * @return Post
     * @throws PostInvalidArgumentException
     * @throws PostNotFoundException
     */
    public function createPost(array $params) : Post
    {
        try {

            $collection = collect($params);
            
            if (isset($params['name'])) {
                $slug = str_slug($params['name']);
            }

            if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
                $cover = $this->uploadOne($params['cover'], env('AWS_ROOT_FOLDER') . '/public/posts');
             $merge = $collection->merge(compact('slug', 'cover'));

            } else {
                $merge = $collection->merge(compact('slug'));
            }

            $post = new Post($merge->all());

            $post->save();

            return $post;
        } catch (QueryException $e) {
            throw new PostInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update the post
     *
     * @param array $params
     *
     * @return Post
     * @throws PostNotFoundException
     */
    public function updatePost(array $params) : Post
    {
        $post = $this->findPostById($this->model->id);
        $collection = collect($params)->except('_token');
        $slug = str_slug($collection->get('name'));

        if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
            $cover = $this->uploadOne($params['cover'], env('AWS_ROOT_FOLDER') . '/public/posts');
            $merge = $collection->merge(compact('slug', 'cover'));
        } else {
            $merge = $collection->merge(compact('slug'));
        }

        $post->save();
        $post->update($merge->all());
        
        return $post;
    }

    /**
     * @param int $id
     * @return Post
     * @throws PostNotFoundException
     */
    public function findPostById(int $id) : Post
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException($e->getMessage());
        }
    }

    /**
     * Return all the products associated with the category
     *
     * @return mixed
     */
    public function findPosts() : Post
    {
        return $this->model;
    }

    /**
     * Delete a post
     *
     * @return bool
     * @throws \Exception
     */
    public function deletePost() : bool
    {
        return $this->model->delete();
    }

    /**
     * @param $file
     * @param null $disk
     * @return bool
     */
    public function deleteFile(array $file, $disk = null) : bool
    {   
        return $this->findOneOrFail( $file["post"])->update(['cover' => null]);
    }

    /**
     * Return the post by using the slug as the parameter
     *
     * @param array $slug
     *
     * @return Post
     * @throws PostNotFoundException
     */
    public function findPostBySlug(array $slug) : Post
    {
        try {
            return $this->findOneByOrFail($slug);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException($e);
        }
    }
}
