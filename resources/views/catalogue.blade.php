@extends('layouts.app')

@section('title', 'Product Catalogue')
@section('page-title', 'Product Catalogue')

@section('content')
    <div id="catalogue-app">
        <catalogue-component 
            :initial-products='@json($products)'
        ></catalogue-component>
    </div>
@endsection