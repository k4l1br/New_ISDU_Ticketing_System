@extends('adminlte::page')

@section('title', 'References')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">References</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">References</li>
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

    <div class="card card-primary card-outline shadow-sm h-100">
        <div class="card-header bg-white border-bottom-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0 font-weight-bold text-primary">
                    <i class="fas fa-book mr-1"></i> Reference List
                </h3>
                <a href="{{ route('references.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Add Reference
                </a>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body p-0">
            <!-- Search Section -->
            <div class="px-3 pt-3 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="global-search">Search References:</label>
                            <input type="text" id="global-search" class="form-control form-control-sm" placeholder="Search reference types...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div class="table-responsive">
                    <table id="references-table" class="table table-bordered table-striped table-hover mb-0 w-100">
                        <thead>
                            <tr>
                                <th style="width: 10%">#</th>
                                <th>Reference Type</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($references as $reference)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $reference->reference_type }}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('references.edit', $reference->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('references.destroy', $reference->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this reference?')">
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
                                        <p>No references found.</p>
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
    var table = $('#references-table').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'asc']],
        pageLength: 8,
        lengthChange: false,
        dom: 'Bfrtip',
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
            search: "Search:",
            info: "Showing _START_ to _END_ of _TOTAL_ references",
            infoEmpty: "Showing 0 to 0 of 0 references",
            infoFiltered: "(filtered from _MAX_ total references)"
        }
    });

    // Custom search functionality
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
