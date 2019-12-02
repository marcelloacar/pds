@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($product)
            <div class="box">
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="col-md-2">Nome</td>
                            <td class="col-md-3">Descrição</td>
                            <!-- <td class="col-md-3">Imagem principal</td> -->
                            <td class="col-md-2">Quantidade</td>
                            <td class="col-md-2">Preço</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{!! $product->description !!}</td>
                               <!--  <td>
                                    @if(isset($product->cover))
                                        <img src="{{ asset("storage/$product->cover") }}" class="img-responsive" alt="">
                                    @endif
                                </td> -->
                                <td>{{ $product->quantity }}</td>
                                <td>{{ config('cart.currency') }} {{ $product->price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
