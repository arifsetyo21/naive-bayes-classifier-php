@extends('layouts.app')

@section('title')
    Create Category | Scrap
@endsection

@section('content')
@if (session('status'))
    {{ session('status') }}
@endif

@endsection