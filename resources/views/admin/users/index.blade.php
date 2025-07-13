@extends('adminlte::page')

@section('title', 'User Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <th>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               name="name_search" 
                               placeholder="Search name..."
                               value="{{ request('name_search') }}"
                               form="searchForm">
                    </th>
                    <th>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               name="email_search" 
                               placeholder="Search email..."
                               value="{{ request('email_search') }}"
                               form="searchForm">
                    </th>
                    <th>
                        <select class="form-control form-control-sm" name="role_search" form="searchForm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role_search') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role_search') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role == 'admin' ? 'success' : 'primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                    
                        
                        <!-- Review Button - Modal Version (Alternative) -->
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#reviewModal-{{ $user->id }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        
                        <!-- Delete Button -->
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $users->appends(request()->except('page'))->links() }}
    </div>
</div>

<!-- Search Form -->
<form id="searchForm" method="GET" action="{{ route('admin.users.index') }}" style="display: none;">
    @foreach(request()->except('name_search', 'email_search', 'role_search', 'page') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>

<!-- Review Modals -->
@foreach($users as $user)
<div class="modal fade" id="reviewModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details: {{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Name:</div>
                    <div class="col-md-8">{{ $user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Email:</div>
                    <div class="col-md-8">{{ $user->email }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Role:</div>
                    <div class="col-md-8">
                        <span class="badge badge-{{ $user->role == 'admin' ? 'success' : 'primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Created At:</div>
                    <div class="col-md-8">{{ $user->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@section('js')
<script>
    // Submit form when search fields change
    document.querySelectorAll('#searchForm input, #searchForm select').forEach(element => {
        element.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });
</script>
@endsection
@endsection