@php
    /**
     * @var string $key
     * @var string $slot
     */
@endphp
<div id="{{ $key }}">
    @fragment($key)
        {{ $slot }}
    @endfragment
</div>
