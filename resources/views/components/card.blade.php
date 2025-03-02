<div class="bg-white rounded-md shadow-md relative">
    @if (isset($header))
        <div class="flex bg-red-600 text-white border-b rounded-t-md p-2">
            {{ $header }}
        </div>
    @endif
    <div class="p-4">
        {{ $slot }}
    </div>
</div>
