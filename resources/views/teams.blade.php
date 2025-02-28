@php
    use App\Enums\RouteNames;
@endphp
@extends('layouts.default')

@section('content')
    <div hx-boost="true" hx-target="#teams" hx-indicator="#content-spinner" hx-swap="innerHtml transition:true">
    <x-fragment key="teams">
        <x-heading>Unallocated Members</x-heading>
        <ul>
            @foreach($unallocatedMembers as $id=>$detail)
                <li>
                    {{ $detail['name'] }} - {{ $detail['irating'] }}
                    @foreach($teams as $index=> $team)
                        <a hx-post="{{ route(RouteNames::TEAMS_MEMBERS_STORE, ['index' => ++$index, 'id' => $id]) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 text-sm">{{ $index }}</a>
                    @endforeach
                </li>
            @endforeach
        </ul>
        <div class="grid grid-cols-2 gap-4 mb-4">
            @foreach($teams as $index => $team)
                <x-team :label="'Team ' . (++$index)" :index="$index" :team="$team" :allMembers="$members"/>
            @endforeach
        </div>
    </x-fragment>
    <a hx-post="{{ route(RouteNames::TEAMS_ADD) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-plus" /> Add Team
    </a>
    <a hx-post="{{ route(RouteNames::TEAMS_AUTO_ALLOCATE) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-account-alert" /> Auto Allocate Teams
    </a>

    <a href="{{ route(RouteNames::MEMBERS_REFRESH) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-refresh" /> Reload Member Stats
    </a>

    <a hx-post="{{ route(RouteNames::TEAMS_RESET) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-refresh" /> Reset Teams
    </a>
        <div id="content-spinner" class="htmx-indicator absolute top-24 right-4 flex justify-center items-center opacity-0 transition-opacity duration-300">
            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
@endsection
