@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($post)
            <div class="box">
                <div class="box-body">
                    <h2>Post</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="col-md-4">Nome</td>
                            <td class="col-md-4">Descrição</td>
                            <td class="col-md-4">Imagem</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $post->name }}</td>
                                <td>{!! $post->description !!}</td>
                                <td>
                                    @if(isset($post->cover))
                                        <img src="{{$post->cover}}" alt="post image" class="img-thumbnail">
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
              
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-default btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection
