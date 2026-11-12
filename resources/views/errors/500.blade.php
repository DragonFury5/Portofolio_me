@extends('layouts.public')

@section('title', 'Something went wrong · Portside')
@section('robots', 'noindex, nofollow')

@section('content')
@include('partials.error-page', [
    'code' => 500,
    'heading' => 'The harbour is having a moment.',
    'body' => 'Something failed on our side. The error has been logged. Try again, or head back to the work.',
])
@endsection
