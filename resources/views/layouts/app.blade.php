{{--
    Layout utama JARA (Bootstrap 5 CDN).
    Bisa dipakai dua cara:
      1) <x-app-layout> <x-slot name="header">...</x-slot> ... </x-app-layout>
      2) @extends('layouts.app') + @section('header') / @section('content')
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'JARA') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    @include('layouts.navigation')

    @if (isset($header) || View::hasSection('header'))
        <header class="bg-white border-bottom">
            <div class="container py-3">
                {{ $header ?? '' }}
                @yield('header')
            </div>
        </header>
    @endif

    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
