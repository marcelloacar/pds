<?php

namespace App\Http\Controllers\Admin\Offers;

use App\Shop\Offers\Repositories\OfferRepository;
use App\Shop\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Shop\Offers\Requests\CreateOfferRequest;
use App\Shop\Offers\Requests\UpdateOfferRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $offerRepo;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $offerRepository
     */
    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepo = $offerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->offerRepo->listOffers('created_at', 'desc');

        return view('admin.offers.list', [
            'offers' => $this->offerRepo->paginateArrayResults($list->all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.offers.create', [
            'offers' => $this->offerRepo->listOffers('name', 'asc')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOfferRequest $request)
    {
        $this->offerRepo->createOffer($request->except('_token', '_method'));

        return redirect()->route('admin.offers.index')->with('message', 'Anúncio criada com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = $this->offerRepo->findOfferById($id);
        return view('admin.offers.show', [
            'offer' => $offer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.offers.edit', [
            'offers' => $this->offerRepo->listOffers('name', 'asc', $id),
            'offer' => $this->offerRepo->findOfferById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateOfferRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOfferRequest $request, $id)
    {
        $offer = $this->offerRepo->findOfferById($id);

        $update = new OfferRepository($offer);
        $update->updateOffer($request->except('_token', '_method'));

        $request->session()->flash('message', 'Atualizado com sucesso');
        return redirect()->route('admin.offers.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $offer = $this->offerRepo->findOfferById($id);
        $offer->delete();

        request()->session()->flash('message', 'Removido com sucesso');
        return redirect()->route('admin.offers.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {

        $this->offerRepo->deleteFile($request->only('offer'));
        request()->session()->flash('message', 'Imagem removida com sucesso');
        return redirect()->route('admin.offers.edit', $request->input('offer'));
    }
}
