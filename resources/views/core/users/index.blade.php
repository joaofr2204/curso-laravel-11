<x-app-layout>

    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            .dataTables_wrapper,
            .dataTables_wrapper input,
            .dataTables_wrapper select {
                font-size: .875rem;
                line-height: 1.25rem;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin: 0;
                /* Remove margens extras */
                display: inline-block;
                /* Mantém os elementos inline */
            }

            .dataTables_wrapper .dataTables_length select {
                padding: 4px 20px 4px 4px;
                background-position: right 0px center;
                margin-bottom: 1rem;
            }

            .dataTables_wrapper .dataTables_filter input {
                padding: 4px;
                margin-bottom: 1rem;

            }

            /* Remove a borda inferior das células da tabela */
            .dataTable tbody tr td{
                border-top: 0px !important;
                white-space: nowrap;
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

            /* Paginate - xs */
            .dataTables_wrapper .dataTables_paginate {
                padding-top: .755em;
                margin-top: 0px;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {}

            @media screen and (max-width: 767px) {
                .dataTables_wrapper .dataTables_paginate {}
            }

            @media screen and (max-width: 640px) {

                .dataTables_wrapper .dataTables_length {
                    margin-top: 0px;
                    float: left
                }

                .dataTables_wrapper .dataTables_filter {
                    margin-top: 0px;
                    float: right
                }
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

    <script type="text/javascript">

        $(document).ready(function () {
            var table = $('#crud-table').DataTable({

                pageLength: 100, // Define a quantidade de registros por página
                lengthMenu: [100, 200, 500], // Opções para o usuário selecionar o número de registros a exibir
                scrollY: 'calc(100vh - 360px)', // Define a altura para 65% da altura da tela
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
                        "previous": "<<",
                        "next": ">>"
                    },
                    lengthMenu: "Exibir_MENU_ <span class=\"hidden sm:inline-block\">registros por página</span>", // Traduz o texto "Show _MENU_ entries"
                    search: "Buscar:", // Traduz o campo Search
                    info: "Mostrando _START_ até _END_ de _TOTAL_ registros", // Traduz o texto "Showing ... to ... of ..."
                    infoEmpty: "Nenhum registro", // Quando não há registros
                    infoFiltered: "(filtrado de _MAX_ registros no total)" // Mensagem de filtro

                },
                initComplete: function (settings, json) {
                    // Ajustar a aparência da paginação
                    $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').addClass('flex justify-between items-center space-x-2 text-xs');
                    $(this).closest('.dataTables_wrapper').find('.paginate_button').addClass('bg-gray-300 text-gray-800 hover:bg-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none px-3 py-1 rounded');
                    $(this).closest('.dataTables_wrapper').find('.dataTables_info').addClass('hidden md:block text-sm');

                    // Caso queira evitar que quebre linha, forçando o layout em uma linha
                    // $('.dataTables_wrapper').addClass('flex flex-nowrap items-center justify-between');

                }

            });

            // Após atualizar a tabela via AJAX, ajusta as colunas
            $('#crud-table').on('init.dt', function () {
                table.columns.adjust().draw();
            });

        });
    </script>

</x-app-layout>