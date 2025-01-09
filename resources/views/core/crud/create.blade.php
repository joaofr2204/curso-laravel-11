<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create') }} {{ __('New') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 pt-4">

        <x-alert />

        <!-- Botão de Voltar -->
        <button type="button" onclick="window.location = '{{ route($model->getTable() . '.index') }}'"
            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-500 py-1 px-3 ">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </button>
    </div>

    <div class="container mx-auto p-4">
        @include('core.crud.form',['action' => 'store', 'model' => $model])
    </div>
</x-app-layout>