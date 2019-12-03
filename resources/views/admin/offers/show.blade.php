@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($offer)
            <div class="box">
                <div class="box-body">
                    <h2>Categoria</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="col-md-4">Nome</td>
                            <!-- <td class="col-md-4">Descrição</td> -->
                            <td class="col-md-4">Imagem</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $offer->name }}</td>
                                <!-- <td>{!! $offer->description !!}</td> -->
                                <td>
                                    @if(isset($offer->cover))
                                        <img src="{{$offer->cover}}" alt="offer image" class="img-thumbnail">
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
              
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.offers.index') }}" class="btn btn-default btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
