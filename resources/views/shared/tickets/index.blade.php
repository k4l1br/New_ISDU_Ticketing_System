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
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('ticket.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Create New Ticket
                    </a>
                @else
                    <span class="badge badge-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $tickets->count() }} tickets assigned to your unit
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- Advanced Search and Filter Section -->
            <form method="GET" action="{{ route('ticket.index') }}" class="mb-3">
                <div class="card card-body bg-light border-0">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">
                                    <i class="fas fa-tasks text-muted"></i> Status:
                                </label>
                                <select id="status" name="status" class="form-control form-control-sm auto-submit">
                                    <option value="">All Status</option>
                                    <option value="no action" {{ request('status') == 'no action' ? 'selected' : '' }}>No Action</option>
                                    <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_range">
                                    <i class="fas fa-calendar text-muted"></i> Date Range:
                                </label>
                                <select id="date_range" name="date_range" class="form-control form-control-sm auto-submit">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <a href="{{ route('ticket.index') }}" class="btn btn-secondary btn-sm w-100">
                                        <i class="fas fa-times"></i> Clear Filters
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Responsive Table -->
            <div class="table-responsive">
                <table id="tickets-table" class="table table-bordered table-striped table-hover" style="color: #000;">
                    <thead>
                        <tr>
                            <th style="color: #000;">Requesting Office</th>
                            <th>Reference</th>
                            <th>Authority</th>
                            <th>Status</th>
                            <th>Unit Responsible</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <th>
                                <input type="text" class="form-control form-control-sm column-search" 
                                    placeholder="Search office..." data-column="0">
                            </th>
                            <th>
                                <input type="text" class="form-control form-control-sm column-search" 
                                    placeholder="Search reference..." data-column="1">
                            </th>
                            <th>
                                <input type="text" class="form-control form-control-sm column-search" 
                                    placeholder="Search authority..." data-column="2">
                            </th>
                            <th></th>
                            <th>
                                <input type="text" class="form-control form-control-sm column-search" 
                                    placeholder="Search unit..." data-column="4">
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            @php
                                $status = strtolower(trim($ticket->status));
                            @endphp
                            <tr>
                                <td style="color: #000;">{{ $ticket->req_office }}</td>
                                <td>
                                    <code style="color: #000;">{{ $ticket->reference }}</code>
                                </td>
                                <td>{{ $ticket->authority }}</td>
                                <td style="color: #000;">
                                    @if($status === 'no action')
                                        No Action
                                    @elseif($status === 'in progress')
                                        In Progress
                                    @elseif(in_array($status, ['complete', 'completed', 'closed']))
                                        Completed
                                    @else
                                        {{ ucfirst($ticket->status) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $ticket->unit_responsible }}
                                </td>
                                <td>
                                    {{ $ticket->created_at ? $ticket->created_at->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if(auth()->user()->isSuperAdmin())
                                            <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-warning" title="Edit Ticket">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewModal{{ $ticket->id }}" title="Quick View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if(auth()->user()->isSuperAdmin())
                                        <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete Ticket">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- AdminLTE Style Modal -->
                            <div class="modal fade" id="viewModal{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $ticket->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <!-- Modal Header with AdminLTE style -->
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title">
                                                <i class="fas fa-ticket-alt"></i> Ticket Information
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" class="text-white">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Status Section -->
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    @php
                                                        $statusInfo = [
                                                            'no action' => [
                                                                'color' => 'danger',
                                                                'icon' => 'exclamation-circle',
                                                                'text' => 'No Action'
                                                            ],
                                                            'in progress' => [
                                                                'color' => 'orange',
                                                                'icon' => 'clock',
                                                                'text' => 'In Progress'
                                                            ],
                                                            'completed' => [
                                                                'color' => 'success',
                                                                'icon' => 'check-circle',
                                                                'text' => 'Completed'
                                                            ]
                                                        ];
                                                        
                                                        $currentStatus = strtolower($ticket->status);
                                                        if (in_array($currentStatus, ['complete', 'closed'])) {
                                                            $currentStatus = 'completed';
                                                        }
                                                        
                                                        $status = $statusInfo[$currentStatus] ?? [
                                                            'color' => 'secondary',
                                                            'icon' => 'question-circle',
                                                            'text' => ucfirst($currentStatus)
                                                        ];
                                                    @endphp
                                                    <div class="info-box bg-{{ $status['color'] }}">
                                                        <span class="info-box-icon">
                                                            <i class="fas fa-{{ $status['icon'] }}"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-white">Current Status</span>
                                                            <span class="info-box-number text-white font-weight-bold">{{ $status['text'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Main Content -->
                                            <div class="row">
                                                <!-- Personal Information -->
                                                <div class="col-md-6">
                                                    <div class="card card-outline card-primary h-100">
                                                        <div class="card-header">
                                                            <h3 class="card-title">
                                                                <i class="fas fa-user-circle"></i> Personal Information
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <dl class="row">
                                                                <dt class="col-sm-4">Full Name</dt>
                                                                <dd class="col-sm-8">{{ $ticket->full_name }}</dd>
                                                                
                                                                <dt class="col-sm-4">Position</dt>
                                                                <dd class="col-sm-8">{{ $ticket->position }}</dd>
                                                                
                                                                <dt class="col-sm-4">Designation</dt>
                                                                <dd class="col-sm-8">{{ $ticket->designation }}</dd>
                                                                
                                                                <dt class="col-sm-4">Contact</dt>
                                                                <dd class="col-sm-8">
                                                                    <div>Phone: {{ $ticket->contact_number }}</div>
                                                                    <div>Email: {{ $ticket->email_address }}</div>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Request Details -->
                                                <div class="col-md-6">
                                                    <div class="card card-outline card-success h-100">
                                                        <div class="card-header">
                                                            <h3 class="card-title">
                                                                <i class="fas fa-clipboard-list"></i> Request Details
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <dl class="row">
                                                                <dt class="col-sm-4">Office</dt>
                                                                <dd class="col-sm-8">{{ $ticket->req_office }}</dd>
                                                                
                                                                <dt class="col-sm-4">Reference</dt>
                                                                <dd class="col-sm-8">{{ $ticket->reference }}</dd>
                                                                
                                                                <dt class="col-sm-4">Authority</dt>
                                                                <dd class="col-sm-8">{{ $ticket->authority }}</dd>
                                                                
                                                                <dt class="col-sm-4">Unit</dt>
                                                                <dd class="col-sm-8">{{ $ticket->unit_responsible }}</dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($ticket->description)
                                            <!-- Description Section -->
                                            <div class="card card-outline card-info mt-4">
                                                <div class="card-header">
                                                    <h3 class="card-title">
                                                        <i class="fas fa-align-left"></i> Description
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    {{ $ticket->description }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Close
                                            </button>
                                            @if(auth()->user()->isSuperAdmin())
                                                <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Edit Ticket
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="color: #000;">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No tickets found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Results Info and Pagination Controls -->
            <div class="row align-items-center mt-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 mr-2">Show</label>
                        <form method="GET" class="form-inline mb-0">
                            @foreach(request()->except('per_page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                        <label class="mb-0 ml-2">entries</label>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    @if(method_exists($tickets, 'total'))
                        <div class="dataTables_info">
                            <small class="text-muted">
                                Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} 
                                of {{ $tickets->total() }} entries
                                @if(request('search'))
                                    (filtered)
                                @endif
                            </small>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(method_exists($tickets, 'links'))
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm justify-content-end mb-0">
                                {{-- Previous Page Link --}}
                                @if ($tickets->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tickets->previousPageUrl() }}" rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $tickets->lastPage(); $i++)
                                    <li class="page-item {{ ($tickets->currentPage() == $i) ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $tickets->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($tickets->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tickets->nextPageUrl() }}" rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif
                    <!-- DataTables pagination will also appear here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/datatables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables.buttons.bootstrap4.min.css') }}">
<style>
    /* Table Styles */
    .table-responsive {
        min-height: 400px;
        border-radius: .25rem;
        box-shadow: 0 0 15px rgba(0,0,0,.05);
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: rgba(0,123,255,.05);
    }

    /* Status Badge Styles */
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }

    /* Button Styles */
    .btn-group .btn {
        margin-right: 2px;
        border-radius: 3px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Filter Card Styles */
    .card-body.bg-light {
        background-color: #f8f9fa;
        border-radius: .25rem;
    }

    /* Form Control Styles */
    .form-control-sm {
        border-radius: .2rem;
    }

    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .page-link {
        color: #007bff;
    }
    
    .page-link:hover {
        color: #0056b3;
    }

    /* Additional DataTables styling */
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    .page-link {
        padding: .4rem .7rem;
        font-size: .875rem;
        color: #007bff;
        border: 1px solid #dee2e6;
    }

    .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #0056b3;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
    }

    /* AdminLTE Modal Styles */
    .modal-content {
        border-radius: 0.3rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
        padding: 0.5rem;
        min-height: 80px;
        background: #fff;
    }

    .bg-orange {
        background-color: #fd7e14 !important;
    }

    .info-box.bg-success,
    .info-box.bg-danger,
    .info-box.bg-orange {
        color: #fff !important;
    }

    .info-box.bg-success .info-box-content *,
    .info-box.bg-danger .info-box-content *,
    .info-box.bg-orange .info-box-content * {
        color: #fff !important;
    }

    .info-box.bg-success .progress-description,
    .info-box.bg-danger .progress-description,
    .info-box.bg-orange .progress-description {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .info-box.bg-success .info-box-icon,
    .info-box.bg-danger .info-box-icon,
    .info-box.bg-orange .info-box-icon {
        color: #fff !important;
    }

    .info-box-icon {
        border-radius: 0.25rem;
        display: block;
        width: 70px;
        text-align: center;
        font-size: 30px;
        float: left;
    }

    .info-box-content {
        padding: 5px 10px;
        margin-left: 70px;
    }

    .info-box-number {
        display: block;
        font-weight: 700;
    }

    .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .progress-description {
        display: block;
        font-size: 13px;
    }

    .card-outline {
        border-top: 3px solid #007bff;
    }

    .card-outline.card-success {
        border-top-color: #28a745;
    }

    .card-outline.card-info {
        border-top-color: #17a2b8;
    }

    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 0.75rem 1.25rem;
    }

    .card-title {
        float: left;
        font-size: 1.1rem;
        font-weight: 400;
        margin: 0;
    }

    .badge {
        font-size: 85%;
        font-weight: 500;
    }

    .badge-info {
        color: #fff;
        background-color: #17a2b8;
    }

    dl.row {
        margin-bottom: 0;
    }

    dt {
        font-weight: 500;
        color: #6c757d;
    }

    dd {
        margin-bottom: .5rem;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        justify-content: space-between;
        padding: 1rem;
    }

    .btn-default {
        background-color: #f8f9fa;
        border-color: #ddd;
        color: #444;
    }

    .btn-default:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
        color: #2b3035;
    }
    
    .modal-header.bg-info {
        color: white;
    }
    
    /* Column Search Styles */
    .column-search {
        width: 100%;
        padding: 4px 8px;
        font-size: 0.875rem;
        margin-top: 5px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    
    .column-search:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    /* Table Header Styles */
    #tickets-table thead tr:first-child th {
        border-bottom: 1px solid #dee2e6;
    }
    
    #tickets-table thead tr:last-child th {
        padding: 4px;
        border-top: none;
    }
    
    /* DataTables Overrides */
    .dataTables_filter {
        display: none;
    }
    
    .card-outline.card-primary {
        border-top: 3px solid #007bff;
    }
    
    .badge {
        font-size: 0.8rem;
    }
    
    /* DataTables Buttons Styling */
    .dt-buttons {
        margin-bottom: 15px;
    }
    
    .dt-buttons .btn {
        margin-right: 5px;
        font-size: 0.875rem;
        border-radius: 3px;
    }
    
    .dt-buttons .btn i {
        margin-right: 5px;
    }
    
    /* Ensure cards are visible */ 
    .modal .card { 
        background-color: white !important; 
        border: 1px solid #dee2e6 !important; 
        margin-bottom: 1rem !important; 
    } 
    
    /* Fix card headers */ 
    .modal .card-header { 
        background-color: #f8f9fa !important; 
        color: #212529 !important; 
        border-bottom: 1px solid #dee2e6 !important; 
    } 
    
    /* Ensure text is visible */ 
    .modal .card-body { 
        color: #212529 !important; 
        background-color: white !important; 
    } 
    
    /* Fix info items */ 
    .modal .info-item { 
        margin-bottom: 1rem !important; 
        padding: 0.5rem 0 !important; 
        border-bottom: 1px solid #f1f3f4 !important; 
    } 
    
    .modal .info-label { 
        display: block !important; 
        font-weight: 600 !important; 
        color: #6c757d !important; 
        margin-bottom: 0.25rem !important; 
    } 
    
    .modal .info-value { 
        display: block !important; 
        color: #212529 !important; 
    } 
    
    /* Fix status banner */ 
    .modal .status-banner { 
        display: block !important; 
        padding: 1rem !important; 
        margin-bottom: 0 !important; 
        border: none !important; 
        border-radius: 0 !important; 
    } 
    
    /* Ensure responsive layout works */ 
    @media (max-width: 768px) { 
        .modal-xl { 
            max-width: 95% !important; 
            margin: 10px auto !important; 
        } 
        
        .modal .col-md-6 { 
            margin-bottom: 1rem !important; 
        }
    }
</style>
@endsection

@section('js')
<!-- Use AdminLTE's built-in jQuery -->
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
    // Auto-submit form when filter selects change
    $('.auto-submit').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // Initialize DataTable with pagination
    var table = $('#tickets-table').DataTable({
        dom: 'Bfrtip', // Removed 'l' to hide default length menu
        buttons: [
            
            {
                extend: 'excel',
                className: 'btn-success',
                text: '<i class="fas fa-file-excel"></i> Excel'
            },
            {
                extend: 'pdf',
                className: 'btn-danger',
                text: '<i class="fas fa-file-pdf"></i> PDF'
            },
            {
                extend: 'print',
                className: 'btn-info',
                text: '<i class="fas fa-print"></i> Print'
            }
        ],
        pageLength: 10, // Show 10 records per page
        paging: true,
        info: true,
        lengthChange: true,
        order: [[5, 'desc']], // Sort by Created Date by default
        searching: true
    });

    // Apply the search for each column
    $('.column-search').on('keyup change', function() {
        var columnIndex = $(this).data('column');
        table
            .column(columnIndex)
            .search(this.value)
            .draw();
    });

    // Style improvements for DataTables
    $('.dataTables_wrapper .dataTables_filter').hide(); // Hide default search as we have our own
    
    // Hide DataTables pagination when Laravel pagination is available
    if ($('.pagination').length > 0) {
        $('.dataTables_paginate').hide();
        $('.dataTables_info').hide();
    } else {
        // Add styling to pagination elements
        $('.dataTables_paginate').addClass('pagination-sm');
    }
    
    // Add custom styles for pagination
    $('head').append('<style>' + 
        '.dataTables_paginate { margin-top: 10px; }' +
        '.paginate_button { padding: 0.25rem 0.5rem; border-radius: 0.2rem; }' +
        '.paginate_button.current { background-color: #007bff; color: white; }' +
        '.dataTables_info { margin-top: 10px; font-size: 0.875rem; color: #6c757d; }' +
        '.dataTables_length { margin-right: 20px; font-size: 0.875rem; }' +
        '.dataTables_length select { margin: 0 5px; padding: 2px; border-radius: 3px; }' +
    '</style>');
        
    // Keep other style improvements
    $('.dataTables_wrapper .row').addClass('g-3');
});
</script>
@endsection
