<style>
.label-required::after {
    content: " *"; /* Adiciona um asterisco */
    color: red;    /* Define a cor vermelha */
    font-weight: bold; /* Opcional: deixa o asterisco em negrito */
}    
</style>
<form action="{{ route("{$model->getTable()}.$action", $action == 'store' ? null : $model->id) }}" method="POST"
    class="bg-white dark:bg-gray-800 p-6 rounded shadow-md grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

    @csrf

    @if($action == 'update')
        @method('PUT')
    @endif

    @php
        $action = $action == 'store' ? 'create' : $action;
        $action = $action == 'update' ? 'edit' : $action;
    @endphp

    @foreach ($model->getFormColumns($action) as $field)

        @if($field['type'] == 'CH') {{-- CHECKBOX --}}

            <div class="flex items-center {{ $field['field_class'] }}">
                <input type="hidden" name="{{ $field['name'] }}" value="0" />
                <input id="{{ $field['name'] }}" type="checkbox" {{ $model->{$field['name']} ? 'checked' : ''}} {{ $field["readonly_on_{$action}"] ? 'disabled' : "name={$field['name']}" }} title="{{ $field['name'] }}"
                    value="1"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="{{$field['name']}}"
                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ ucfirst($field['form_label']) }}</label>
            </div>
        @elseif(in_array($field['type'],['CV','CI'])) {{-- COMBOBOX VARCHAR / COMBOBOX INTEGER --}}
            <div class="{{ $field['field_class'] }}">

                <label for="{{$field['name']}}"
                    class="block text-sm font-medium text-gray-900 dark:text-white {{$field["required_on_{$action}"] ? 'label-required' : ''}}">{{ ucfirst($field['form_label']) }}</label>

                <select id="{{$field['name']}}" {{ $field["readonly_on_{$action}"] ? 'disabled' : "name={$field['name']}" }} {{ $field["required_on_{$action}"] ? 'required' : '' }}
                    class="block w-full p-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=""></option>
                    @foreach($field['options'] as $key => $option)
                        <option value="{{ $key }}" {{ $model->{$field['name']} == $key ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>

            </div>
        @else
            <div>
                <label for="{{$field['name']}}"
                    class="block text-sm font-medium text-gray-900 dark:text-white {{$field["required_on_{$action}"] ? 'label-required' : ''}}">{{ ucfirst($field['form_label']) }}</label>

                @php
                    if (in_array($field['type'],['BI','IN'])) { // BIGINT, INTEGER
                        $type = 'number';
                    } else if($field['type']=='PW') { // PASSWORD
                        $type = 'password';
                    } else {
                        $type = 'text';
                    }
                @endphp

                <input type="{{ $type }}" id="{{ $field['name'] }}"
                    value="{{ $model->{$field['name']} ?? old($field['name']) }}" {{ $field["readonly_on_{$action}"] ? 'disabled' : "name={$field['name']}" }} {{ $field["required_on_{$action}"] ? 'required' : '' }}
                    class="block w-full p-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            </div>
        @endif
        {{--
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input type="email" id="email" name="email" value="{{ $data['email'] ?? old('email') }}" required
            class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">

        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
        <input type="password" id="password" name="password" {{ $action=='store' ? 'required' : '' }}
            class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        --}}


    @endforeach

    <hr class="my-4 sm:col-span-2 lg:col-span-3">

    <button type="submit"
        class="sm:col-span-2 lg:col-span-3 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Enviar
    </button>

</form>