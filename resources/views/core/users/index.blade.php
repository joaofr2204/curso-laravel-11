<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="mb-4">
            <a href="{{ route('users.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Novo
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nome
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            E-mail
                        </th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $loop->odd ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                {{--
                                Ações podem ser descomentadas aqui
                                --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                Nenhum usuário cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between">
            <div class="mt-2 sm:mt-0">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>