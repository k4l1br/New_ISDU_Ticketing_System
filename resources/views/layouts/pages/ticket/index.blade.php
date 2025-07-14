        @extends('adminlte::page')

        @section('title', 'Tickets Management')

<<<<<<< Updated upstream
        @section('content_header')
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tickets Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tickets</li>
                    </ol>
                </div>
=======
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Tickets Management</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tickets</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0"> 
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Tickets List
            </h3>
            <div class="card-tools">
                <a href="{{ route('pages.ticket.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Create New Ticket
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filter Section (Global search removed) -->
            <div class="row mb-3">
                <!-- No global search or status filter here anymore -->
>>>>>>> Stashed changes
            </div>
        @endsection

<<<<<<< Updated upstream
        @section('content')
        <div class="container-fluid px-0"> 
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
=======
            <!-- Responsive Table -->
            <div class="table-responsive">
                <table id="tickets-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user mr-1"></i>Full Name</th>
                            <th><i class="fas fa-briefcase mr-1"></i>Position</th>
                            <th><i class="fas fa-id-badge mr-1"></i>Designation</th>
                            <th><i class="fas fa-phone mr-1"></i>Contact</th>
                            <th><i class="fas fa-envelope mr-1"></i>Email</th>
                            <th><i class="fas fa-building mr-1"></i>Office</th>
                            <th><i class="fas fa-hashtag mr-1"></i>Reference</th>
                            <th><i class="fas fa-gavel mr-1"></i>Authority</th>
                            <th>
                                <i class="fas fa-flag mr-1"></i>Status
                                <div style="margin-top: 5px;">
                                    <select id="status-filter-header" class="form-control form-control-sm">
                                        <option value="">All Status</option>
                                        <option value="no action">No Action</option>
                                        <option value="in progress">In Progress</option>
                                        <option value="complete">Completed</option>
                                    </select>
                                </div>
                            </th>
                            <th><i class="fas fa-users mr-1"></i>Unit Responsible</th>
                            <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                        </tr>
                        <tr class="filters">
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><!-- Email: no search --></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><!-- Status: no search, filter is above --></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                            <th><!-- Actions column: keep empty to match header count --></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            @php
                                $status = strtolower(trim($ticket->status));
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $ticket->fullName }}</strong>
                                </td>
                                <td>{{ $ticket->position }}</td>
                                <td>{{ $ticket->designation }}</td>
                                <td>
                                    <a href="tel:{{ $ticket->contactNumber }}" class="text-decoration-none">
                                        <i class="fas fa-phone-alt mr-1"></i>{{ $ticket->contactNumber }}
                                    </a>
                                </td>
                                <td>
                                    <a href="mailto:{{ $ticket->emailAddress }}" class="text-decoration-none">
                                        <i class="fas fa-envelope mr-1"></i>{{ $ticket->emailAddress }}
                                    </a>
                                </td>
                                <td>{{ $ticket->reqOffice }}</td>
                                <td>
                                    <code>{{ $ticket->reference }}</code>
                                </td>
                                <td>{{ $ticket->authority }}</td>
                                <td>
                                    @if($status === 'no action')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times mr-1"></i>No Action
                                        </span>
                                    @elseif($status === 'in progress')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i>In Progress
                                        </span>
                                    @elseif(in_array($status, ['complete', 'completed', 'closed']))
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Completed
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-question mr-1"></i>{{ $ticket->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $ticket->unitResponsible }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('ticket.edit', $ticket->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit Ticket">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                data-toggle="modal" 
                                                data-target="#viewModal{{ $ticket->id }}"
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('ticket.destroy', $ticket->id) }}" 
                                              method="POST" 
                                              style="display:inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete Ticket">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
>>>>>>> Stashed changes

            <!-- Statistics Cards removed as requested -->

            <!-- Main Content Card -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Tickets List
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('pages.ticket.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Create New Ticket
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="global-search">Global Search:</label>
                                <input type="text" id="global-search" class="form-control form-control-sm" placeholder="Search across all fields...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status-filter">Filter by Status:</label>
                                <select id="status-filter" class="form-control form-control-sm">
                                    <option value="">All Status</option>
                                    <option value="no action">No Action</option>
                                    <option value="in progress">In Progress</option>
                                    <option value="complete">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Table -->
                    <div class="table-responsive">
                        <table id="tickets-table" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user mr-1"></i>Full Name</th>
                                    <th><i class="fas fa-briefcase mr-1"></i>Position</th>
                                    <th><i class="fas fa-id-badge mr-1"></i>Designation</th>
                                    <th><i class="fas fa-phone mr-1"></i>Contact</th>
                                    <th><i class="fas fa-envelope mr-1"></i>Email</th>
                                    <th><i class="fas fa-building mr-1"></i>Office</th>
                                    <th><i class="fas fa-hashtag mr-1"></i>Reference</th>
                                    <th><i class="fas fa-gavel mr-1"></i>Authority</th>
                                    <th><i class="fas fa-flag mr-1"></i>Status</th>
                                    <th><i class="fas fa-users mr-1"></i>Unit Responsible</th>
                                    <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                                </tr>
                                <tr class="filters">
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><!-- Email: no search --></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><!-- Status: no search --></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search..."></th>
                                    <th><!-- Actions column: keep empty to match header count --></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    @php
                                        $status = strtolower(trim($ticket->status));
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $ticket->fullName }}</strong>
                                        </td>
                                        <td>{{ $ticket->position }}</td>
                                        <td>{{ $ticket->designation }}</td>
                                        <td>
                                            <a href="tel:{{ $ticket->contactNumber }}" class="text-decoration-none">
                                                <i class="fas fa-phone-alt mr-1"></i>{{ $ticket->contactNumber }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $ticket->emailAddress }}" class="text-decoration-none">
                                                <i class="fas fa-envelope mr-1"></i>{{ $ticket->emailAddress }}
                                            </a>
                                        </td>
                                        <td>{{ $ticket->reqOffice }}</td>
                                        <td>
                                            <code>{{ $ticket->reference }}</code>
                                        </td>
                                        <td>{{ $ticket->authority }}</td>
                                        <td>
                                            @if($status === 'no action')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times mr-1"></i>No Action
                                                </span>
                                            @elseif($status === 'in progress')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>In Progress
                                                </span>
                                            @elseif(in_array($status, ['complete', 'completed', 'closed']))
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Completed
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-question mr-1"></i>{{ $ticket->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $ticket->unitResponsible }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('ticket.edit', $ticket->id) }}" 
                                                class="btn btn-sm btn-warning" 
                                                title="Edit Ticket">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-info" 
                                                        data-toggle="modal" 
                                                        data-target="#viewModal{{ $ticket->id }}"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <form action="{{ route('ticket.destroy', $ticket->id) }}" 
                                                    method="POST" 
                                                    style="display:inline;" 
                                                    onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Delete Ticket">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- View Modal -->
                                    <div class="modal fade" id="viewModal{{ $ticket->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-ticket-alt mr-2"></i>Ticket Details
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <dl class="row">
                                                                <dt class="col-sm-4">Full Name:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->fullName }}</dd>
                                                                <dt class="col-sm-4">Position:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->position }}</dd>
                                                                <dt class="col-sm-4">Designation:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->designation }}</dd>
                                                                <dt class="col-sm-4">Contact:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->contactNumber }}</dd>
                                                                <dt class="col-sm-4">Email:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->emailAddress }}</dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <dl class="row">
                                                                <dt class="col-sm-4">Office:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->reqOffice }}</dd>
                                                                <dt class="col-sm-4">Reference:</dt>
                                                                <dd class="col-sm-8"><code>{{ $ticket->reference }}</code></dd>
                                                                <dt class="col-sm-4">Authority:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->authority }}</dd>
                                                                <dt class="col-sm-4">Status:</dt>
                                                                <dd class="col-sm-8">
                                                                    @if($status === 'no action')
                                                                        <span class="badge badge-danger">No Action</span>
                                                                    @elseif($status === 'in progress')
                                                                        <span class="badge badge-warning">In Progress</span>
                                                                    @elseif(in_array($status, ['complete', 'completed', 'closed']))
                                                                        <span class="badge badge-success">Completed</span>
                                                                    @else
                                                                        <span class="badge badge-secondary">{{ $ticket->status }}</span>
                                                                    @endif
                                                                </dd>
                                                                <dt class="col-sm-4">Unit Responsible:</dt>
                                                                <dd class="col-sm-8">{{ $ticket->unitResponsible }}</dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-warning">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No tickets found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($tickets, 'links'))
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endsection

<<<<<<< Updated upstream
        @section('css')
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
            <style>
                .small-box .icon {
                    top: 10px;
                    right: 10px;
=======
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <style>
        .small-box .icon {
            top: 10px;
            right: 10px;
        }
        .table-responsive {
            min-height: 400px;
        }
        .filters input {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        .modal-header.bg-info {
            color: white;
        }
        .dataTables_filter {
            display: none;
        }
        .card-outline.card-primary {
            border-top: 3px solid #007bff;
        }
        .badge {
            font-size: 0.8rem;
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
    var table = $('#tickets-table').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: ':not(:last-child)'
>>>>>>> Stashed changes
                }
                .table-responsive {
                    min-height: 400px;
                }
                .filters input {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                }
                .btn-group .btn {
                    margin-right: 2px;
                }
                .btn-group .btn:last-child {
                    margin-right: 0;
                }
                .modal-header.bg-info {
                    color: white;
                }
                /* Row color classes removed as requested */
                .dataTables_filter {
                    display: none;
                }
                .card-outline.card-primary {
                    border-top: 3px solid #007bff;
                }
                .badge {
                    font-size: 0.8rem;
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

<<<<<<< Updated upstream
        <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#tickets-table').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                order: [[0, 'asc']],
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
                    lengthMenu: "Show _MENU_ entries per page",
                    zeroRecords: "No matching records found",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    search: "Search:",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Column search functionality
            $('.column-search').on('keyup change', function() {
                var colIdx = $(this).closest('th').index();
                table.column(colIdx).search(this.value).draw();
            });

            // Global search
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });
=======
    // Remove global search logic

    // Status filter in header
    $('#status-filter-header').on('change', function() {
        var statusValue = this.value;
        // Use regex search for status column to match badge text
        if (statusValue === '') {
            table.column(8).search('', true, false).draw();
        } else if (statusValue === 'complete') {
            // Match any of the completed statuses
            table.column(8).search('Completed', true, false).draw();
        } else if (statusValue === 'no action') {
            table.column(8).search('No Action', true, false).draw();
        } else if (statusValue === 'in progress') {
            table.column(8).search('In Progress', true, false).draw();
        } else {
            table.column(8).search(statusValue, true, false).draw();
        }
    });
>>>>>>> Stashed changes

            // Status filter
            $('#status-filter').on('change', function() {
                var statusValue = this.value;
                table.column(8).search(statusValue).draw();
            });

            // Clear all filters
            $('#clear-filters').on('click', function() {
                $('.column-search').val('');
                $('#global-search').val('');
                $('#status-filter').val('');
                table.search('').columns().search('').draw();
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
        </script>
        @endsection