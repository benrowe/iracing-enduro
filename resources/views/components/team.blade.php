<div class="bg-white rounded-md shadow-md p-4">
    <h2 class="font-bold">{{ $label }}:</h2>
    <ul>
        @foreach ($team as $acc => $value)
            <li>{{ $ratings[$acc]['name'] }} ({{ $value }})</li>
        @endforeach
    </ul>
    Avg: {{ array_sum($team) / count($team) }}

</div>
