@extends('main.base_template')

@push('head')
    <style>
        .-m {
            max-width: 200px;
            max-height: 200px;
        }
    </style>
    <script src="/js/cart.js"><script>
@endpush

@section('content')
<form action="/order-add" method="POST">
    @csrf
    <table class="table">
        <tr>
            <th>Наименование товара</th>
            <th>Картинка товара</th>
            <th>Параметры товара</th>
            <th>Кол-во</th>
            <th>Стоимость</th>
        </tr>
        @forelse($ordersProduct as $id => $orderProd)
        <tr id="prod_{{$id}}">
            <td><a href="/products/{{$orderProd['product']->saf}}">{{$orderProd['product']->caption ?? ''}}</a></td>
            <td>@php($img = $orderProd['product']->firstImage())
                @if($img != '')<img class="-m" src="/imgs/{{$img}}">@endif
            </td>
            <td>
                @if($orderProd['params'])
                <input type="hidden" name="products[{{$id}}][params]" value="{{json_encode($orderProd['params'])}}">
                @endif
                @foreach($orderProd['product']->params() as $param)
                <b>{{$param->caption}}</b>: {!! $param->getValueSet($orderProd['params'][$param->id] ?? 0) !!}<br>
                @endforeach
            </td>
            <td style="text-align: right">
                <form action="/">
                <input type="button" class="btn btn-success" value="-" onclick="decProduct({{$id}})"
                >&nbsp<input id="count_prod_{{$id}}" type="number"
                    name="products[{{$id}}][count]"
                    value="{{$orderProd['count']}}"
                    maxlength="3"
                    style="width: 50px; text-align:right"
                    onchange="setProduct({{$id}}, this.value)"
                >&nbsp<input type="button" class="btn btn-success" value="+" onclick="incProduct({{$id}})">
            </td>
            <td style="text-align: right"><span id="price_prod_{{$id}}">
                {{$orderProd['price']}}
            </span> руб.</td>
        </tr>
        @empty
        <tr><td colspan="5">Карзина пуста</td></tr>
        @endforelse
        <tr>
            <td colspan="3"></td>
            <td>Итого:</td>
            <td style="text-align: right">
                <input type="hidden"  id="price_total_input" name="totalSum" value="{{$totalSum}}">
                <span id="price_total">{{$totalSum}}</span> руб.</td>
        </tr>
    </table>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                Укажите свою почту: <input required class="form-control" type="email" name="email" value="{{old('email', '')}}" placeholder="example@mail.ru">
            </div>
            <div class="col-md-6 col-sm-12">
                Укажите свой телефон: <input required class="form-control" type="tel" name="tel" value="{{old('tel', '')}}" placeholder="+7(987)654-32-10">
            </div>
            <div class="col-12">
                Укажите адресс доставки: <input class="form-control" name="address" value="{{old('address','')}}">
            </div>
            <div class="col-12">
                Укажите особенность заказа или дополнительные комментарии:
                <textarea class="form-control" name="other">{{old('other')}}</textarea><br><br>
            </div>
        </div>
        <input class="btn btn-success" type="submit" value="Заказать">
    </div>
</form>
@endsection
