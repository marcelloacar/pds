<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Offers\Repositories\OfferRepository;
use App\Shop\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;

class HomeController
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
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, OfferRepositoryInterface $offerRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->offerRepo = $offerRepository;
        $this->productRepo = $productRepository;

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories_list = $this->categoryRepo->listCategories("name", "asc");
        $products = $this->productRepo->listProducts()->where("quantity", ">", 0);
        $offers = $this->offerRepo->listActiveOffers();

        return view('front.home', compact('categories_list', 'offers', 'products'));
    }
}
