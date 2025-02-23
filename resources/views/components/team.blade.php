<div class="bg-white rounded-md shadow-md p-4 relative">
    <div class="absolute top-2 right-2">
        <a href="/team/delete" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
            <x-icon class="i-mdi-trash-can" />
        </a>
    </div>
    <h2 class="font-bold">{{ $label }}:</h2>
    <ul>
        @foreach ($team as $acc => $value)
            <li>{{ $ratings[$acc]['name'] }} ({{ $value }})</li>
        @endforeach
    </ul>
    @if($team)
    Avg: {{ array_sum($team) / count($team) }}
    @endif

</div>
