@extends('layouts.front.app')

@section('content')
    <hr>
    <!-- Main content -->
    <section class="container content">
        <div class="row">
            <div class="col-md-12">@include('layouts.errors-and-messages')</div>
            <div class="col-md-5">
                <h2>Entrar</h2>
                <form action="{{ route('cart.login') }}" method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" value="" class="form-control" placeholder="xxxxx">
                    </div>
                    <div class="row">
                        <button class="btn btn-primary btn-block" type="submit">Entrar</button>
                    </div>
                </form>
                <div class="row"><hr>
                    <a href="#">Esqueci minha senha</a><br>
                </div>
            </div>
            <div class="col-md-6 col-md-offset-1">
                <h2>Criar sua conta</h2>
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="control-label">Nome</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">E-Mail</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Senha</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="control-label">Confirmar Senha</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
