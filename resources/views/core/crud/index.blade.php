<x-app-layout>

    <x-slot name="head">
        @vite('resources/js/core/crud-index.js')
        @routes
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __(ucfirst($model->getTable())) }}
        </h2>
    </x-slot>

    <input id="crud-datatables-index-route" type="hidden" value="{{ route("{$model->getTable()}.index") }}" />

    <x-alert />

    {{-- BOTOES CRUD --}}

    <div class="my-2 mx-3">
        <button onclick="window.location = '{{ route("{$model->getTable()}.create") }}'" title="Incluir"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded-sm shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition"
            accesskey="I">
            <i class="fas fa-plus"></i>
        </button>
        <button onclick="crudForm('{{$model->getTable()}}','show')" title="Visualizar" id="crud-show-btn"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-sm shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition"
            accesskey="V">
            <i class="fas fa-eye"></i>
        </button>
        <button onclick="crudForm('{{$model->getTable()}}','edit')" title="Editar" id="crud-edit-btn"
            class="bg-yellow-500 hover:bg-yellow-400 text-white font-semibold py-1 px-3 rounded-sm shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2 transition"
            accesskey="A">
            <i class="fas fa-edit"></i>
        </button>
    </div>

    {{-- DATATABLES --}}

    <input type="hidden" id="crud-datatables-columns" value="{{ json_encode($cols) }}" />

    <div class="border border-gray-200 bg-white dark:bg-gray-600 w-full px-3 py-3 min-w-[400px]">
        <table id="crud-table" class="display cell-border compact text-sm" style="width:100%">
            <thead class="bg-gray-100 uppercase text-gray-700">
                <tr>
                    @foreach ($cols as $column)
                        <th class="border">{{ $column['syscolumn']['grid_label'] }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </div>

</x-app-layout>