@extends('layout.app')

@section('content-header')
    Account Settings
@endsection

@section('content')
    @include('settings.partials.user-details-password-update');

    @include('settings.partials.export-transaction');

    @include('settings.partials.purge-transaction');
@endsection
