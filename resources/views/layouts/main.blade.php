<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PDF Convert Services')</title>
    @include('partials._styles')
    @stack('head')
</head>
<body>

@stack('body-before')

{{-- Toast notification --}}
<div id="toast" role="status" aria-live="polite">
    <svg viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd"
            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
            clip-rule="evenodd" />
    </svg>
    <span id="toast-msg"></span>
</div>

@include('partials._topbar')

@yield('content')

@include('partials._scripts')

</body>
</html>
