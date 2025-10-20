<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @hasSection('title')
        @yield('title')
    @else
        <title>ISEKI | Structure Organization</title>
    @endif

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @livewireStyles

    @hasSection('other-head')
        @yield('other-head')
    @endif
</head>
@hasSection('Body-HTML')
    <body class="relative bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1a1a1a] dark:text-[#f5f5f5] text-center  font-sans py-2 px-2">
    @yield('Body-HTML')
    <livewire:toastnotification />
    @stack('scripts')
    @stack('scripts-def')
    @livewireScripts
    </body>
@else
    @yield("HTML")
@endif
</html>
