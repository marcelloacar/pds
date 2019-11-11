@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.employees.store') }}" method="post" class="form">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Nome" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" placeholder="***" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">Atribuições </label>
                        <select name="role" id="role" class="form-control select2">
                            <option></option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('admin.shared.status-select', ['status' => 0])
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <div class="btn-group">
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-default">Voltar</a>
                            <button type="submit" class="btn btn-primary">Criar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
