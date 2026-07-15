<footer class="main-footer erp-footer">
    <strong>&copy; {{ date('Y') }} <a href="{{ route('dashboard') }}">{{ config('erp.name') }}</a>.</strong>
    {{ config('erp.footer') }}
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
</footer>
