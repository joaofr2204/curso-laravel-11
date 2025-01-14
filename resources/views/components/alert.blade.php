@if (session()->has('success'))
    <div class="crud-messages hidden" data-type="success"
        data-title="Sucesso">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="crud-messages hidden" data-type="error" data-title="Erro">
        {{ session('error') }}
    </div>
@endif

@if (session()->has('warning'))
    <div class="crud-messages hidden" data-type="warning"
        data-title="Atenção">
        {{ session('warning') }}
    </div>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="crud-messages hidden" data-type="error" data-title="Erro">
            {{ $error }}
        </div>
    @endforeach
@endif