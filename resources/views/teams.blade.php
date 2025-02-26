@php
    use App\Enums\RouteNames;
@endphp
@extends('layouts.default')

@section('content')
    <x-fragment key="teams">
        <x-heading>Unallocated Members</x-heading>
        <ul>
            @foreach($unallocatedMembers as $id=>$detail)
                <li>
                    {{ $detail['name'] }} - {{ $detail['irating'] }}
                    @foreach($teams as $index=> $team)
                        <a hx-post="/teams/{{ ++$index }}/members/{{ $id }}" hx-target="#teams" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 text-sm">{{ $index }}</a>
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
    <a hx-post="/teams/add" hx-target="#teams" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-plus" /> Add Team
    </a>
    <a hx-post="/teams/auto-allocate" hx-target="#teams" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-account-alert" /> Auto Allocate Teams
    </a>

    <a href="/members/refresh" hx-target="teams" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-refresh" /> Reload Member Stats
    </a>
@endsection
