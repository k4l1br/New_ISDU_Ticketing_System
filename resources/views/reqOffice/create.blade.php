@extends('adminlte::page')

@section('title', 'Add Requesting Office')

@section('content_header')
    <h1>Add Requesting Office</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('reqOffice.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="reqOffice">Requesting Office Name</label>
                <input type="text" name="reqOffice" class="form-control" id="reqOffice" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Office</button>
            <a href="{{ route('reqOffice.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@stop
