@extends('layouts.main')

@section('add_title'){{$page->title}}@endsection
@section('description'){{$page->description}}@endsection
@section('keywords'){{$page->keywords}}@endsection

@section('page_title_ico')
    ico-book
@endsection

@section('page_title')
    {{$page->title}}
@endsection

@section('content')
    <div class="container">
        @can('editor')
        <div>
            <a class="btn btn-info" href="{{ route('cms') }}/edit/{{ $page->id }}">Edit</a>
        </div>
        @endcan
        @yield('cmspagebody')
    </div>
@endsection