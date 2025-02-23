@extends('layouts.default')

@section('content')
    <x-heading>Settings</x-heading>
    <x-heading>Existing Members</x-heading>
    <ul>
        @foreach($members as $id=>$detail)
            <li>{{ $detail['name'] }} - {{ $id }}</li>
        @endforeach
    </ul>
    <form action="/settings/add" method="POST">
        @csrf
        <input type="text" name="memberId" placeholder="Member ID">
        <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
            Add Member
        </button>
    </form>

@endsection
