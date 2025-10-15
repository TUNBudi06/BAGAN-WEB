@extends('indexCSS')

@section('title')
    <title>ISEKI | Login</title>
@endsection

@section('HTML')
<body class="w-full h-screen justify-content-center items-center flex bg-gradient-to-br from-blue-400 to-pink-500">
    <livewire:toastnotification />
    <main class="w-full xl:px-100 lg:px-60 md:px-40 sm:px-20 px-10 flex justify-center items-center">
        <div class="bg-white pt-3 px-5 w-full h-120 rounded-3xl drop-shadow-2xl">
            <div class="justify-between flex">
                <div>
                    <h2 class="text-3xl font-sans">Login page</h2>
                    <p class="text-md text-gray-500">Please enter your credentials to login.</p>
                </div>
                <div>
                    <a href="{{ url('/') }}" class="flex items-center gap-1 text-xl pt-4 float-right">
                        <span class="text-md font-medium text-gray-500 hover:text-gray-700">
                            Back to Home
                        </span>
                        <i data-lucide="chevron-right" class="h-6 w-6 text-gray-500 hover:text-gray-700"></i>
                    </a>
                </div>
            </div>
            <hr class="mb-5 mt-2">
            <livewire:loginform />
        </div>
    </main>
    @stack('scripts')
@livewireScripts
</body>
@endsection
