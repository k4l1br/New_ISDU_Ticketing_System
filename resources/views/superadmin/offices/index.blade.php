@extends('adminlte::page')

@section('title', 'Office Management')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Office Management</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Offices</li>
            </ol>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
    <style>
        .dataTables_filter { display: none; }
        .card-outline.card-primary {
            border-top: 3px solid #007bff;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.85em;
        }
        .btn-group .btn {
            margin: 0 2px;
        }
        .dt-buttons {
            margin-bottom: 10px;
        }
        .btn-print {
            background-color: #17a2b8 !important;
            color: #fff !important;
            border-color: #17a2b8 !important;
        }
        .btn-print:hover, .btn-print:focus {
            background-color: #138496 !important;
            border-color: #117a8b !important;
            color: #fff !important;
        }
    </style>
@endsection

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
                    <i class="fas fa-building mr-1"></i> Offices List
                </h3>
                <a href="{{ route('admin.offices.create') }}" class="btn btn-primary btn-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Add New Office
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
                    <div class="mb-2" id="offices-table-controls"></div>
                    <div class="table-responsive flex-grow-1" style="height:100%; min-height:0;">
                        <table id="offices-table" class="table table-bordered table-striped table-hover mb-0 w-100" style="table-layout: auto;">
                            <thead>
                                <tr>
                                    <th style="width: 10%">#</th>
                                    <th>Office Name</th>
                                    <th>Abbreviation</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offices as $office)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $office->name }}</td>
                                        <td>{{ $office->abbreviation ?: 'N/A' }}</td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.offices.edit', $office) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit Office">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this office?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Office">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No offices found.</p>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the office: <strong id="officeName"></strong>?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#offices-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: '<"row mb-2"<"col-md-6"B><"col-md-6"f>>rtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-print btn-sm'
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search offices...",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)"
        },
        order: [[1, 'asc']] // Default sort by name
    });

    // Global search functionality
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

function confirmDelete(officeId, officeName) {
    $('#officeName').text(officeName);
    $('#deleteForm').attr('action', '{{ route("admin.offices.index") }}/' + officeId);
    $('#deleteModal').modal('show');
}
</script>
@stop
