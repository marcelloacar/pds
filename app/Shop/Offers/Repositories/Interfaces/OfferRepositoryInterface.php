<?php

namespace App\Shop\Offers\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Offers\Offer;
use Illuminate\Support\Collection;

interface OfferRepositoryInterface extends BaseRepositoryInterface
{
    public function listOffers(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createOffer(array $params) : Offer;

    public function updateOffer(array $params) : Offer;

    public function findOfferById(int $id) : Offer;

    public function deleteOffer() : bool;

    public function deleteFile(array $file, $disk = null) : bool;

    public function findOfferBySlug(array $slug) : Offer;

}
