@extends('adminlte::page')

@section('title', 'Edit Status')

@section('content')
<div class="card">
    <div class="card-header"><h3>Edit Status</h3></div>
    <div class="card-body">
        <form action="{{ route('status.update', $status->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Status Name</label>
                <input type="text" name="name" class="form-control" value="{{ $status->name }}" required>
            </div>
            <button class="btn btn-success">Update</button>
            <a href="{{ route('status.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
