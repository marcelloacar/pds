<div class="row">
    <div class="col-md-6">       
        <figure class="text-center post-cover-wrap col-md-12">
            @if(isset($post->cover))
                <img id="main-image" class="post-cover img-responsive"
                     src="{{ $post->cover }}?w=400"
                     data-zoom="{{ $post->cover }}?w=1200">
            @else
                <img id="main-image" class="post-cover" src="https://placehold.it/300x300"
                     data-zoom="{{ $post->cover }}?w=1200" alt="{{ $post->name }}">
            @endif
        </figure>
    </div>
    <div class="col-md-6">
        <div class="post-description">
            <h1>{{ $post->name }}</h1>
            <div class="description">{!! $post->description !!}</div>
            <div class="excerpt">
                <hr>{!!  $post->description !!}</div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.errors-and-messages')
                    {{ csrf_field() }}
                    @if(isset($postAttributes) && !$postAttributes->isEmpty())
                        <div class="form-group">
                            <label for="postAttribute">Escolha a combinação</label> <br />
                            <select name="postAttribute" id="postAttribute" class="form-control select2">
                                @foreach($postAttributes as $postAttribute)
                                    <option value="{{ $postAttribute->id }}">
                                        @foreach($postAttribute->attributesValues as $value)
                                            {{ $value->attribute->name }} : {{ ucwords($value->value) }}
                                        @endforeach
                                        @if(!is_null($postAttribute->price))
                                            ( {{ config('cart.currency_symbol') }} {{ $postAttribute->price }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div><hr>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            var postPane = document.querySelector('.post-cover');
            var paneContainer = document.querySelector('.post-cover-wrap');

            new Drift(postPane, {
                paneContainer: paneContainer,
                inlinePane: false
            });
        });
    </script>
@endsection