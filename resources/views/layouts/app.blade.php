<x-html>
    <x-slot name="head">

        <!-- Scripts -->
        @vite(['resources/css/core/app.css', 'resources/js/core/app.js'])
        
        {{ $head ?? '' }}

    </x-slot>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

</x-html>