@extends('adminlte::page')

@section('title', 'My Assigned Tickets')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">My Assigned Tickets</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">My Tickets</li>
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

    <!-- Tickets Table Card -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list mr-2"></i>
                My Assigned Tickets
            </h3>
            <div class="card-tools">
                <span class="badge badge-info">{{ $tickets->count() }} tickets</span>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Filters -->
            <div class="p-3 border-bottom">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status-filter" class="form-label">Filter by Status:</label>
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
                            <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <strong>{{ $ticket->full_name }}</strong>
                                </td>
                                <td>{{ $ticket->position }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $ticket->designation }}</span>
                                </td>
                                <td>
                                    <a href="tel:{{ $ticket->contact_number }}" class="text-decoration-none">
                                        <i class="fas fa-phone mr-1"></i>{{ $ticket->contact_number }}
                                    </a>
                                </td>
                                <td>
                                    <a href="mailto:{{ $ticket->email_address }}" class="text-decoration-none">
                                        <i class="fas fa-envelope mr-1"></i>{{ $ticket->email_address }}
                                    </a>
                                </td>
                                <td>{{ $ticket->req_office }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $ticket->reference }}</span>
                                </td>
                                <td>{{ $ticket->authority }}</td>
                                <td>
                                    @if($ticket->status === 'no action')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i>No Action
                                        </span>
                                    @elseif($ticket->status === 'in progress')
                                        <span class="badge badge-primary">
                                            <i class="fas fa-spinner mr-1"></i>In Progress
                                        </span>
                                    @elseif($ticket->status === 'complete')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Completed
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">{{ $ticket->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(auth()->user()->isSuperAdmin())
                                            <a href="{{ route('ticket.edit', $ticket) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit Ticket">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p class="mb-0">No tickets assigned to your unit yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .table-responsive {
        border-radius: 0;
    }
    
    .badge {
        font-size: 0.8em;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .text-decoration-none {
        text-decoration: none !important;
    }
    
    .text-decoration-none:hover {
        text-decoration: underline !important;
    }
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#tickets-table').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        language: {
            search: "Search tickets:",
            lengthMenu: "Show _MENU_ tickets per page",
            info: "Showing _START_ to _END_ of _TOTAL_ tickets",
            infoEmpty: "No tickets found",
            infoFiltered: "(filtered from _MAX_ total tickets)"
        }
    });

    // Status filter
    $('#status-filter').on('change', function() {
        const status = this.value;
        if (status === '') {
            // Clear status filter
            table.column(8).search('').draw();
        } else {
            // Map filter values to display text for better matching
            let searchTerm = '';
            switch(status) {
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
                    searchTerm = status;
            }
            table.column(8).search(searchTerm).draw();
        }
    });
});
</script>
@endsection
