@extends('adminlte::page')

@section('title', 'Edit Requesting Office')

@section('content_header')
    <h1>Edit Requesting Office</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('reqOffice.update', $office->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="reqOffice">Requesting Office Name</label>
                <input type="text" name="reqOffice" class="form-control" id="reqOffice" value="{{ $office->req_office }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update Office</button>
            <a href="{{ route('reqOffice.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@stop
