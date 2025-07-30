@extends('adminlte::page')

@section('title', 'User Management')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">User Management</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users List</h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="global-search" class="form-control" placeholder="Search users...">
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->role === 'super_admin' ? 'danger' : 'info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td>{{ $user->unit ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#userModal-{{ $user->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                            <strong>ID:</strong> {{ $user->id }}<br>
                            <strong>Name:</strong> {{ $user->name }}<br>
                            <strong>Email:</strong> {{ $user->email }}<br>
                            <strong>Username:</strong> {{ $user->username ?? 'N/A' }}<br>
                        </div>
                        <div class="col-md-6">
                            <strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role)) }}<br>
                            <strong>Unit:</strong> {{ $user->unit ?? 'N/A' }}<br>
                            <strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}<br>
                            <strong>Created:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}<br>
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
    // Initialize DataTable
    var table = $('#users-table').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "info": true,
        "paging": true,
        "searching": false, // We'll use our custom search
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [6] } // Actions column not orderable
        ]
    });

    // Connect our global search to DataTable
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endsection
