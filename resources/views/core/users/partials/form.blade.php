<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
    <input type="text" id="name" name="name" value="{{ $data['name'] ?? old('name') }}" required
        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
</div>
<div class="mb-4">
    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
    <input type="email" id="email" name="email" value="{{ $data['email'] ?? old('email') }}" required
        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
</div>
<div class="mb-4">
    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
    <input type="password" id="password" name="password" {{ $action == 'store' ? 'required' : '' }}
        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
</div>
