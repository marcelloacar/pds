<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Compra</title>
    <link rel="stylesheet" href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css')}}">
    <style type="text/css">
        table { border-collapse: collapse;}
    </style>
</head>
<body>
<section class="container">
    <div class="col-md-12">
        <h2>Olá {{config('app.name')}}! <br />Uma nova compra foi realizada! </h2>
        <p>Abaixo esta mais detalhes sobre a compra realizada: </p>
        <table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th class="col-md-2">SKU</th>
                <th class="col-md-2">Nome</th>
                <th class="col-md-3">Descrição</th>
                <th class="col-md-1">Quantidade</th>
                <th class="col-md-4 text-right">Preço</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{$product->sku}}</td>
                    <td>{{$product->name}}</td>
                    <td>
                        {{$product->description}}
                        @php($pattr = \App\Shop\ProductAttributes\ProductAttribute::find($product->pivot->product_attribute_id))
                        @if(!is_null($pattr))<br>
                        @foreach($pattr->attributesValues as $it)
                            <p class="label label-primary">{{ $it->attribute->name }} : {{ $it->value }}</p>
                        @endforeach
                        @endif
                    </td>
                    <td>{{$product->pivot->quantity}}</td>
                    <td class="text-right">{{config('cart.currency')}} {{number_format($product->price * $product->pivot->quantity, 2)}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Subtotal:</td>
                <td class="text-right">{{config('cart.currency')}} {{number_format($order->total_products, 2)}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Entrega:</td>
                <td class="text-right">{{config('cart.currency')}} {{number_format($order->total_shipping, 2)}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Descontos:</td>
                <td class="text-right">({{config('cart.currency')}} {{number_format($order->discounts, 2)}})</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Taxas:</td>
                <td class="text-right">{{config('cart.currency')}} {{number_format($order->tax, 2)}}</td>
            </tr>
            <tr class="bg-warning">
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>{{config('cart.currency')}} {{number_format($order->total, 2)}}</strong></td>
            </tr>
            </tfoot>
        </table>
    </div>
</section>
</body>
</html>