@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.offers.update', $offer->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <input type="hidden" name="_method" value="put">
                    {{ csrf_field() }}
  
                    <div class="form-group">
                        <label for="name">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Nome" class="form-control" value="{!! $offer->name ?: old('name')  !!}">
                    </div>
                    <!--
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Descrição">{!! $offer->description ?: old('description')  !!}</textarea>
                        </div>
                    -->
                    @if(isset($offer->cover))
                    <div class="form-group">
                        <img src="{{ $offer->cover }}" alt="" class="img-responsive"> <br/>
                        <a onclick="return confirm('Tem certeza?')" href="{{ route('admin.offer.remove.image', ['offer' => $offer->id]) }}" class="btn btn-danger">Remover imagem?</a>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="cover">Imagem </label>
                        <input type="file" name="cover" id="cover" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            <option value="0" @if($offer->status == 0) selected="selected" @endif>Inativo</option>
                            <option value="1" @if($offer->status == 1) selected="selected" @endif>Ativo</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.offers.index') }}" class="btn btn-default">Voltar</a>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
