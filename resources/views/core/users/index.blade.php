<x-app-layout>

    <x-slot name="head">

        @vite('resources/js/core/crud-index.js')

        <style>
            .dt-container select.dt-input {
                padding: 4px 20px 4px 4px !important;
                background-position: right 0px center;
                margin-bottom: 0.7rem;
            }

            .dt-container .dt-search .dt-input {
                line-height: 1.22em !important;
            }

            .dt-container .dt-length .dt-input {
                line-height: 1.36em !important;
            }

            .dt-button {
                padding: 8px 10px !important;
                margin-top: 0px !important;
                line-height: 0.8em !important;
            }

            /* Selected row color */
            table.display.dataTable>tbody>tr.selected>*,
            table.display.dataTable>tbody>tr.odd.selected>*,
            table.display.dataTable>tbody>tr.selected:hover>* {
                box-shadow: inset 0 0 0 9999px #acbad4 !important;
                color: #333 !important;
                background-color: #fff !important;
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

    <script type="module">
        $(document).ready(function () {
            var table = $('#crud-table').DataTable({
                dom: 'Bflrtip', // Adiciona os botões de exportação
                buttons: [
                    {
                        extend: 'excelHtml5', // Extensão para exportar para Excel
                        text: '<i class="fas fa-file-excel"></i>', // Texto do botão
                        title: 'Relatório de Tabela', // Título do arquivo Excel
                        className: 'dt-button text-sm px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600'
                    }
                ],
                pageLength: 100, // Define a quantidade de registros por página
                lengthMenu: [100, 200, 500], // Opções para o usuário selecionar o número de registros a exibir
                scrollY: 'calc(100vh - 360px)', // Define a altura para 65% da altura da tela
                scrollCollapse: true, // Permite que a tabela encolha quando houver menos dados
                select: true,
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
                        "previous": '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>',
                        "next": '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>'
                    },
                    lengthMenu: "Exibir _MENU_ <span class=\"hidden sm:inline-block\">registros por página</span><span class=\"inline-block sm:hidden\">/pág.</span>", // Traduz o texto "Show _MENU_ entries"
                    search: '', // Traduz o campo Search
                    searchPlaceholder: "Digite para buscar...", // Adiciona o placeholder
                    info: "Mostrando _START_ até _END_ de _TOTAL_ registros", // Traduz o texto "Showing ... to ... of ..."
                    infoEmpty: "Nenhum registro", // Quando não há registros
                    infoFiltered: "(filtrado de _MAX_ registros no total)" // Mensagem de filtro

                },
                initComplete: function (settings, json) {
                    // Ajustar a aparência da paginação
                    $(this).closest('.dt-container').find('.dt-paging').addClass('text-xs justify-self-end mt-2 mb-0');
                    // $(this).closest('.dt-container').find('.dt-paging-button').addClass('');
                    $(this).closest('.dt-container').find('.dt-info').addClass('hidden sm:inline-block text-sm justify-self-start float-left mt-4 mb-0');

                    $(this).closest('.dt-container').find('.dt-buttons').addClass('inline-block');
                    $(this).closest('.dt-container').find('.dt-search').addClass('inline-block');
                    $(this).closest('.dt-container').find('.dt-length').addClass('inline-block justify-self-right float-right');

                    // Caso queira evitar que quebre linha, forçando o layout em uma linha
                    // $('dt-container').addClass('flex flex-nowrap items-center justify-between');

                }

            });

            // Após atualizar a tabela via AJAX, ajusta as colunas
            $('#crud-table').on('init.dt', function () {
                table.columns.adjust().draw();
            });

        });

    </script>

</x-app-layout>