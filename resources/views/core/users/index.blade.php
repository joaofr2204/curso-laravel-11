<x-app-layout>

    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <style>
            .dataTables_wrapper .dataTables_length select {
                padding: 4px 20px 4px 4px ;
                background-position: right 0px center ;
                margin-bottom:1rem;
            }
            .dataTables_wrapper .dataTables_filter input{
                padding: 4px;
                margin-bottom:1rem;

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
            <a href="{{ route('users.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Novo
            </a>
        </div>

        <div class="overflow-hidden rounded-lg shadow-lg border border-gray-200 bg-white w-full px-4 py-4">
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
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', email: 'name' },
                    { data: 'action', action: 'action' }
                ],
                order: [[0, "asc"]], // Ordena pelo primeiro campo
                
            });
        });
    </script>

</x-app-layout>