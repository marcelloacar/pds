@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-body">
                <h2>Cliente</h2>
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="col-md-4">ID</td>
                        <td class="col-md-4">Nome</td>
                        <td class="col-md-4">Email</td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->nome }}</td>
                        <td>{{ $customer->email }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-body">
                <h2>Addresses</h2>
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="col-md-2">Apelido</td>
                        <td class="col-md-2">Endereço 1</td>
                        <td class="col-md-2">país</td>
                        <td class="col-md-2">Status</td>
                        <td class="col-md-4">Ações</td>
                    </tr>
                    </tbody>
                    <tbody>
                    @foreach ($addresses as $address)
                        <tr>
                            <td>{{ $address->alias }}</td>
                            <td>{{ $address->address_1 }}</td>
                            <td>{{ $address->country->name }}</td>
                            <td>@include('layouts.status', ['status' => $address->status])</td>
                            <td>
                                <form action="{{ route('admin.addresses.destroy', $address->id) }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.customers.addresses.show', [$customer->id, $address->id]) }}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Exibir</a>
                                        <!-- <a href="{{ route('admin.customers.addresses.edit', [$customer->id, $address->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Editar</a> -->
                                        <button onclick="return confirm('Tem certeza?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Apagar</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-default btn-sm">Voltar</a>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
