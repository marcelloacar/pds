@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($offers)
            <div class="box">
                <div class="box-body">
                    <h2>Anúncios</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-3">Nome</td>
<!--                                 <td class="col-md-3">Imagem</td>
 -->                            <td class="col-md-3">Status</td>
                                <td class="col-md-3">Ações</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($offers as $offer)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.offers.show', $offer->id) }}">{{ $offer->name }}</a></td>
                                <td>
                                    @if(isset($offer->cover))
                                        <img src="{{ $offer->cover }}" alt="" class="img-responsive">
                                    @endif
                                </td>
                                <td>@include('layouts.status', ['status' => $offer->status])</td>
                                <td>
                                    <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                            <button onclick="return confirm('Tem certeza?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Apagar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $offers->links() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
