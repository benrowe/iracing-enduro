@php
    use App\Enums\RouteNames;
@endphp
@extends('layouts.default')

@section('content')
    <div hx-boost="true" hx-target="#teams" hx-indicator="#content-spinner" hx-swap="innerHtml transition:true">
    <x-fragment key="teams">
        <x-card>
            <x-slot:header>
                <div class="flex justify-between items-center w-full">
                    <x-heading>Unallocated Members</x-heading>
                    @if ($unallocatedMembers)
                        <a hx-post="{{ route(RouteNames::TEAMS_AUTO_ALLOCATE) }}" class="bg-gray-200 text-white px-2 py-2 text-sm rounded-lg hover:bg-gray-700 transition duration-300 cursor-pointer">
                            <x-icon class="i-mdi-account-alert" /> Auto Allocate Remaining Members
                        </a>
                    @endif
                </div>


            </x-slot:header>
            @if ($unallocatedMembers)
                <strong>
                    Average iRating:
                    {{ round(array_sum(array_map(fn($detail) => $detail['irating'], $unallocatedMembers)) / count($unallocatedMembers)) }}

                </strong>
            @endif
            <ul id="unallocated-members">
                @forelse($unallocatedMembers as $id=>$detail)
                    <li>
                        <x-member :member="$id" :allMembers="$unallocatedMembers"></x-member>
                        <span class="text-sm">
                            Add to team
                            @foreach($teams as $index=> $team)
                                <a hx-post="{{ route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => ++$index, 'id' => $id]) }}" class="bg-red-600 text-white px-2 py-1 rounded-lg hover:bg-red-700 transition duration-300 text-sm cursor-pointer">{{ $index }}</a>
                            @endforeach
                        </span>
                    </li>
                @empty
                    <li>no unallocated team members</li>
                @endforelse
            </ul>
        </x-card>

        <x-heading>Teams</x-heading>
        <div class="grid grid-cols-2 gap-4 mb-4">
            @foreach($teams as $index => $team)
                <x-team :label="'Team ' . (++$index)" :index="$index" :team="$team" :allMembers="$members"/>
            @endforeach
        </div>
    </x-fragment>
        <div class="flex w-full flex-justify-center gap-2">
        <a hx-post="{{ route(RouteNames::TEAMS_ADD) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 cursor-pointer">
            <x-icon class="i-mdi-plus" /> Add Team
        </a>


        <a href="{{ route(RouteNames::MEMBERS_REFRESH) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 cursor-pointer">
            <x-icon class="i-mdi-refresh" /> Reload Member Stats (from iRacing)
        </a>

        <a hx-post="{{ route(RouteNames::TEAMS_RESET) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 cursor-pointer">
            <x-icon class="i-mdi-refresh" /> Reset Everything!
        </a>
        </div>
        <div id="content-spinner" class="htmx-indicator absolute top-24 right-4 flex justify-center items-center opacity-0 transition-opacity duration-300">
            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
@endsection
