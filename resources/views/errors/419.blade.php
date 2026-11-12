@extends('layouts.public')

@section('title', 'Form expired · Portside')
@section('robots', 'noindex, nofollow')

@section('content')
@include('partials.error-page', [
    'code' => 419,
    'heading' => 'That form expired.',
    'body' => 'Your session changed before the form was sent. Go back, reload the page and try again.',
])
@endsection
