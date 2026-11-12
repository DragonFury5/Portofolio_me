@extends('layouts.public')

@section('title', 'Slow down · Portside')
@section('robots', 'noindex, nofollow')

@section('content')
@include('partials.error-page', [
    'code' => 429,
    'heading' => 'A little too fast.',
    'body' => 'Too many requests from this connection. Give it a minute, then try again.',
])
@endsection
