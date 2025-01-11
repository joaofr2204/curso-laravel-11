<form action="{{ route("{$model->getTable()}.$action", $action == 'store' ? null : $model->id) }}" method="POST"
    class="bg-white dark:bg-gray-800 p-6 rounded shadow-md">

    @csrf

    @if($action == 'update')
        @method('PUT')
    @endif

    @foreach ($model->getColumns('form', $action) as $field)

        @if($field['type'] == 'CH') {{-- CHECKBOX --}}
            <div class="flex sm:grid sm:grid-cols-12 items-center my-4">
                <input id="{{ $field['name'] }}" type="checkbox" {{ $model->{$field['name']} ? 'checked' : ''}} {{ $field["readonly_on_{$action}"] ? '' : "name={$field['name']}" }}
                    class="w-4 h-4 sm:col-start-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="{{$field['name']}}"
                    class="ms-2 sm:ms-[-1em] md:ms-[-2em] lg:ms-[-3em] xl:ms-[-5em] 2xl:ms-[-6em] text-sm font-medium text-gray-900 dark:text-gray-300">{{ $field['name'] }}</label>
            </div>
        @else

            <div class="mb-2 grid sm:grid-cols-6">
                <label for="{{$field['name']}}"
                    class="block sm:inline-block sm:col-span-2 sm:pt-3 sm:mr-2 text-sm font-medium text-gray-700 dark:text-gray-300 text-left sm:text-right ">{{ $field['name'] }}</label>

                @php
                    if ($field['type'] == 'BI') { // BIGINT
                        $type = 'number';
                    } else {
                        $type = 'text';
                    }
                @endphp

                <input type="{{ $type }}" id="{{ $field['name'] }}"
                    value="{{ $model->{$field['name']} ?? old($field['name']) }}" {{ $field["readonly_on_{$action}"] ? 'disabled' : "name={$field['name']}" }} {{ $field["required_on_{$action}"] ? 'required' : '' }}
                    class="mt-1 block sm:inline-block  sm:col-span-4 px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />

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

    <button type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Enviar
    </button>

</form>