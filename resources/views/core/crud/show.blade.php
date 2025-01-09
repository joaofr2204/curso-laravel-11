<x-app-layout>

    <x-slot name="head">
        @vite('resources/js/core/crud-index.js')
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Showing') }} ID: {{ $model->id }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 pt-4">

        <!-- Botão de Voltar -->
        <button type="button" onclick="window.location = '{{ route('users.index') }}'"
            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-500 py-1 px-3 ">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </button>

        @can('is-admin')

            <button type="button" data-id="{{ $model->id }}"
                class="btn-delete bg-red-500 hover:bg-red-400 text-white font-semibold py-1 px-3 rounded-sm shado-md focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2 transition">
                <i class="fas fa-trash"></i>
                Excluir
            </button>

        @endcan

    </div>
    <div class="container mx-auto px-4 pt-4">

        <ul class="bg-white dark:bg-gray-800 p-6 rounded shadow-md dark:text-white">
            @foreach($model->getAttributes() as $key => $value)
                <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>

    </div>

    <script type="module">
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();

            let itemId = $(this).data('id');
            let url = '{{ route('users.destroy', ':id') }}'.replace(':id', itemId);

            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Token CSRF obrigatório no Laravel
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            Swal.fire(
                                'Excluído!',
                                response.success,
                                'success'
                            ).then((result) => {
                                window.location = '{{ route('users.index') }}';
                            });
                        },
                        error: function (xhr) {
                            debugger;

                            Swal.fire(
                                'Erro!',
                                'Não foi possível excluir o item.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>

</x-app-layout>