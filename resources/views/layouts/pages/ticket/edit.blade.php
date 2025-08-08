@extends('adminlte::page')

@section('title', 'Edit Ticket')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>
                @if(auth()->user()->isSuperAdmin())
                    Edit Ticket
                @else
                    Update Ticket Status
                @endif
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">
                    @if(auth()->user()->isSuperAdmin())
                        Edit
                    @else
                        Update Status
                    @endif
                </li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0" style="padding-bottom: 0.5rem;">
                    <h3 class="card-title mb-0 font-weight-bold text-primary" style="font-size: 1.25rem;">
                        <i class="fas fa-edit mr-2 text-primary"></i>
                        @if(auth()->user()->isSuperAdmin())
                            Edit Ticket Information
                        @else
                            Update Ticket Status
                        @endif
                    </h3>
                </div>
                <hr class="my-0">
                <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(auth()->user()->isSuperAdmin())
                            <!-- Super Admin sees full form -->
                            <!-- Personal Information Section -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-muted mb-3">
                                        <i class="fas fa-user"></i> Personal Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fullName">Full Name *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" 
                                               name="fullName" 
                                               id="fullName"
                                               class="form-control @error('fullName') is-invalid @enderror" 
                                               value="{{ old('fullName', $ticket->full_name) }}" 
                                               placeholder="Enter full name"
                                               required>
                                        @error('fullName')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Position *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        </div>
                                        <select name="position" 
                                                id="position" 
                                                class="form-control select2 @error('position') is-invalid @enderror" 
                                                required>
                                            <option value="">Select or type position</option>
                                            @if(isset($positions) && count($positions))
                                                @foreach($positions as $position)
                                                    <option value="{{ $position }}" {{ old('position', $ticket->position) == $position ? 'selected' : '' }}>
                                                        {{ $position }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            @if(!in_array(old('position', $ticket->position), $positions ?? []))
                                                <option value="{{ old('position', $ticket->position) }}" selected>{{ old('position', $ticket->position) }}</option>
                                            @endif
                                        </select>
                                        @error('position')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="designation">Designation *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                        </div>
                                        <select name="designation" 
                                                id="designation"
                                                class="form-control select2 @error('designation') is-invalid @enderror" 
                                                required>
                                            <option value="">Select designation</option>
                                            <option value="Civilian Personnel" {{ old('designation', $ticket->designation) == 'Civilian Personnel' ? 'selected' : '' }}>Civilian Personnel</option>
                                            <option value="Military" {{ old('designation', $ticket->designation) == 'Military' ? 'selected' : '' }}>Military</option>
                                        </select>
                                        @error('designation')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactNumber">Contact Number *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" 
                                               name="contactNumber" 
                                               id="contactNumber"
                                               class="form-control @error('contactNumber') is-invalid @enderror" 
                                               value="{{ old('contactNumber', $ticket->contact_number) }}" 
                                               placeholder="Enter contact number"
                                               required>
                                        @error('contactNumber')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emailAddress">Email Address *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" 
                                               name="emailAddress" 
                                               id="emailAddress"
                                               class="form-control @error('emailAddress') is-invalid @enderror" 
                                               value="{{ old('emailAddress', $ticket->email_address) }}" 
                                               placeholder="Enter email address"
                                               required>
                                        @error('emailAddress')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reqOffice">Requesting Office *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <select name="reqOffice" id="reqOffice" class="form-control select2 @error('reqOffice') is-invalid @enderror" required>
                                            <option value="">Select or type office</option>
                                            @foreach($reqOffices as $office)
                                                <option value="{{ $office }}" {{ old('reqOffice', $ticket->req_office) == $office ? 'selected' : '' }}>{{ $office }}</option>
                                            @endforeach
                                            @if(!in_array(old('reqOffice', $ticket->req_office), $reqOffices ?? []))
                                                <option value="{{ old('reqOffice', $ticket->req_office) }}" selected>{{ old('reqOffice', $ticket->req_office) }}</option>
                                            @endif
                                        </select>
                                        @error('reqOffice')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Request Information Section -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-muted mb-3">
                                    <i class="fas fa-clipboard-list"></i> Request Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference">Reference *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        </div>
                                        <select name="reference" 
                                                id="reference"
                                                class="form-control select2 @error('reference') is-invalid @enderror" 
                                                required>
                                            <option value="">Select reference</option>
                                            <option value="Service Directive" {{ old('reference', $ticket->reference) == 'Service Directive' ? 'selected' : '' }}>Service Directive</option>
                                            <option value="Email" {{ old('reference', $ticket->reference) == 'Email' ? 'selected' : '' }}>Email</option>
                                            <option value="Verbal Request" {{ old('reference', $ticket->reference) == 'Verbal Request' ? 'selected' : '' }}>Verbal Request</option>
                                            <option value="Call" {{ old('reference', $ticket->reference) == 'Call' ? 'selected' : '' }}>Call</option>
                                            <option value="Text Message" {{ old('reference', $ticket->reference) == 'Text Message' ? 'selected' : '' }}>Text Message</option>
                                            @if(!in_array(old('reference', $ticket->reference), ['Service Directive','Email','Verbal Request','Call','Text Message']))
                                                <option value="{{ old('reference', $ticket->reference) }}" selected>{{ old('reference', $ticket->reference) }}</option>
                                            @endif
                                        </select>
                                        @error('reference')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="authority">Authority *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                        </div>
                                        <input type="text" 
                                               name="authority" 
                                               id="authority"
                                               class="form-control @error('authority') is-invalid @enderror" 
                                               value="{{ old('authority', $ticket->authority) }}" 
                                               placeholder="Enter authorizing person/department"
                                               required>
                                        @error('authority')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                        </div>
                                        <select name="status" 
                                                id="status"
                                                class="form-control select2 @error('status') is-invalid @enderror" 
                                                required>
                                            <option value="">Select status</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unitResponsible">
                                        Unit Responsible *
                                        @if(auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                                            <small class="text-muted">(Read-only for admin)</small>
                                        @endif
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                        </div>
                                        @if(auth()->user()->isSuperAdmin())
                                            <select name="unitResponsible" 
                                                    id="unitResponsible"
                                                    class="form-control select2 @error('unitResponsible') is-invalid @enderror" 
                                                    required>
                                                <option value="">Select unit</option>
                                                @if(isset($availableUnits) && count($availableUnits))
                                                    @foreach($availableUnits as $unit)
                                                        <option value="{{ $unit }}" {{ old('unitResponsible', $ticket->unit_responsible) == $unit ? 'selected' : '' }}>
                                                            {{ $unit }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <!-- Fallback to static options if no admin users exist yet -->
                                                    <option value="ISDU" {{ old('unitResponsible', $ticket->unit_responsible) == 'ISDU' ? 'selected' : '' }}>ISDU</option>
                                                    <option value="NMU" {{ old('unitResponsible', $ticket->unit_responsible) == 'NMU' ? 'selected' : '' }}>NMU</option>
                                                    <option value="REPAIR" {{ old('unitResponsible', $ticket->unit_responsible) == 'REPAIR' ? 'selected' : '' }}>REPAIR</option>
                                                    <option value="MB" {{ old('unitResponsible', $ticket->unit_responsible) == 'MB' ? 'selected' : '' }}>MB</option>
                                                @endif
                                                <!-- Include current value if it doesn't match available units -->
                                                @if(isset($availableUnits) && !in_array(old('unitResponsible', $ticket->unit_responsible), $availableUnits) && old('unitResponsible', $ticket->unit_responsible))
                                                    <option value="{{ old('unitResponsible', $ticket->unit_responsible) }}" selected>{{ old('unitResponsible', $ticket->unit_responsible) }}</option>
                                                @endif
                                            </select>
                                        @else
                                            <input type="text" 
                                                   name="unitResponsible_display" 
                                                   class="form-control" 
                                                   value="{{ $ticket->unit_responsible }}" 
                                                   readonly
                                                   style="background-color: #f8f9fa;">
                                            <input type="hidden" name="unitResponsible" value="{{ $ticket->unit_responsible }}">
                                        @endif
                                        @error('unitResponsible')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if(auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                                        <small class="text-info">
                                            <i class="fas fa-info-circle"></i> 
                                            Only super admins can change unit assignments
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @else
                            <!-- Admin sees only ticket info and status update -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5><i class="fas fa-info-circle"></i> Ticket Information</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Requestor:</strong> {{ $ticket->full_name }}<br>
                                                <strong>Position:</strong> {{ $ticket->position }}<br>
                                                <strong>Office:</strong> {{ $ticket->req_office }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Contact:</strong> {{ $ticket->contact_number }}<br>
                                                <strong>Email:</strong> {{ $ticket->email_address }}<br>
                                                <strong>Unit:</strong> {{ $ticket->unit_responsible }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Update Section for Admin -->
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <div class="form-group">
                                        <label for="status">Update Status *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                            </div>
                                            <select name="status" 
                                                    id="status"
                                                    class="form-control select2 @error('status') is-invalid @enderror" 
                                                    required>
                                                <option value="">Select status</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status }}" {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            You can only update the ticket status
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> 
                                    @if(auth()->user()->isSuperAdmin())
                                        Update Ticket
                                    @else
                                        Update Status
                                    @endif
                                </button>
                                <a href="{{ auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() ? route('tickets.my') : route('ticket.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                @if(auth()->user()->isSuperAdmin())
                                    <button type="reset" class="btn btn-outline-secondary float-right">
                                        <i class="fas fa-redo"></i> Reset Form
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-primary.card-outline {
            border-top: 3px solid #007bff;
        }
        
        .text-muted {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.5rem;
        }
        
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(2.25rem);
            padding-left: 0.75rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }
        
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        
        .required {
            color: #dc3545;
        }
        
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all dropdowns
            $('select').select2({
                placeholder: function(){
                    return $(this).attr('placeholder') || 'Please select...';
                },
                allowClear: true,
                width: '100%'
            });

            // Make reqOffice a taggable select2
            $('#reqOffice').select2({
                tags: true,
                placeholder: "Select or type office",
                allowClear: true,
                width: '100%'
            });

            // Phone number formatting
            $('#contactNumber').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length <= 3) {
                        $(this).val(value);
                    } else if (value.length <= 6) {
                        $(this).val(value.substring(0, 3) + '-' + value.substring(3));
                    } else {
                        $(this).val(value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6, 10));
                    }
                }
            });

            // Form validation feedback
            $('form').on('submit', function(e) {
                var isValid = true;
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                if (!isValid) {
                    e.preventDefault();
                    toastr.error('Please fill in all required fields.');
                }
            });

            $('input, select').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
@stop