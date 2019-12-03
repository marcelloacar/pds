<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Offers\Repositories\OfferRepository;
use App\Shop\Offers\Repositories\Interfaces\OfferRepositoryInterface;

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
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, OfferRepositoryInterface $offerRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->offerRepo = $offerRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories_list = $this->categoryRepo->listCategories("name", "asc");
        $category = $categories_list[0];
        $categories_list[0]->side_bar_active = true;
        $offers = $this->offerRepo->listOffers();

        return view('front.index', compact('category','categories_list', 'offers'));
    }
}
