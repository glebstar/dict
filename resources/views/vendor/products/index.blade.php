@extends('products::layouts.product')

@section('content')
    <table class="table table-striped">
        <tr>
            <th>Наименование</th>
            <th>Артикул</th>
            <th>Действия</th>
        </tr>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->art }}</td>
                <td>
                    <a href="{{ route('products.edit', ['id' => $product->id]) }}"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;&nbsp;
                    @if ('admin' == app('current_user_type'))
                    <form style="display: inline" method="post" action="{{ route('products.destroy', ['id' => $product->id]) }}" id="delete-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="delete">
                        <i class="glyphicon glyphicon-trash" style="cursor: pointer" onclick="if(confirm('Delete this product?')){ return $('#delete-form').submit() } return false;"></i>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection