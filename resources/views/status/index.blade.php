@extends('adminlte::page')

@section('title', 'Statuses')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Statuses</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Statuses</li>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary card-outline shadow-sm h-100">
        <div class="card-header bg-white border-bottom-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0 font-weight-bold text-primary">
                    <i class="fas fa-flag mr-1"></i> Status List
                </h3>
                <a href="{{ route('status.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Status
                </a>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body p-0">
            <div class="px-3 pb-3">
                <div class="table-responsive">
                    <table id="status-table" class="table table-bordered table-striped table-hover mb-0 w-100">
                        <thead>
                            <tr>
                                <th style="width: 10%">#</th>
                                <th>Status Name</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statuses as $status)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $status->name }}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('status.edit', $status->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('status.destroy', $status->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this status?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No statuses found.</p>
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
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<style>
    .dataTables_filter { display: none; }
</style>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#status-table')) {
            $('#status-table').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc']],
                pageLength: 8,
                lengthChange: false,
                language: {
                    zeroRecords: "No matching records found"
                }
            });
        }
    });
</script>
@endsection
