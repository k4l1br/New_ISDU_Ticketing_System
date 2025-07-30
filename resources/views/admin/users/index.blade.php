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

    });

    // Connect our global search to DataTable
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endsection

@endsection
