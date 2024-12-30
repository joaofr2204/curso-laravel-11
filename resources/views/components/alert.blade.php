@if (session()->has('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

@if (session()->has('warning'))
    <div class="bg-yellow-100 text-yellow-700 px-4 py-3 rounded mb-4">
        {{ session('warning') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif