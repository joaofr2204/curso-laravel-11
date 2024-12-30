<div class="container mx-auto px-4 pt-4">

    <x-alert />

    <!-- Botão de Voltar -->
    <button type="button" onclick="window.location = '{{ route($model . '.index') }}'"
        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
        Voltar
    </button>

</div>

<div class="container mx-auto p-4">
    <form action="{{ route("$model.$action", $action == 'store' ? null : $data['id']) }}" method="POST"
        class="bg-white dark:bg-gray-800 p-6 rounded shadow-md">

        @csrf

        @if($action == 'update')
            @method('PUT')
        @endif

        @include("core.$model.partials.form", ['action' => $action, 'data' => $data ?? []])

        <button type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Enviar
        </button>

    </form>
</div>