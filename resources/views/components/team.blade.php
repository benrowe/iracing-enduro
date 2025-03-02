<x-card>
    <x-slot:header>
        <div class="flex justify-between items-center w-full gap-1">
            <h2 class="font-bold">{{ $label }}</h2>
            <div>
                <a
                    hx-delete="{{ route(\App\Enums\RouteNames::TEAMS_DELETE, ['index' => $index]) }}"
                    hx-vals='{"_method": "DELETE"}'
                    hx-target="#teams"
                    class="bg-gray-200 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300 cursor-pointer"
                >
                    <x-icon class="i-mdi-trash-can" />
                </a>
            </div>
        </div>


    </x-slot:header>
    <strong>
        @if($team->members)
                <?php $ratings = array_map(fn($id) => $allMembers[$id]['irating'], $team->members); ?>
            <div class="w-full">Average Team iRating: {{ round(array_sum($ratings) / count($team->members)) }} </div>
        @endif
    </strong>
    <ul>
        @foreach ($team->members as $value)
            <li>
                <x-member :member="$value" :allMembers="$allMembers"></x-member>

                <a
                    hx-delete="{{ route(\App\Enums\RouteNames::TEAMS_MEMBERS_DELETE, ['index' => $index, 'id' => $value]) }}"
                    hx-vals='{"_method": "DELETE"}'
                    class="cursor-pointer"
                >
                    <x-icon class="i-mdi-trash-can" />
                </a>
            </li>
        @endforeach
    </ul>
</x-card>
