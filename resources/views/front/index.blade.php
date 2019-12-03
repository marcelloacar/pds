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
                <div class="category-top col-md-12">
                    <h2>{{ $category->name }}</h2>
                    {!! $category->description !!}
                </div>
                <!-- <div class="category-image">
                    @if(isset($category->cover))
                        <img src="{{ $category->cover }}" alt="{{ $category->name }}" class="img-responsive" />
                    @else
                        <img src="https://placehold.it/1200x200" alt="{{ $category->cover }}" class="img-responsive" />
                    @endif
                </div> -->
            </div>
            <hr>
            <div class="row">
                @include('front.products.product-list', ['products' => $category->products->where('status', 1)])
            </div>
        </div>
    </div>
@endsection
