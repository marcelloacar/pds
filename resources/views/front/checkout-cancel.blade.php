@extends('layouts.front.app')

@section('content')
    <div class="container product-in-cart-list">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <p class="alert alert-warning">Você cancelou seu pedido. Talvez você queira <a href="{{ route('home') }}">comprar outros itens?</a></p>
            </div>
        </div>
    </div>
@endsection