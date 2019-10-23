@extends('layouts.front.app')

@section('content')
    <div class="container product-in-cart-list">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <p class="alert alert-success">Sua compra esta em processamento! <a href="{{ route('home') }}">Mostrar mais!</a></p>
            </div>
        </div>
    </div>
@endsection