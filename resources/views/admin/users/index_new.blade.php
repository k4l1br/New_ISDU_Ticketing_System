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
@stop

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary card-outline shadow-sm h-100" style="min-height: 400px; display: flex; flex-direction: column;">
        <div class="card-header bg-white border-bottom-0" style="padding-bottom: 0.5rem;">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="card-title mb-0 font-weight-bold text-primary" style="font-size: 1.25rem;">
                    <i class="fas fa-users mr-1"></i> Users List
                </h3>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Add New User
                </a>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body p-0 d-flex flex-column flex-grow-1 h-100" style="min-height:0;">
            <!-- Search Section -->
            <div class="row m-0 p-3 pb-0">
                <div class="col-md-6 px-1">
                    <div class="form-group mb-2">
                        <label for="global-search" class="mb-1">Global Search:</label>
                        <input type="text" id="global-search" class="form-control form-control-sm" placeholder="Search across all fields...">
                    </div>
                </div>
                <div class="col-md-6 px-1">
                    <!-- Intentionally left blank for alignment -->
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column px-3 pb-3" style="min-height:0; height:100%;">
                <div class="d-flex flex-column flex-grow-1 h-100" style="height:100%; min-height:0;">
                    <div class="mb-2" id="users-table-controls"></div>
                    <div class="table-responsive flex-grow-1" style="height:100%; min-height:0;">
                        <table id="users-table" class="table table-bordered table-striped table-hover mb-0 w-100" style="table-layout: auto;">
                            <thead>
                                <tr>
                                    <th style="width: 10%">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="width: 15%">Role</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge badge-{{ $user->role == 'admin' ? 'success' : 'primary' }}">
                                                {{ $user->role == 'super_admin' ? 'Super Admin' : ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Review Button - Modal Version -->
                                                <button class="btn btn-sm btn-info" 
                                                        data-toggle="modal" 
                                                        data-target="#reviewModal-{{ $user->id }}"
                                                        title="View User">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <p>No users found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            {{ $user->role == 'super_admin' ? 'Super Admin' : ucfirst($user->role) }}
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
$(document).ready(function() {
    // Initialize DataTable with global search
    const table = $('#users-table').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[1, 'asc']],
        language: {
            search: "Search users:",
            lengthMenu: "Show _MENU_ users per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users found",
            infoFiltered: "(filtered from _MAX_ total users)"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            // Move DataTable controls to our custom div
            $('#users-table-controls').html($('.dataTables_length').detach());
            $('.dataTables_filter').hide(); // Hide default search, we'll use our custom one
        }
    });

    // Connect our global search to DataTable
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endsection
@endsection
