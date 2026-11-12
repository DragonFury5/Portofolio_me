@extends('layouts.public')

@section('title', 'Staff only · Portside')
@section('robots', 'noindex, nofollow')

@section('content')
@include('partials.error-page', [
    'code' => 403,
    'heading' => 'This room is staff only.',
    'body' => 'Your account does not have access to moderation tools. If you think that is wrong, log in with the right account.',
])
@endsection
