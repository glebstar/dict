@extends('products::layouts.product')

@section('content')
    @include('products::form', [
        'name' => 'Добавление продукта',
        'action' => route('products.store'),
        'type' => 'create',
        'productName' => old('name'),
        'productArt' => old('art'),
    ])
@endsection