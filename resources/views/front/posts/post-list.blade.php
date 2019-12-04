@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="post"/>
    <meta property="og:title" content="Listagem de Posts"/>
@endsection

@section('content')

     <div class="container">
        <div class="col-md-3">
            @include('front.categories.sidebar-category', ['categories_list' => $categories_list])
            @include('front.offers', ['offers' => $offers])
        </div>
        <div class="col-md-9">
            @if(!empty($posts) && !collect($posts)->isEmpty())
                <ul class="row text-center list-unstyled">
                @foreach($posts as $post)
                        <li class="col-md-4 col-sm-6 col-xs-12 post-list">
                            <div class="single-post">
                                <a class="btn btn-default" href="{{ route('front.post.slug', str_slug($post->slug)) }}"> 
                                    <div class="post">                                   
                                        @if(isset($post->cover))
                                            <img src="{{ $post->cover }}" alt="{{ $post->name }}" class="img-bordered img-responsive">
                                        @else
                                            <img src="https://placehold.it/263x330" alt="{{ $post->name }}" class="img-bordered img-responsive" />
                                        @endif
                                    </div>
                                    <div class="post-text">
                                        <h4>{{ $post->name }}</h4>
                                    </div>
                                </a>
                            </div>
                        </li>                
                @endforeach
                </ul>
                @if($posts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">{{ $posts->links() }}</div>
                        </div>
                    </div>
                @endif
            @else
                <p class="alert alert-warning">Nenhuma postagem ainda.</p>
            @endif
        </div>
    </div>

@endsection
