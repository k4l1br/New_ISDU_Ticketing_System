@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <h1>My Profile</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
            <p><strong>Member since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
        </div>
    </div>
@stop