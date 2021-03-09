@extends('layouts.app')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">403 Error</li>
    </ol>

    <h1 class="display-1">403</h1>
    <p class="lead">You don't have permissions to access this page. You can
        <a href="javascript:history.back()">go back</a>
        to the previous page, or
        <a href="{{ route('dashboard') }}">return home</a>.
    </p>
@endsection
