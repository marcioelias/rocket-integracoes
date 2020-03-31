<form method="POST" action="{{ $route }}">
    @csrf
    @if (isset($xMethod))
        @method($xMethod)
    @endif
    {{ $slot }}
</form>
