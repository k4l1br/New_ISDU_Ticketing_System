        @extends('adminlte::page')

        @section('title', 'Tickets Management')

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
        <!-- Hidden input to control pagination on the server -->
        <form method="get" id="pagination-control" style="display:none;">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <input type="hidden" name="page" value="{{ request('page', 1) }}">
        </form>
        <div class="container-fluid px-0"> 
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-lg mr-3"></i>
                        <div>
                            <strong>Success!</strong> {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Statistics Cards removed as requested -->

            <!-- Main Content Card -->
            <div class="card card-primary card-outline">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title d-flex align-items-center">
                        <i class="fas fa-ticket-alt text-primary mr-2"></i>
                        Tickets Management
                    </h3>
                    <div class="card-tools">
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('ticket.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                Create New Ticket
                            </a>
                        @else
                            <span class="badge badge-info badge-pill">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ $tickets->count() }} tickets assigned to your unit
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status-filter">
                                    <i class="fas fa-tasks text-muted"></i> Status:
                                </label>
                                <select id="status-filter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="no action">No Action</option>
                                    <option value="in progress">In Progress</option>
                                    <option value="complete">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date-range-filter">
                                    <i class="fas fa-calendar text-muted"></i> Date Range:
                                </label>
                                <select id="date-range-filter" class="form-control">
                                    <option value="">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <div class="btn-group w-100">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-download mr-1"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" id="export-excel">
                                            <i class="fas fa-file-excel text-success mr-2"></i> Excel
                                        </a>
                                        <a class="dropdown-item" href="#" id="export-pdf">
                                            <i class="fas fa-file-pdf text-danger mr-2"></i> PDF
                                        </a>
                                        <a class="dropdown-item" href="#" id="export-print">
                                            <i class="fas fa-print text-primary mr-2"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mt-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" id="global-search" class="form-control" placeholder="Search across all fields...">
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Table -->
                    <div class="table-responsive">
                        <table id="tickets-table" class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Requesting Office</th>
                                    <th>Reference</th>
                                    <th>Authority</th>
                                    <th>Status</th>
                                    <th>Unit Responsible</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Office..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Reference..."></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Authority..."></th>
                                    <th><!-- Status: no search --></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Unit..."></th>
                                    <th><!-- Actions: no search --></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    @php
                                        $status = strtolower(trim($ticket->status));
                                    @endphp
                                    <tr>
                                        <td>{{ $ticket->req_office }}</td>
                                        <td>
                                            <code class="bg-light p-1 rounded">{{ $ticket->reference }}</code>
                                        </td>
                                        <td>{{ $ticket->authority }}</td>
                                        <td>
                                            @if($status === 'no action')
                                                <span class="badge badge-danger badge-pill">
                                                    <i class="fas fa-times-circle mr-1"></i>No Action
                                                </span>
                                            @elseif($status === 'in progress')
                                                <span class="badge badge-warning badge-pill">
                                                    <i class="fas fa-clock mr-1"></i>In Progress
                                                </span>
                                            @elseif(in_array($status, ['complete', 'completed', 'closed']))
                                                <span class="badge badge-success badge-pill">
                                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                                </span>
                                            @else
                                                <span class="badge badge-secondary badge-pill">
                                                    <i class="fas fa-question-circle mr-1"></i>{{ $ticket->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $ticket->unit_responsible }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                @if(auth()->user()->isSuperAdmin())
                                                    <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-warning" title="Edit Ticket">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewModal{{ $ticket->id }}" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if(auth()->user()->isSuperAdmin())
                                                <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete Ticket">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Professional View Modal -->
                                    <div class="modal fade" id="viewModal{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $ticket->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content shadow-lg border-0">
                                                <!-- Modern Header with Gradient -->
                                                <div class="modal-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                                                    <div class="d-flex align-items-center">
                                                        <div class="modal-icon-wrapper bg-white bg-opacity-20 rounded-circle p-2 mr-3">
                                                            <i class="fas fa-ticket-alt fa-lg"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="modal-title mb-0" id="viewModalLabel{{ $ticket->id }}">
                                                                Ticket Details
                                                            </h4>
                                                            <small class="text-white-50">Reference: {{ $ticket->reference }}</small>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body p-0">
                                                    <!-- Status Banner -->
                                                    <div class="alert alert-{{ $status === 'complete' || $status === 'completed' ? 'success' : ($status === 'in progress' ? 'warning' : 'danger') }} mb-0 rounded-0 border-0">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-{{ $status === 'complete' || $status === 'completed' ? 'check-circle' : ($status === 'in progress' ? 'clock' : 'exclamation-triangle') }} mr-2"></i>
                                                                <strong>Current Status: </strong>
                                                                <span class="ml-2 badge badge-{{ $status === 'complete' || $status === 'completed' ? 'success' : ($status === 'in progress' ? 'warning' : 'danger') }} badge-pill">
                                                                    {{ ucfirst($ticket->status) }}
                                                                </span>
                                                            </div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                Last updated: {{ $ticket->updated_at ? $ticket->updated_at->diffForHumans() : 'N/A' }}
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="p-4">
                                                        <!-- Personal Information Card -->
                                                        <div class="card border-0 shadow-sm mb-4">
                                                            <div class="card-header bg-light border-0 py-3">
                                                                <h6 class="card-title mb-0 font-weight-bold text-primary">
                                                                    <i class="fas fa-user-circle mr-2"></i>Personal Information
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Full Name</label>
                                                                            <div class="info-value h6">{{ $ticket->full_name }}</div>
                                                                        </div>
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Position</label>
                                                                            <div class="info-value">
                                                                                <span class="badge badge-secondary badge-pill">{{ $ticket->position }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Designation</label>
                                                                            <div class="info-value">
                                                                                <span class="badge badge-info badge-pill">{{ $ticket->designation }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Contact Number</label>
                                                                            <div class="info-value">
                                                                                <a href="tel:{{ $ticket->contact_number }}" class="text-decoration-none">
                                                                                    <i class="fas fa-phone-alt text-success mr-2"></i>{{ $ticket->contact_number }}
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Email Address</label>
                                                                            <div class="info-value">
                                                                                <a href="mailto:{{ $ticket->email_address }}" class="text-decoration-none">
                                                                                    <i class="fas fa-envelope text-primary mr-2"></i>{{ $ticket->email_address }}
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Requesting Office</label>
                                                                            <div class="info-value">
                                                                                <span class="badge badge-dark badge-pill">
                                                                                    <i class="fas fa-building mr-1"></i>{{ $ticket->req_office }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Request Details Card -->
                                                        <div class="card border-0 shadow-sm mb-4">
                                                            <div class="card-header bg-light border-0 py-3">
                                                                <h6 class="card-title mb-0 font-weight-bold text-success">
                                                                    <i class="fas fa-clipboard-list mr-2"></i>Request Details
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Reference Number</label>
                                                                            <div class="info-value">
                                                                                <code class="bg-light text-dark p-2 rounded">{{ $ticket->reference }}</code>
                                                                            </div>
                                                                        </div>
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Authority</label>
                                                                            <div class="info-value">
                                                                                <i class="fas fa-user-shield text-warning mr-2"></i>{{ $ticket->authority }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Unit Responsible</label>
                                                                            <div class="info-value">
                                                                                <span class="badge badge-primary badge-pill">
                                                                                    <i class="fas fa-users mr-1"></i>{{ $ticket->unit_responsible }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="info-item mb-3">
                                                                            <label class="info-label text-muted small font-weight-bold text-uppercase">Created Date</label>
                                                                            <div class="info-value text-muted">
                                                                                <i class="fas fa-calendar-plus mr-2"></i>{{ $ticket->created_at ? $ticket->created_at->format('M d, Y h:i A') : 'N/A' }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if($ticket->description)
                                                        <!-- Description Card -->
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-light border-0 py-3">
                                                                <h6 class="card-title mb-0 font-weight-bold text-info">
                                                                    <i class="fas fa-file-alt mr-2"></i>Description
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="mb-0 text-justify">{{ $ticket->description }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Modern Footer -->
                                                <div class="modal-footer bg-light border-0 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        <small>Ticket ID: #{{ $ticket->id }}</small>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                                            <i class="fas fa-times mr-1"></i>Close
                                                        </button>
                                                        @if(auth()->user()->isSuperAdmin())
                                                            <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-warning btn-sm ml-2">
                                                                <i class="fas fa-edit mr-1"></i>Edit Ticket
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-primary btn-sm ml-2">
                                                            <i class="fas fa-external-link-alt mr-1"></i>Full View
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                                                <h5 class="text-muted mb-2">No Tickets Found</h5>
                                                <p class="text-muted mb-3">There are currently no tickets to display.</p>
                                                @if(auth()->user()->isSuperAdmin())
                                                    <a href="{{ route('ticket.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus mr-1"></i> Create New Ticket
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- DataTable handles pagination - Laravel pagination removed -->
                    
                </div>
            </div>
        </div>
        @endsection

        @section('css')
            <link rel="stylesheet" href="{{ asset('assets/css/datatables.bootstrap4.min.css') }}">
            <link rel="stylesheet" href="{{ asset('assets/css/datatables.buttons.bootstrap4.min.css') }}">
            <style>
                /* General improvements */
                body {
                    background-color: #f4f6f9;
                }
                
                /* Card styling */
                .card {
                    border-radius: 0.5rem;
                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                    margin-bottom: 1.5rem;
                    border: none;
                    transition: all 0.2s ease-in-out;
                }
                
                .card:hover {
                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                }
                
                .card-header {
                    background-color: #fff;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                    padding: 0.75rem 1.25rem;
                }
                
                .card-title {
                    font-weight: 600;
                    margin-bottom: 0;
                    color: #343a40;
                }
                
                /* Table styling */
                .table-responsive {
                    min-height: 400px;
                    border-radius: 0.25rem;
                    overflow: hidden;
                }
                
                .table {
                    margin-bottom: 0;
                    background-color: #fff;
                }
                
                .table thead th {
                    font-weight: 600;
                    border-top: none;
                    background-color: #f8f9fa;
                    color: #495057 !important;
                    text-transform: uppercase;
                    font-size: 0.8rem;
                    letter-spacing: 0.5px;
                }
                
                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: rgba(0, 0, 0, 0.02);
                }
                
                .filters input {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    font-size: 0.85rem;
                    padding: 0.5rem;
                    border-radius: 0.25rem;
                    transition: all 0.2s;
                }
                
                .filters input:focus {
                    border-color: #80bdff;
                    outline: 0;
                    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
                }
                
                /* Button styling */
                .btn-group .btn {
                    margin-right: 0.25rem;
                    border-radius: 0.25rem;
                    transition: all 0.2s;
                }
                
                .btn-group .btn:last-child {
                    margin-right: 0;
                }
                
                .btn-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.875rem;
                    border-radius: 0.2rem;
                }
                
                /* Search and filter controls */
                .form-control-sm {
                    height: calc(1.5em + 0.5rem + 2px);
                    padding: 0.25rem 0.5rem;
                    font-size: 0.875rem;
                    line-height: 1.5;
                    border-radius: 0.2rem;
                }
                
                label {
                    color: #495057;
                    font-weight: 500;
                    font-size: 0.875rem;
                }
                
                /* Hide DataTables default search */
                .dataTables_filter {
                    display: none;
                }
                .dataTables_length, 
                .dataTables_paginate,
                .dataTables_info,
                .paging_simple_numbers,
                .pagination,
                nav .pagination,
                .d-flex .pagination,
                [aria-label="Pagination Navigation"],
                .page-item,
                .paginate_button,
                .dataTables_wrapper .row:last-child,
                .dataTables_wrapper > .row:nth-last-child(2) {
                    display: none !important;
                    visibility: hidden !important;
                    opacity: 0 !important;
                    height: 0 !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    overflow: hidden !important;
                }
                
                /* Card styling */
                .card-outline.card-primary {
                    border-top: 3px solid #007bff;
                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                }
                
                /* Badge styling */
                .badge {
                    font-size: 0.75rem;
                    padding: 0.4em 0.8em;
                    font-weight: 500;
                    letter-spacing: 0.3px;
                }
                
                .badge-pill {
                    border-radius: 50rem;
                    padding-right: 0.8em;
                    padding-left: 0.8em;
                }
                
                .badge-success {
                    background-color: #28a745;
                    color: white;
                }
                
                .badge-warning {
                    background-color: #ffc107;
                    color: #212529;
                }
                
                .badge-danger {
                    background-color: #dc3545;
                    color: white;
                }
                
                .badge-info {
                    background-color: #17a2b8;
                    color: white;
                }
                
                /* Alert styling */
                .alert {
                    border-radius: 0.25rem;
                    border: none;
                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                }
                
                .alert-success {
                    background-color: #d4edda;
                    color: #155724;
                }
                
                /* Button styling */
                .btn {
                    border-radius: 0.25rem;
                    font-weight: 500;
                    letter-spacing: 0.3px;
                    transition: all 0.2s;
                }
                
                .btn-primary {
                    background-color: #007bff;
                    border-color: #007bff;
                }
                
                .btn-primary:hover {
                    background-color: #0069d9;
                    border-color: #0062cc;
                }
                
                .btn-outline-secondary {
                    color: #6c757d;
                    border-color: #6c757d;
                }
                
                .btn-outline-secondary:hover {
                    color: #fff;
                    background-color: #6c757d;
                    border-color: #6c757d;
                }
                
                /* Input group styling */
                .input-group-text {
                    border: none;
                }
                
                /* Animation for alerts */
                .alert-dismissible {
                    animation: fadeIn 0.5s;
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                /* Empty state styling */
                .text-center td {
                    padding: 3rem 1rem !important;
                }
                
                /* Hover effect for table rows */
                .table-hover tbody tr:hover {
                    background-color: rgba(0, 123, 255, 0.05);
                    transition: background-color 0.2s;
                }
                
                /* Code styling */
                code {
                    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                    font-size: 87.5%;
                    color: #212529;
                    background-color: #f8f9fa;
                    padding: 0.2rem 0.4rem;
                    border-radius: 0.2rem;
                }
                
                /* Professional Modal Styles */
                .modal-xl {
                    max-width: 1140px;
                }
                
                .modal-icon-wrapper {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .bg-gradient-primary {
                    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
                }
                
                .bg-opacity-20 {
                    background-color: rgba(255, 255, 255, 0.2) !important;
                }
                
                .info-item {
                    position: relative;
                    padding-left: 0;
                }
                
                .info-label {
                    display: block;
                    margin-bottom: 4px;
                    font-size: 0.75rem;
                    letter-spacing: 0.5px;
                }
                
                .info-value {
                    font-size: 0.95rem;
                    line-height: 1.4;
                    font-weight: 500;
                }
                
                .modal-content {
                    border-radius: 15px;
                    overflow: hidden;
                }
                
                .modal-header {
                    border-bottom: none;
                    padding: 1.5rem 2rem;
                }
                
                .modal-footer {
                    border-top: 1px solid #e9ecef;
                    padding: 1rem 2rem;
                }
                
                .card {
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }
                
                .card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
                }
                
                .badge-pill {
                    padding: 0.5rem 1rem;
                    font-size: 0.8rem;
                    font-weight: 500;
                }
                
                .text-white-50 {
                    color: rgba(255, 255, 255, 0.5) !important;
                }
                
                .shadow-lg {
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
                }
                
                .text-justify {
                    text-align: justify;
                }
                
                /* Status Alert Styling */
                .alert {
                    border: none;
                    font-weight: 500;
                }
                
                /* Button Styling */
                .btn-sm {
                    font-size: 0.8rem;
                    padding: 0.4rem 0.8rem;
                    border-radius: 20px;
                }
                
                /* Smooth Animations */
                .modal.fade .modal-dialog {
                    transition: transform 0.4s ease-out;
                    transform: translate(0, -50px);
                }
                
                .modal.show .modal-dialog {
                    transform: none;
                }
                
                /* Custom scrollbar for modal */
                .modal-body::-webkit-scrollbar {
                    width: 6px;
                }
                
                .modal-body::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 3px;
                }
                
                .modal-body::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 3px;
                }
                
                .modal-body::-webkit-scrollbar-thumb:hover {
                    background: #a8a8a8;
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
            // Submit the form to handle server-side pagination
            $('#pagination-control').submit();
            
            // Initialize DataTable
            var table = $('#tickets-table').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: parseInt("{{ request('per_page', 10) }}"), // Use the selected per_page value
                order: [[0, 'asc']],
                dom: 'Blfrtip', // Add pagination controls back
                lengthChange: true,
                info: true,
                paging: true, // Enable paging
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm d-none excel-button',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm d-none pdf-button',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm d-none print-button',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                language: {
                    zeroRecords: "No matching records found",
                    search: "Search:"
                }
            });
            
            // Handle custom export buttons
            $('#export-excel').on('click', function(e) {
                e.preventDefault();
                $('.excel-button').click();
            });
            
            $('#export-pdf').on('click', function(e) {
                e.preventDefault();
                $('.pdf-button').click();
            });
            
            $('#export-print').on('click', function(e) {
                e.preventDefault();
                $('.print-button').click();
            });

            // Style pagination elements
            function stylePaginationElements() {
                $('.dataTables_paginate').addClass('pagination-sm');
                $('.pagination').addClass('pagination-sm');
                $('.page-item').addClass('me-1');
                
                // Make DataTables pagination work well with Laravel pagination
                if ($('nav').has('.pagination').length > 0) {
                    $('.dataTables_paginate, .dataTables_info').hide();
                }
            }
            
            // Run styling
            stylePaginationElements();
            
            // Then run again after a short delay to catch any that might be added dynamically
            setTimeout(stylePaginationElements, 100);
            setTimeout(stylePaginationElements, 500);
            
            // Also watch for DOM changes and apply styling
            const observer = new MutationObserver(function(mutations) {
                stylePaginationElements();
            });
            
            // Watch the entire document for changes
            observer.observe(document.body, { childList: true, subtree: true });
            
            // Add CSS to enhance pagination styles
            $('<style>' + 
              '.pagination { margin-bottom: 0; }' + 
              '.page-item.active .page-link { background-color: #007bff; border-color: #007bff; }' + 
              '.page-link { color: #007bff; padding: 0.4rem 0.75rem; }' + 
              '.page-link:hover { color: #0056b3; background-color: #e9ecef; }' + 
              '.dataTables_info { margin-top: 10px; font-size: 0.875rem; }' + 
              '</style>').appendTo('head');

            // Column search functionality
            $('.column-search').on('keyup change', function() {
                var colIdx = $(this).closest('th').index();
                table.column(colIdx).search(this.value).draw();
            });

            // Global search
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Status filter
            $('#status-filter').on('change', function() {
                var statusValue = this.value;
                // Apply status filter - no clear option available now
                var searchTerm = '';
                switch(statusValue) {
                    case 'no action':
                        searchTerm = 'No Action';
                        break;
                    case 'in progress':
                        searchTerm = 'In Progress';
                        break;
                    case 'complete':
                        searchTerm = 'Completed';
                        break;
                    default:
                        searchTerm = statusValue;
                }
                table.column(3).search(searchTerm).draw();
            });
            
            // Date range filter
            $('#date-range-filter').on('change', function() {
                // Date range filtering would be handled here
                // For now, we'll just redraw the table
                table.draw();
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
        </script>
        @endsection