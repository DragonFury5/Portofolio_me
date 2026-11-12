@extends('layouts.public')

@section('title', 'Page not found · Portside')
@section('robots', 'noindex, nofollow')

@section('content')
@include('partials.error-page', [
    'code' => 404,
    'heading' => 'That page slipped the mooring.',
    'body' => 'The link is broken or the page has moved. Try the work, the templates, or head home.',
])
@endsection
