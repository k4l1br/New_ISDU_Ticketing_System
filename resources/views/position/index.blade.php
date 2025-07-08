@extends('adminlte::page')

@section('title', 'Positions')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Positions</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Positions</li>
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
                    <i class="fas fa-list mr-1"></i> Position List
                </h3>
                <a href="{{ route('position.create') }}" class="btn btn-primary btn-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Add Position
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
                    <div class="mb-2" id="positions-table-controls"></div>
                    <div class="table-responsive flex-grow-1" style="height:100%; min-height:0;">
                        <table id="positions-table" class="table table-bordered table-striped table-hover mb-0 w-100" style="table-layout: auto;">
                        <thead>
                            <tr>
                                <th style="width: 10%">#</th>
                                <th>Position Name</th>
                                <th>Description</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($positions as $position)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $position->name }}</td>
                                    <td>{{ $position->description }}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('position.edit', $position->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit Position">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('position.destroy', $position->id) }}" 
                                                  method="POST" 
                                                  style="display:inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this position?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Delete Position">
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
                                        <p>No positions found.</p>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <style>
        .table-responsive {
            min-height: 300px;
        }
        .filters input {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .btn-icon {
            width: 2.2rem;
            height: 2.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 1.1rem;
        }
        .card-outline.card-primary {
            border-top: 3px solid #007bff;
        }
        .badge {
            font-size: 0.8rem;
        }
        .dataTables_filter {
            display: none;
        }
    </style>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#positions-table').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'asc']],
        dom: 'B<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
        pageLength: 8,
        lengthChange: false,
        paging: true,
        scrollCollapse: false,
        initComplete: function() {
            var tableWrapper = $('#positions-table').closest('.dataTables_wrapper');
            $('#positions-table-controls').append(tableWrapper.find('.dt-buttons')).append(tableWrapper.find('.dataTables_length'));
        },
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
            zeroRecords: "No matching records found",
            info: "",
            infoEmpty: "",
            infoFiltered: "",
            search: "Search:"
        }
    });

    // Global search
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection