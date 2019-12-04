<?php

namespace App\Shop\Offers\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Offers\Offer;
use App\Shop\Offers\Exceptions\OfferInvalidArgumentException;
use App\Shop\Offers\Exceptions\OfferNotFoundException;
use App\Shop\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Shop\Tools\UploadableTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class OfferRepository extends BaseRepository implements OfferRepositoryInterface
{
    use UploadableTrait;

    /**
     * OfferRepository constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct($offer);
        $this->model = $offer;
    }

    /**
     * List all the offers
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return \Illuminate\Support\Collection
     */
    public function listOffers(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }


    /**
     * List all active offers
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return \Illuminate\Support\Collection
     */
    public function listActiveOffers(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->where("status", 1)->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * Create the offer
     *
     * @param array $params
     *
     * @return Offer
     * @throws OfferInvalidArgumentException
     * @throws OfferNotFoundException
     */
    public function createOffer(array $params) : Offer
    {
        try {

            $collection = collect($params);
            
            if (isset($params['name'])) {
                $slug = str_slug($params['name']);
            }

            if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
                $cover = $this->uploadOne($params['cover'], env('AWS_ROOT_FOLDER') . '/public/offers');
                   $merge = $collection->merge(compact('slug', 'cover'));

            } else {
                $merge = $collection->merge(compact('slug'));
            }

            $offer = new Offer($merge->all());

            $offer->save();

            return $offer;
        } catch (QueryException $e) {
            throw new OfferInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update the offer
     *
     * @param array $params
     *
     * @return Offer
     * @throws OfferNotFoundException
     */
    public function updateOffer(array $params) : Offer
    {
        $offer = $this->findOfferById($this->model->id);
        $collection = collect($params)->except('_token');
        $slug = str_slug($collection->get('name'));

        if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
            $cover = $this->uploadOne($params['cover'], env('AWS_ROOT_FOLDER') . '/public/offers');
            $merge = $collection->merge(compact('slug', 'cover'));

        } else {
            $merge = $collection->merge(compact('slug'));
        }

        $offer->save();
        $offer->update($merge->all());
        
        return $offer;
    }

    /**
     * @param int $id
     * @return Offer
     * @throws OfferNotFoundException
     */
    public function findOfferById(int $id) : Offer
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new OfferNotFoundException($e->getMessage());
        }
    }

    /**
     * Delete a offer
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteOffer() : bool
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
        return $this->findOneOrFail( $file["offer"])->update(['cover' => null]);
    }

    /**
     * Return the offer by using the slug as the parameter
     *
     * @param array $slug
     *
     * @return Offer
     * @throws OfferNotFoundException
     */
    public function findOfferBySlug(array $slug) : Offer
    {
        try {
            return $this->findOneByOrFail($slug);
        } catch (ModelNotFoundException $e) {
            throw new OfferNotFoundException($e);
        }
    }
}
