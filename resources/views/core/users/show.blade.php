<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Show User') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 pt-4">

        <!-- Botão de Voltar -->
        <button type="button" onclick="window.location = '{{ route('users.index') }}'"
            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            Voltar
        </button>

        @can('is-admin')
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-500">
                    Excluir
                </button>
            </form>
        @endcan

    </div>
    <div class="container mx-auto px-4 pt-4">

        <ul class="bg-white dark:bg-gray-800 p-6 rounded shadow-md dark:text-white">
            <li><strong>Name:</strong> {{ $user->name }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
        </ul>

    </div>
</x-app-layout>