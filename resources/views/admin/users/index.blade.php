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
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <th>
                        <input type="text" class="form-control form-control-sm" name="id_search" placeholder="Search ID..." value="{{ request('id_search') }}" form="searchForm">
                    </th>
                    <th>
                        <input type="text" class="form-control form-control-sm" name="name_search" placeholder="Search name..." value="{{ request('name_search') }}" form="searchForm">
                    </th>
                    <th>
                        <input type="text" class="form-control form-control-sm" name="username_search" placeholder="Search username..." value="{{ request('username_search') }}" form="searchForm">
                    </th>
                    <th>
                        <input type="text" class="form-control form-control-sm" name="email_search" placeholder="Search email..." value="{{ request('email_search') }}" form="searchForm">
                    </th>
                    <th>
                        <select class="form-control form-control-sm" name="role_search" form="searchForm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role_search') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role_search') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </th>
                    <th>
                        <button type="submit" form="searchForm" class="btn btn-sm btn-primary">Search</button>
                        <button type="button" id="clearFilters" class="btn btn-sm btn-secondary">Clear</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role == 'admin' ? 'success' : 'primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <!-- View -->
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#userModal-{{ $user->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <!-- Edit -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $users->appends(request()->except('page'))->links() }}
    </div>
</div>

<!-- Hidden Search Form -->
<form id="searchForm" method="GET" action="{{ route('admin.users.index') }}" style="display: none;">
    @foreach(request()->except('page') as $key => $value)
        @if($value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
</form>

<!-- User Detail Modals -->
@foreach($users as $user)
<div class="modal fade" id="userModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="userModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="userModalLabel-{{ $user->id }}">User Details: {{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Full Name</label>
                            <p class="form-control-static">{{ $user->name }}</p>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <p class="form-control-static">{{ $user->username }}</p>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Role</label>
                            <p class="form-control-static">
                                <span class="badge badge-{{ $user->role == 'admin' ? 'success' : 'primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Account Created</label>
                            <p class="form-control-static">{{ $user->created_at->format('F j, Y \a\t g:i a') }}</p>
                        </div>
                        <div class="form-group">
                            <label>Last Updated</label>
                            <p class="form-control-static">{{ $user->updated_at->format('F j, Y \a\t g:i a') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit User
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Submit search on filter change
        $('input[name$="_search"], select[name$="_search"]').on('change', function () {
            $('#searchForm').submit();
        });

        // Clear filters
        $('#clearFilters').on('click', function () {
            $('input[name$="_search"], select[name$="_search"]').val('');
            $('#searchForm').submit();
        });
    });
</script>
@endsection

@section('css')
<style>
    .badge {
        font-size: 100%;
    }
    .table th {
        vertical-align: middle;
    }
    .form-control-static {
        padding-top: 0;
        padding-bottom: 0;
        min-height: auto;
    }
</style>
@endsection


@section('css')
<style>
    .badge {
        font-size: 100%;
    }
    .table th {
        vertical-align: middle;
    }
    .form-control-static {
        padding-top: 0;
        padding-bottom: 0;
        min-height: auto;
    }
</style>
@endsection
