@php
    use App\Enums\RouteNames;
@endphp
@extends('layouts.default')

@section('content')
    <x-heading>Available Members</x-heading>
    <ul>
        @foreach($ratings as $id=>$detail)
            <li>{{ $detail['name'] }} - {{ $detail['irating'] }}</li>
        @endforeach
    </ul>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <x-team label="Team 1" :team="$team1" :ratings="$ratings"/>
        <x-team label="Team 2" :team="$team2" :ratings="$ratings"/>
    </div>
    <a href="/teams/add" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-plus" /> Add Team
    </a>

    <a href="/refresh" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
        <x-icon class="i-mdi-refresh" /> Reload Member Stats
    </a>
@endsection
