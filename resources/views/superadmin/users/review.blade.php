@extends('adminlte::page')

@section('title', 'Review User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Details</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" class="form-control" 
                           value="{{ ucfirst($user->role) }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Account Created</label>
                    <input type="text" class="form-control" 
                           value="{{ $user->created_at->format('M d, Y H:i') }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit User
        </a>
    </div>
</div>
@endsection