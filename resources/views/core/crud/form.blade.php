<form action="{{ route("{$model->getTable()}.$action", $action == 'store' ? null : $model->id) }}" method="POST"
    class="bg-white dark:bg-gray-800 p-6 rounded shadow-md">

    @csrf

    @if($action == 'update')
        @method('PUT')
    @endif

    @foreach ($model->getFillable() as $campo)

        <div class="mb-4">
            <label for="{{$campo}}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $campo }}</label>
            <input type="text" id="{{ $campo }}" name="{{ $campo }}" value="{{ $model->{$campo} ?? old($campo) }}" required
                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">

            {{--
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" id="email" name="email" value="{{ $data['email'] ?? old('email') }}" required
                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">

            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <input type="password" id="password" name="password" {{ $action=='store' ? 'required' : '' }}
                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            --}}

        </div>

    @endforeach

    <button type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Enviar
    </button>

</form>