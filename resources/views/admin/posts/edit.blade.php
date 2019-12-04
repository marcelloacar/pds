@extends('layouts.admin.app')
@section('content')
<!-- Main content -->
<section class="content">
    @include('layouts.errors-and-messages')
    <div class="box">
        <form action="{{ route('admin.posts.update', $post->id) }}" method="post" class="form" enctype="multipart/form-data">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <input type="hidden" name="_method" value="put">
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label for="name">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Nome" class="form-control" value="{!! $post->name ?: old('name')  !!}">
                        </div>

                        @if(isset($post->cover))
                        <div class="form-group">
                            <img src="{{ $post->cover }}" alt="" class="img-responsive"> <br/>
                            <a onclick="return confirm('Tem certeza?')" href="{{ route('admin.post.remove.image', ['post' => $post->id]) }}" class="btn btn-danger">Remover imagem?</a>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="cover">Imagem </label>
                            <input type="file" name="cover" id="cover" class="form-control">
                        </div>
                                                
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Descrição">{!! $post->description ?: old('description')  !!}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status </label>
                            <select name="status" id="status" class="form-control">
                                <option value="0" @if($post->status == 0) selected="selected" @endif>Inativo</option>
                                <option value="1" @if($post->status == 1) selected="selected" @endif>Ativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="fb-share-button" data-href="{{ route('front.post.slug', str_slug($post->slug)) }}" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.post.slug', str_slug($post->slug)) }}" class="fb-xfbml-parse-ignore">Compartilhar</a>
                    </div>
                    <div class="block" style="margin-top: 10px;">
                        <a class="twitter-share-button"
                            href="https://twitter.com/intent/tweet?url={{ route('front.post.slug', str_slug($post->slug)) }}">
                        Tweet</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="btn-group">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Voltar</a>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </div>
    </form>
</div>
<!-- /.box -->
</section>
<!-- /.content -->
@endsection

@section('js')
    <script>window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);

      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };

      return t;
    }(document, "script", "twitter-wjs"));
    </script>
@endsection
