@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="post"/>
    <meta property="og:title" content="{{ $post->name }}"/>
    <meta property="og:description" content="{{ strip_tags($post->description) }}"/>
    @if(!is_null($post->cover))
        <meta property="og:image" content="{{ $post->cover }}"/>
    @endif
@endsection

@section('content')
    <div class="container post">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Inicio</a></li>
                    <li class="active"><a href="{{ route('front.post.list') }}">Postagens</a></li>
                    @if(isset($post))
                    <li><a href="{{ route('front.post.slug', $post->slug) }}">{{ $post->name }}</a></li>
                    @endif
                </ol>
            </div>
        </div>
        @include('layouts.front.post')
    </div>
@endsection