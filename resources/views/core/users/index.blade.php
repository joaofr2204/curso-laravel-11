<x-app-layout>

    <x-slot name="head">
        @vite('resources/js/core/crud-index.js')
    </x-slot>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <input id="crud-datatables-index-route" type="hidden" value="{{ route('users.index') }}" />

    <div class=" px-4 py-4">

        <x-alert />

        <div class="mb-4">
            <a href="{{ route('users.create') }}" title="Incluir"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        <div
            class="rounded-lg shadow-lg border border-gray-200 bg-white dark:bg-gray-600 w-full px-4 py-4 min-w-[400px]">
            <table id="crud-table" class="display cell-border compact text-sm" style="width:100%">
                <thead class="bg-gray-100 uppercase text-gray-700">
                    <tr>
                        <th class="border">Nome</th>
                        <th class="border">E-mail</th>
                        <th class="border">Ações</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>