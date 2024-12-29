<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <x-form action="{{ route('users.update' , $user->id ) }}">
        @method('PUT')
        @include('core.users.partials.form',['action' => 'update'])
    </x-form>    

</x-app-layout>