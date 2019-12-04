@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    <div class="container">
        <div class="col-md-3">
            @include('front.categories.sidebar-category', ['categories_list' => $categories_list])
            @include('front.offers', ['offers' => $offers])
        </div>
        <div class="col-md-9">
            <div class="row">
                @include('front.products.product-list', ['products' => $products->where('status', 1)])
            </div>
        </div>
    </div>
@endsection
