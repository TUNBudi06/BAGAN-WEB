@extends('indexCSS')

@section('title')
    <title>ISEKI | Dashboard </title>
@endsection

@section('Body-HTML')

    <div x-data="{ sidebarOpen: false, ui: { openMenu: null } }" class="min-h-screen flex bg-gray-100 dark:bg-gray-900">
        <!-- Mobile backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed z-30 inset-y-0 left-0 w-64 transform bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto transition-transform duration-200 ease-in-out">
            <div class="p-4 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-800 dark:text-white">Admin Panel</a>
                <button class="lg:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" @click="sidebarOpen = false" aria-label="Close sidebar">
                    <svg class="h-5 w-5 text-gray-700 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <nav class="px-2 py-3 space-y-1">
                <a href="{{route('admin')}}" class="block px-3 py-2 data-[nav=true]:bg-gray-200 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" data-nav="{{request()->routeIs('admin') ? 'true' : 'false'}}">Users</a>
                <a href="{{route('admin')}}" class="block px-3 py-2 data-[nav=true]:bg-gray-200 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" data-nav="false">Bagan List</a>
                <a href="#" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
            </nav>

            <div class="mt-6 p-4 border-t border-gray-100 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col lg:pl-64">
            <header class="w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button class="lg:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" @click="sidebarOpen = true" aria-label="Open sidebar">
                            <svg class="h-6 w-6 text-gray-700 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="ps-2 text-md font-semibold text-gray-900 dark:text-white">User List</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Example right-side buttons -->
                        <a href="{{route('base')}}" class="px-3 py-2 rounded-md text-sm bg-gray-50 dark:bg-gray-700">Home</a>
                    </div>
                </div>
            </header>

            <main class="p-4 justify-items-start w-full">
                <livewire:user.userlist />
                <livewire:user.edituserlistbagan />
                <livewire:user.tableuserlistbagan />
            </main>
        </div>
    </div>
@endsection
