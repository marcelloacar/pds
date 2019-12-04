<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Repositories\CategoryRepository;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Offers\Repositories\OfferRepository;
use App\Shop\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepo;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, OfferRepositoryInterface $offerRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->offerRepo = $offerRepository;
    }

    /**
     * Find the category via the slug
     *
     * @param string $slug
     * @return \App\Shop\Categories\Category
     */
    public function getCategory(string $slug)
    {
        $category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);

        $repo = new CategoryRepository($category);

        $products = $repo->findProducts()->where('status', 1)->where('quantity', '>', 0)->all();

        $categories_list = $this->categoryRepo->listCategories("name", "asc");

        $offers = $this->offerRepo->listActiveOffers();
        
        return view('front.categories.category', [
            'category' => $category,
            'products' => $products,
            'categories_list' => $categories_list,
            'offers' => $offers
        ]);
    }
}
