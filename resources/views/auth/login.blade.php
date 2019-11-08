@extends('layouts.front.app')

@section('content')
    <hr>
    <!-- Main content -->
    <section class="container content">
        <div class="col-md-12">@include('layouts.errors-and-messages')</div>
        <div class="col-md-4 col-md-offset-4">
            <h2>Entrar</h2>
            <!-- <form action="{{ route('login') }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" value="" class="form-control" placeholder="xxxxx">
                </div>
                <div class="row">
                    <button class="btn btn-default btn-block" type="submit">Entrar</button>
                </div>
            </form>
            <div class="row">
                <hr>
                <a href="{{route('password.request')}}">Esqueci minha senha</a><br>
                <a href="{{route('register')}}" class="text-center">Não possui conta? Crie a sua agora.</a>
            </div> -->
            <div class="col-md-8 offset-md-4">
                <a href="{{ url('/auth/redirect/facebook') }}" class="btn btn-primary"><i class="fa fa-facebook"></i> Facebook</a>
            </div>
        </div>
    </section>

    <!-- /.content -->
@endsection
