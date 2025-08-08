@extends('adminlte::page')

@section('title', 'View Ticket')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Ticket Details</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">View</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-ticket-alt mr-2"></i>
                Ticket Information
            </h3>
            <div class="card-tools">
                <span class="badge badge-{{ $ticket->status === 'complete' ? 'success' : ($ticket->status === 'in progress' ? 'primary' : 'warning') }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-user mr-2"></i>Personal Information
                    </h5>
                    
                    <dl class="row">
                        <dt class="col-sm-4">Full Name:</dt>
                        <dd class="col-sm-8">{{ $ticket->full_name }}</dd>
                        
                        <dt class="col-sm-4">Position:</dt>
                        <dd class="col-sm-8">{{ $ticket->position }}</dd>
                        
                        <dt class="col-sm-4">Designation:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-secondary">{{ $ticket->designation }}</span>
                        </dd>
                        
                        <dt class="col-sm-4">Contact Number:</dt>
                        <dd class="col-sm-8">
                            <a href="tel:{{ $ticket->contact_number }}" class="text-decoration-none">
                                <i class="fas fa-phone mr-1"></i>{{ $ticket->contact_number }}
                            </a>
                        </dd>
                        
                        <dt class="col-sm-4">Email Address:</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:{{ $ticket->email_address }}" class="text-decoration-none">
                                <i class="fas fa-envelope mr-1"></i>{{ $ticket->email_address }}
                            </a>
                        </dd>
                    </dl>
                </div>

                <!-- Ticket Information -->
                <div class="col-md-6">
                    <h5 class="text-success mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Ticket Information
                    </h5>
                    
                    <dl class="row">
                        <dt class="col-sm-4">Requesting Office:</dt>
                        <dd class="col-sm-8">{{ $ticket->req_office }}</dd>
                        
                        <dt class="col-sm-4">Reference:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-info">{{ $ticket->reference }}</span>
                        </dd>
                        
                        <dt class="col-sm-4">Authority:</dt>
                        <dd class="col-sm-8">{{ $ticket->authority }}</dd>
                        
                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
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
                        </dd>
                        
                        <dt class="col-sm-4">Unit Responsible:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-dark">{{ $ticket->unit_responsible }}</span>
                        </dd>
                    </dl>
                </div>
            </div>

            <hr class="my-4">

            <!-- Timestamps -->
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-info mb-3">
                        <i class="fas fa-clock mr-2"></i>Timestamps
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Created:</strong> {{ $ticket->created_at ? $ticket->created_at->format('M d, Y h:i A') : 'N/A' }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Last Updated:</strong> {{ $ticket->updated_at ? $ticket->updated_at->format('M d, Y h:i A') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-12">
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('ticket.edit', $ticket) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Ticket
                        </a>
                    @endif
                    
                    <a href="{{ auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() ? route('tickets.my') : route('ticket.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Tickets
                    </a>
                    
                    @if(auth()->user()->isSuperAdmin())
                        <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> Delete Ticket
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isSuperAdmin())
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this ticket? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('ticket.destroy', $ticket) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('css')
<style>
    .text-decoration-none {
        text-decoration: none !important;
    }
    
    .text-decoration-none:hover {
        text-decoration: underline !important;
    }
    
    .badge {
        font-size: 0.85em;
    }
</style>
@endsection
