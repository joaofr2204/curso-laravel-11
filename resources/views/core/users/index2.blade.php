<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <x-alert />

        <div class="mb-4">
            <a href="{{ route('users.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Novo
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <!-- Cabeçalho fixo -->
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
            </table>

            <!-- Corpo com rolagem -->
            <div class="h-[65vh] overflow-y-auto">
                <table class="min-w-full table-auto border-collapse">
                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $loop->odd ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }}">
                                <td
                                    class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-500">
                                        Editar
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-500">
                                        Detalhes
                                    </a>
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
        </div>


        <div class="mt-4 flex flex-col sm:flex-row">
            <div class="mt-2 sm:mt-0 w-full">
                {{ $users->onEachSide(2)->links() }}
            </div>
        </div>
    </div>
</x-app-layout>