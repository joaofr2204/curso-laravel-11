<x-app-layout>

    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            .dataTables_wrapper .dataTables_length select {
                padding: 4px 20px 4px 4px;
                background-position: right 0px center;
                margin-bottom: 1rem;
            }

            .dataTables_wrapper .dataTables_filter input {
                padding: 4px;
                margin-bottom: 1rem;

            }

            /* CSS para garantir que os itens de pesquisa e paginação não quebrem linha */
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                flex-wrap: nowrap !important;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                margin-left: 10px;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class=" px-4 py-4">

        <x-alert />

        <div class="mb-4">
            <a href="{{ route('users.create') }}" title="Incluir"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        <div class="rounded-lg shadow-lg border border-gray-200 bg-white dark:bg-gray-600 w-full px-4 py-4">
            <table id="crud-table" class="display cell-border border compact" style="width:100%">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                    <tr>
                        <th class="border px-6 py-3">Nome</th>
                        <th class="border px-6 py-3">E-mail</th>
                        <th class="border px-6 py-3 text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function () {
            $('#crud-table').DataTable({
                pageLength: 100, // Define a quantidade de registros por página
                lengthMenu: [100, 200, 500], // Opções para o usuário selecionar o número de registros a exibir
                scrollY: 'calc(100vh - 370px)', // Define a altura para 65% da altura da tela
                scrollCollapse: true, // Permite que a tabela encolha quando houver menos dados

                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', email: 'name' },
                    { data: 'action', action: 'action' }
                ],
                order: [[0, "asc"]], // Ordena pelo primeiro campo

                pagingType: "simple_numbers",  // Use "simple" para uma paginação mais compacta
                language: {
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Próximo"
                    }
                },
                initComplete: function (settings, json) {
                    // Ajustar a aparência da paginação
                    $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').addClass('flex justify-between items-center space-x-2 text-xs mt-4');
                    $(this).closest('.dataTables_wrapper').find('.paginate_button').addClass('bg-gray-300 text-gray-800 hover:bg-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none px-3 py-1 rounded');
                    $(this).closest('.dataTables_wrapper').find('.dataTables_info').addClass('hidden md:flex');

    // Caso queira evitar que quebre linha, forçando o layout em uma linha
    // $('.dataTables_wrapper').addClass('flex flex-nowrap items-center justify-between');

                }

            });
        });
    </script>

</x-app-layout>