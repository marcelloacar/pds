@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.posts.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Nome" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="cover">Imagem </label>
                        <input type="file" name="cover" id="cover" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição </label>
                        <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Descrição">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">Inativo</option>
                            <option value="1">Ativo</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Voltar</a>
                        <button type="submit" class="btn btn-primary">Criar</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
