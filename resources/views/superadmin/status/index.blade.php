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

    <div class="card card-primary card-outline shadow-sm h-100" style="min-height: 400px; display: flex; flex-direction: column;">
        <div class="card-header bg-white border-bottom-0" style="padding-bottom: 0.5rem;">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="card-title mb-0 font-weight-bold text-primary" style="font-size: 1.25rem;">
                    <i class="fas fa-flag mr-1"></i> Status List
                </h3>
                <a href="{{ route('status.create') }}" class="btn btn-primary btn-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Add Status
                </a>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body p-0 d-flex flex-column flex-grow-1 h-100" style="min-height:0;">
            <!-- Export Controls Section -->
            <div class="px-3 pt-3 pb-2">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div id="status-table-controls" class="d-flex align-items-center">
                            <!-- DataTable buttons will be moved here -->
                        </div>
                    </div>
                   
                </div>
            </div>
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
<link rel="stylesheet" href="{{ asset('assets/css/datatables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables.buttons.bootstrap4.min.css') }}">
<style>
    .dataTables_filter { display: none; }
    .card-outline.card-primary {
        border-top: 3px solid #007bff;
    }
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .badge {
        font-size: 0.8rem;
    }
    /* DataTable button styling */
    .dt-buttons {
        margin-bottom: 0;
    }
    .dt-buttons .btn {
        margin-right: 0.25rem;
        margin-bottom: 0;
    }
    .dt-buttons .btn:last-child {
        margin-right: 0;
    }
    /* Hide default DataTable buttons container */
    .dataTables_wrapper .dt-buttons {
        display: none;
    }
    /* Show our custom buttons container */
    #status-table-controls .dt-buttons {
        display: flex !important;
        gap: 0.25rem;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/export/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/export/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/export/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/buttons.print.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced configuration
    var table = $('#status-table').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'asc']],
        pageLength: 10, // Show 10 records per page
        lengthChange: true,
        paging: true, // Enable pagination
        info: true, // Enable info about showing X of Y entries
        dom: 'Blfrtip', // Added length and pagination controls
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],
        language: {
            zeroRecords: "No matching statuses found",
            search: "Search:",
            info: "Showing _START_ to _END_ of _TOTAL_ statuses",
            infoEmpty: "Showing 0 to 0 of 0 statuses",
            infoFiltered: "(filtered from _MAX_ total statuses)",
            lengthMenu: "Show _MENU_ statuses per page",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function() {
            // Move the DataTable buttons to our custom controls div
            var tableWrapper = $('#status-table').closest('.dataTables_wrapper');
            $('#status-table-controls').append(tableWrapper.find('.dt-buttons'));
        }
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection
