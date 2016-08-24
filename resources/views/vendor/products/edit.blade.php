@extends('products::layouts.product')

@section('content')
    @include('products::form', [
        'name' => 'Редактирование продукта',
        'action' => route('products.update', ['id' => $product->id]),
        'type' => 'edit',
        'productName' => $product->name,
        'productArt' => $product->art,
    ])
@endsection