@extends('adminlte::page')

@section('title', 'Create Ticket')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Create New Ticket</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">Create</li>
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
                        <i class="fas fa-plus mr-2 text-primary"></i>Ticket Information
                    </h3>
                    <!-- Removed Back to List button -->
                </div>
                <hr class="my-0">
                <form action="{{ route('ticket.store') }}" method="POST">
                    @csrf
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
                                    <label for="fullName">
                                        Requesting Personnel *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" 
                                               name="fullName" 
                                               id="fullName"
                                               class="form-control @error('fullName') is-invalid @enderror" 
                                               value="{{ old('fullName') }}" 
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
                                    <label for="position">
                                        Position *
                                    </label>
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
                                                        <option value="{{ $position }}" {{ old('position') == $position ? 'selected' : '' }}>
                                                            {{ $position }}
                                                        </option>
                                                    @endforeach
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
                                    <label for="designation">
                                        Designation *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                        </div>
                                        <select name="designation" 
                                                id="designation"
                                                class="form-control select2 @error('designation') is-invalid @enderror" 
                                                required>
                                            <option value="">Select designation</option>
                                            <option value="Civilian Personnel" {{ old('designation') == 'Civilian Personnel' ? 'selected' : '' }}>Civilian Personnel</option>
                                            <option value="Military" {{ old('designation') == 'Military' ? 'selected' : '' }}>Military</option>
                                        </select>
                                        @error('designation')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactNumber">
                                        Contact Number *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" 
                                               name="contactNumber" 
                                               id="contactNumber"
                                               class="form-control @error('contactNumber') is-invalid @enderror" 
                                               value="{{ old('contactNumber') }}" 
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
                                    <label for="emailAddress">
                                        Email Address *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" 
                                               name="emailAddress" 
                                               id="emailAddress"
                                               class="form-control @error('emailAddress') is-invalid @enderror" 
                                               value="{{ old('emailAddress') }}" 
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
                                    <label for="reqOffice">
                                        Requesting Office *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <select name="reqOffice" id="reqOffice" class="form-control select2 @error('reqOffice') is-invalid @enderror" required>
                                            <option value="">Select or type office</option>
                                            @foreach($reqOffices as $office)
                                                <option value="{{ $office }}" {{ old('reqOffice') == $office ? 'selected' : '' }}>{{ $office }}</option>
                                            @endforeach
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
                                    <label for="reference">
                                        Reference *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        </div>
                                       <select name="reference" 
                                         id="reference"
                                         class="form-control select2 @error('reference') is-invalid @enderror" 
                                         required>
                                         <option value="">Select reference</option>
                                         @foreach($references as $ref)
                                         <option value="{{ $ref }}" {{ old('reference') == $ref ? 'selected' : '' }}>{{ $ref }}</option>
                                         @endforeach
                                         </select>
                                        @error('reference')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="authority">
                                        Authority *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                        </div>
                                        <input type="text" 
                                               name="authority" 
                                               id="authority"
                                               class="form-control @error('authority') is-invalid @enderror" 
                                               value="{{ old('authority') }}" 
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
                                    <label for="status">
                                        Status *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                        </div>
                                        <input type="text" class="form-control" value="In Progress" disabled>
                                        <input type="hidden" name="status" value="In Progress">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unitResponsible">
                                        Assign to Admin *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                        </div>
                                        <select name="unitResponsible" 
                                                id="unitResponsible"
                                                class="form-control select2 @error('unitResponsible') is-invalid @enderror" 
                                                required>
                                            <option value="">Select admin user</option>
                                            
                                            @if(isset($adminUsers) && count($adminUsers))
                                                @foreach($adminUsers as $admin)
                                                    <option value="{{ $admin['name'] }}" {{ old('unitResponsible') == $admin['name'] ? 'selected' : '' }}>
                                                        {{ $admin['name'] }}{{ $admin['unit'] ? ' (' . $admin['unit'] . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option disabled>No admin users available</option>
                                            @endif
                                        </select>
                                        @error('unitResponsible')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">
                                        Task Description *
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                        </div>
                                        <textarea name="description" 
                                                  id="description"
                                                  class="form-control @error('description') is-invalid @enderror" 
                                                  rows="4"
                                                  placeholder="Describe what needs to be done for this ticket. Include specific requirements, deadlines, and any relevant details..."
                                                  required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Provide a clear and detailed description of the task or request. This will help the assigned admin understand what needs to be accomplished.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Ticket
                                </button>
                                <a href="{{ route('ticket.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="reset" class="btn btn-outline-secondary float-right">
                                    <i class="fas fa-redo"></i> Reset Form
                                </button>
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
            
            // Show/hide text input if custom value is typed
            $('#reqOffice').on('change', function() {
                var val = $(this).val();
                var exists = $(this).find('option[value="' + val + '"]').length > 0;
                if (!exists && val) {
                    $('#reqOffice_other').val(val).removeClass('d-none').attr('required', true);
                } else {
                    $('#reqOffice_other').addClass('d-none').attr('required', false).val('');
                }
            });
            
            // If old value was custom, show input
            var initialVal = $('#reqOffice').val();
            var existsInit = $('#reqOffice').find('option[value="' + initialVal + '"]').length > 0;
            if (!existsInit && initialVal) {
                $('#reqOffice_other').val(initialVal).removeClass('d-none').attr('required', true);
            }
            
            // Form validation feedback
            $('form').on('submit', function(e) {
                var isValid = true;
                
                // Check required fields
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
            
            // Remove validation errors on input
            $('input, select').on('input change', function() {
                $(this).removeClass('is-invalid');
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
            
            // Success message if form was submitted successfully
        });
    </script>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
@stop