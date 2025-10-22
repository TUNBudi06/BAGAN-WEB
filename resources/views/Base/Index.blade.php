@extends('indexCSS')

@section('title')
    <title>ISEKI | {{$name}}</title>
@endsection

@section('other-head')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/orgchart.js') }}"></script>
@endsection

@section('Body-HTML')
    {{-- Navbar --}}
    <nav class="w-full bg-white dark:bg-gray-800 shadow-md mb-4"
         x-data="{
            mobileMenuOpen: false,
            openList: false,
            init() {
                console.log('Alpine.js initialized!');
                this.$watch('openList', value => console.log('openList changed to:', value));
                this.$watch('mobileMenuOpen', value => console.log('mobileMenuOpen changed to:', value));
            }
         }">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo & Brand --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img src="{{ asset('favicon.svg') }}" alt="ISEKI Logo" class="h-8 w-8">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">ISEKI</span>
                    </a>
                </div>

                <div class="relative hidden md:block">
                    {{-- Bagan Dropdown Button --}}
                    <button
                        type="button"
                        @click="openList = !openList"
                        class="px-4 py-2 min-w-[200px] text-left flex items-center justify-between bg-white dark:bg-gray-700 border-2 border-blue-600 rounded-md text-gray-800 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                    >
                        <span>{{$name}}</span>
                        <svg
                            class="w-5 h-5 ml-2 transition-transform duration-200"
                            :class="{ 'rotate-180': openList }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Desktop Dropdown Menu --}}
                    <div
                        x-show="openList"
                        @click.away="openList = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute z-50 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5"
                        style="display: none;"
                    >
                        <div class="py-1" role="menu">
                            @foreach($bagans as $bagan)
                                <a
                                    href="{{ route('show-bagan', ['id' => $bagan['id']]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-600 hover:text-white transition {{ $currentId === $bagan['id'] ? 'bg-blue-100 dark:bg-blue-900 font-semibold' : '' }}"
                                    role="menuitem"
                                >
                                    {{ $bagan['name'] }}
                                    @if($currentId === $bagan['id'])
                                        <span class="float-right">✓</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Side: Auth & Menu Button --}}
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="hidden md:inline-block px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hidden md:inline-block px-4 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition">Register</a>
                        @endif
                    @else
                        <a href="{{route('admin')}}" class="hidden md:inline-block px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Admin Panel</a>
                        <form method="POST" action="{{ route('logout') }}" class="hidden md:inline-block">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">
                                Logout
                            </button>
                        </form>
                    @endguest
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 rounded-md md:hidden hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        aria-label="Toggle Menu"
                    >
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-transition class=" md:hidden border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 pt-2 pb-3 space-y-3">
                {{-- Mobile Bagan Dropdown --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Select Organization Chart</label>
                    <button
                        @click="openList = !openList"
                        class="w-full px-4 py-2 text-left flex items-center justify-between bg-white dark:bg-gray-700 border-2 border-blue-600 rounded-md text-gray-800 dark:text-white"
                    >
                        <span>{{$name}}</span>
                        <svg
                            class="w-5 h-5 transition-transform duration-200"
                            :class="{ 'rotate-180': openList }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Mobile Dropdown List --}}
                    <div
                        x-show="openList"
                        x-transition
                        class="mt-2 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 max-h-60 overflow-y-auto"
                        style="display: none;"
                    >
                        <div class="py-1">
                            @foreach($bagans as $bagan)
                                <a
                                    href="{{ route('show-bagan', ['id' => $bagan['id']]) }}"
                                    class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-600 hover:text-white transition {{ $currentId === $bagan['id'] ? 'bg-blue-100 dark:bg-blue-900 font-semibold' : '' }}"
                                >
                                    {{ $bagan['name'] }}
                                    @if($currentId === $bagan['id'])
                                        <span class="float-right">✓</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Mobile Auth Links --}}
                <div class="pt-2 border-t border-gray-200 dark:border-gray-700 space-y-2">
                    @guest
                        <a href="{{ route('login') }}" class="block w-full px-4 py-2 text-center rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block w-full px-4 py-2 text-center rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition">Register</a>
                        @endif
                    @else
                        <a href="{{route('admin')}}" class="block w-full px-4 py-2 text-center rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Admin Panel</a>
                        <form method="POST" action="{{ route('logout') }}" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 text-center rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- Header with Title --}}
    <header class="w-full mb-2 pt-2 relative">
        <h1 class="text-3xl font-bold text-center">{{$name}}</h1>
    </header>

    {{-- OrgChart Container --}}
    <div class="relative max-w-full max-h-screen h-screen w-screen">
        @livewire('bagan.chartviewtemplate', ['idData' => $currentId])
    </div>
@endsection
