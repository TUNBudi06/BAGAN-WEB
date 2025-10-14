@extends('indexCSS')

@section('title')
    <title>ISEKI | {{$name}}</title>
@endsection

@section('other-head')
    <script src="{{ asset('js/jquery-3.7.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/orgchart.js') }}"></script>
@endsection

@section('Body-HTML')
    {{-- Navbar --}}
    <nav class="w-full bg-white dark:bg-gray-800 shadow-md mb-4" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo & Brand --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img src="{{ asset('favicon.svg') }}" alt="ISEKI Logo" class="h-8 w-8">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">ISEKI</span>
                    </a>
                </div>

                <div>
                    <button @click="openList = ! openList" class="px-3 py-2 m-1 min-w-50 text-right justify-between bg-transparent outline-2 outline-blue-600 hidden md:inline-flex">
                        {{$name}}
                        <span>
                            <i data-lucide="chevron-up" class="rotate-90 ml-1 data-[open=true]:rotate-180" data-open=""></i>
                        </span>
                    </button>
                </div>

                {{-- Right Side: Search & Menu Button --}}
                <div class="flex items-center gap-4" x-data="{ openList: false }">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition">Register</a>
                        @endif
                    @else
                        <a href="{{route('admin')}}" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Admin Panel</a>
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

        {{-- Mobile Menu (hidden by default) --}}
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200 dark:border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1">

            </div>
        </div>
    </nav>

    {{-- Header with Title --}}
    <header class="w-full mb-2 pt-2 relative">
        <h1 class="text-3xl font-bold text-center">{{$name}}</h1>
    </header>

    {{-- OrgChart Container --}}
    <div class="relative max-w-full max-h-screen h-screen w-screen">
        <div id="BaganJS" class="w-full h-full"></div>
    </div>

    <script>
        let chart;
        var bagan = document.getElementById("BaganJS");

        $(document).ready(function() {
            // Initialize OrgChart
            chart = new OrgChart(bagan, {
                template: "rony",
                mouseScrool: OrgChart.action.pan,
                scaleInitial: OrgChart.match.boundary,
                enableSearch: true,
                tags: {
                    "Management": {
                        template: "rony"
                    },
                    "Marketing Manager": {
                        template: "polina"
                    },
                    "IT Manager": {
                        template: "ana"
                    },
                    "IT": {
                        template: "ula"
                    },
                    "Marketing": {
                        template: "belinda"
                    }
                },
                nodeBinding: {
                    field_0: "name",
                    field_1: "title",
                    img_0: "img"
                },
                nodes: [
                    { id: 1, tags: ["Management"], name: "Amber McKenzie", title: "CEO", img: "https://cdn.balkan.app/shared/1.jpg" },
                    { id: 2, pid: 1, tags: ["IT Manager"], name: "Ava Field", title: "IT Manager", img: "https://cdn.balkan.app/shared/2.jpg" },
                    { id: 3, pid: 1, tags: ["Marketing Manager"], name: "Rhys Harper", title: "Marketing Team Lead", img: "https://cdn.balkan.app/shared/3.jpg" },
                    { id: 4, pid: 2, tags: ["IT"], name: "Carol Foster", title: "Junior Developer", img: "https://cdn.balkan.app/shared/4.jpg" },
                    { id: 5, pid: 2, tags: ["IT"], name: "Blake Morris", title: "Senior Developer", img: "https://cdn.balkan.app/shared/5.jpg" },
                    { id: 6, pid: 3, tags: ["Marketing"], name: "Erin Grant", title: "Junior Marketing", img: "https://cdn.balkan.app/shared/6.jpg" },
                    { id: 7, pid: 3, tags: ["Marketing"], name: "Avery Hughes", title: "Senior Marketing", img: "https://cdn.balkan.app/shared/7.jpg" }
                ]
            });

            $(window).on('resize', function() {
                chart.fit();
            });
        });
    </script>
@endsection
