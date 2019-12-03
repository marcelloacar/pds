@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h2>
                            <a href="{{ route('admin.customers.show', $customer->id) }}">{{$customer->name}}</a> <br />
                            <small>{{$customer->email}}</small> <br />
                            <small>referência: <strong>{{$order->reference}}</strong></small>
                        </h2>
                    </div>
                    <div class="col-md-3 col-md-offset-3">
                        <!-- <h2><a href="{{route('admin.orders.invoice.generate', $order['id'])}}" class="btn btn-primary btn-block">Baixar pdf</a></h2> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <h4> <i class="fa fa-shopping-bag"></i> Informações do pedido</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <td class="col-md-3">Data</td>
                            <td class="col-md-3">Cliente</td>
                            <td class="col-md-3">Pagamento</td>
                            <td class="col-md-3">Status</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ date('M d, Y h:i a', strtotime($order['created_at'])) }}</td>
                        <td><a href="{{ route('admin.customers.show', $customer->id) }}">{{ $customer->name }}</a></td>
                        <td><strong>{{ $order['payment'] }}</strong></td>
                        <td><button type="button" class="btn btn-info btn-block">{{ $currentStatus->label }}</button></td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Subtotal</td>
                        <td class="bg-warning">{{ $order['total_products'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Taxas</td>
                        <td class="bg-warning">{{ $order['tax'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-warning">Desconto</td>
                        <td class="bg-warning">{{ $order['discounts'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="bg-success text-bold">Total do pedido</td>
                        <td class="bg-success text-bold">{{ $order['total'] }}</td>
                    </tr>
                    @if($order['total_paid'] != $order['total'])
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="bg-danger text-bold">Total pago</td>
                            <td class="bg-danger text-bold">{{ $order['total_paid'] }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        @if($order)
            @if($order->total != $order->total_paid)
                <p class="alert alert-danger">
                    Ooops, há uma diferença entre o valor total do pedido e o total pago. <br />
                    Total order amount: <strong>{{ config('cart.currency') }} {{ $order->total }}</strong> <br>
                    Total amount paid <strong>{{ config('cart.currency') }} {{ $order->total_paid }}</strong>
                </p>

            @endif
            <div class="box">
                @if(!$items->isEmpty())
                    <div class="box-body">
                        <h4> <i class="fa fa-gift"></i> Items</h4>
                        <table class="table">
                            <thead>
                            <!-- <th class="col-md-2">SKU</th> -->
                            <th class="col-md-2">Nome</th>
                            <th class="col-md-2">Descrição</th>
                            <th class="col-md-2">Quantidade</th>
                            <th class="col-md-2">Preço</th>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <!-- <td>{{ $item->sku }}</td> -->
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        {!! $item->description !!}
                                        @php($pattr = \App\Shop\ProductAttributes\ProductAttribute::find($item->product_attribute_id))
                                        @if(!is_null($pattr))<br>
                                            @foreach($pattr->attributesValues as $it)
                                                <p class="label label-primary">{{ $it->attribute->name }} : {{ $it->value }}</p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $item->pivot->quantity }}</td>
                                    <td>{{ $item->price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4> <i class="fa fa-map-marker"></i> Endereço</h4>
                            <table class="table">
                                <thead>
                                    <th>Endereço 1</th>
                                    <th>endereço 2</th>
                                    <th>Cidade</th>
                                    <th>Estado</th>
                                    <th>Cep</th>
                                    <th>País</th>
                                    <th>Telefone</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $order->address->address_1 }}</td>
                                    <td>{{ $order->address->address_2 }}</td>
                                    <td>{{ $order->address->city }}</td>
                                    <td>
                                        @if(isset($order->address->province))
                                            {{ $order->address->province->name }}
                                        @endif
                                    </td>
                                    <td>{{ $order->address->zip }}</td>
                                    <td>{{ $order->address->country->name }}</td>
                                    <td>{{ $order->address->phone }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
            <div class="box-footer">
                <div class="btn-group">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-default">Voltar</a>
                    @if($user->hasPermission('update-order'))<a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">Editar</a>@endif
                </div>
            </div>
        @endif

    </section>
    <!-- /.content -->
@endsection