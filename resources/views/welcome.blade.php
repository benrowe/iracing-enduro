@php
    use App\Enums\RouteNames;
@endphp
@extends('layouts.auth')

@section('content')
    <x-heading>Available Members</x-heading>
    <ul>
        @foreach($ratings as $id=>$detail)
            <li>{{ $detail['name'] }} - {{ $detail['irating'] }}</li>
        @endforeach
    </ul>
    <div class="grid grid-cols-2 gap-4">
        <x-team label="Team 1" :team="$team1" :ratings="$ratings" />
        <x-team label="Team 2" :team="$team2" :ratings="$ratings" />
    </div>


    <a href="/refresh" class="underline">refresh</a>
    <select x-model="color">
        <option>Red</option>
        <option>Orange</option>
        <option>Yellow</option>
    </select>

    Color: <span x-text="color"></span>
@endsection
