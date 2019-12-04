<?php

namespace App\Http\Controllers\Front;

use App\Shop\Posts\Repositories\PostRepository;
use App\Shop\Posts\Repositories\Interfaces\PostRepositoryInterface;
use App\Shop\Offers\Repositories\OfferRepository;
use App\Shop\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Shop\Categories\Repositories\CategoryRepository;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepo;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;
    /**
     * PostController constructor.
     *
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository,PostRepositoryInterface $postRepository, OfferRepositoryInterface $offerRepository)
    {
        $this->postRepo = $postRepository;
        $this->offerRepo = $offerRepository;
        $this->categoryRepo = $categoryRepository;

    }

	/**
     * Find the post via the slug
     *
     * @param string $slug
     * @return \App\Shop\Posts\Post
     */
    public function getPosts()
    {

        // $categories_list = $this->postRepo->listPosts("name", "asc");

        $posts = $this->postRepo->listActivePosts();
        $offers = $this->offerRepo->listActiveOffers();
        $categories_list = $this->categoryRepo->listCategories("name", "asc");

        
        return view('front.posts.post-list', [
            'posts' => $posts,
            'categories_list' => $categories_list,
            'offers' => $offers
        ]);
    }

    /**
     * Find the post via the slug
     *
     * @param string $slug
     * @return \App\Shop\Posts\Post
     */
    public function getPost(string $slug)
    {
        $post = $this->postRepo->findPostBySlug(['slug' => $slug]);

        // $repo = new PostRepository($post);

        // $posts = $repo->findPosts()->where('status', 1)->all();

        // $categories_list = $this->postRepo->listPosts("name", "asc");

        $offers = $this->offerRepo->listOffers();
        
        return view('front.posts.post', [
            'post' => $post,
            // 'posts' => $repo->paginateArrayResults($posts, 20),
            'categories_list' => [],
            'offers' => $offers
        ]);
    }
}
