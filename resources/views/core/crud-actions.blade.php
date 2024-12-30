<div class="flex justify-center space-x-2">
    <a href="{{ route('users.show', $user->id) }}" title="Visualizar"
        class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('users.edit', $user->id)  }}" title="Editar"
        class="inline-flex items-center px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2 transition">
        <i class="fas fa-edit"></i>
    </a>
</div>