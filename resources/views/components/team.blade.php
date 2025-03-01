<div class="bg-white rounded-md shadow-md p-4 relative">
    <div class="absolute top-2 right-2">
        <a
            hx-delete="{{ route(\App\Enums\RouteNames::TEAMS_DELETE, ['index' => $index]) }}"
            hx-vals='{"_method": "DELETE"}'
            hx-target="#teams"
            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300"
        >
            <x-icon class="i-mdi-trash-can" />
        </a>
    </div>
    <h2 class="font-bold">{{ $label }}:</h2>
    <ul>
        @foreach ($team->members as $value)
            <li>
                {{ $allMembers[$value]['name'] }} ({{ $allMembers[$value]['irating'] }})
                <a
                    hx-delete="{{ route(\App\Enums\RouteNames::TEAMS_MEMBERS_DELETE, ['index' => $index, 'id' => $value]) }}"
                    hx-vals='{"_method": "DELETE"}'
                    hx-target="#teams"
                >
                    <x-icon class="i-mdi-trash-can" />
                </a>
            </li>
        @endforeach
    </ul>
    @if($team->members)
        <?php $ratings = array_map(fn($id) => $allMembers[$id]['irating'], $team->members); ?>
        Avg: {{ round(array_sum($ratings) / count($team->members)) }}
    @endif

</div>
