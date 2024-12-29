<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <x-form action="{{ route('users.store') }}">
        @include('core.users.partials.form',['action' => 'store'])
    </x-form>    

</x-app-layout>