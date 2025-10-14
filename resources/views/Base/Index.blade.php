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
    <nav class="w-full bg-white dark:bg-gray-800 shadow-md mb-4">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo & Brand --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img src="{{ asset('favicon.svg') }}" alt="ISEKI Logo" class="h-8 w-8">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">ISEKI</span>
                    </a>
                </div>

                {{-- Center Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        Home
                    </a>
                    <a href="{{ url('/departments') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        Departments
                    </a>
                    <a href="{{ url('/employees') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        Employees
                    </a>
                </div>

                {{-- Right Side: Search & Menu Button --}}
                <div class="flex items-center gap-4">
                    {{-- Search Bar --}}
                    <div class="hidden sm:block">
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search..."
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>

                    {{-- Hamburger Menu Button --}}
                    <button
                        id="menuToggle"
                        class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition"
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
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 dark:border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Home
                </a>
                <a href="{{ url('/departments') }}" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Departments
                </a>
                <a href="{{ url('/employees') }}" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Employees
                </a>
                <div class="px-3 py-2">
                    <input
                        type="text"
                        placeholder="Search..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                </div>
            </div>
        </div>
    </nav>

    {{-- Header with Title --}}
    <header class="w-full mb-2 pt-2 relative">
        <h1 class="text-3xl font-bold text-center">{{$name}}</h1>
        <i data-lucide="tally-3" class="absolute right-0 top-0 size-10 mt-3 rotate-90 data-[open=true]:rotate-0" data-open="false"></i>
    </header>

    {{-- OrgChart Container --}}
    <div class="relative max-w-full max-h-screen h-screen w-screen">
        <div id="BaganJS" class="w-full h-full"></div>
    </div>

    <script>
        let chart;
        var bagan = document.getElementById("BaganJS");

        $(document).ready(function() {
            // Toggle mobile menu
            $('#menuToggle').click(function() {
                $('#mobileMenu').toggleClass('hidden');
            });

            // Search functionality
            $('#searchInput').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                if (chart && searchTerm) {
                    chart.searchUI.find(searchTerm);
                }
            });

            // Initialize OrgChart
            chart = new OrgChart(bagan, {
                template: "rony",
                mouseScrool: OrgChart.action.none,
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
