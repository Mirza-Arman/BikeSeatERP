@if (session('success') || session('error') || session('warning') || session('info') || session('status'))
    <div class="d-none" aria-hidden="true">
        @if (session('success'))
            <div data-flash="success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div data-flash="error">{{ session('error') }}</div>
        @endif
        @if (session('warning'))
            <div data-flash="warning">{{ session('warning') }}</div>
        @endif
        @if (session('info'))
            <div data-flash="info">{{ session('info') }}</div>
        @endif
        @if (session('status'))
            <div data-flash="status">{{ session('status') }}</div>
        @endif
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5><i class="icon fas fa-ban"></i> Validation Error</h5>
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
