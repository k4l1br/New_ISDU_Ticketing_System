@extends('adminlte::page')

@section('title', 'Edit Office')

@section('adminlte_css_pre')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Office</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.offices.index') }}">Offices</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-white border-bottom-0 d-flex align-items-center" style="padding-bottom: 0.5rem;">
                    <h3 class="card-title mb-0 font-weight-bold text-primary" style="font-size: 1.25rem;">
                        <i class="fas fa-edit mr-2 text-primary"></i>Edit Office: {{ $office->name }}
                    </h3>
                </div>
                <hr class="my-0">
                <form method="POST" action="{{ route('admin.offices.update', $office) }}">
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

                        <div class="form-group">
                            <label for="name">Office Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $office->name) }}" required placeholder="Enter office name">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="abbreviation">Office Abbreviation <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" name="abbreviation" id="abbreviation" class="form-control @error('abbreviation') is-invalid @enderror" 
                                       value="{{ old('abbreviation', $office->abbreviation) }}" required placeholder="Enter office abbreviation (e.g., IT, HR, ADMIN)">
                                @error('abbreviation')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-1"></i> Update Office
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.offices.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </a>
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
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #6c757d;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .custom-control-label {
            font-weight: 500;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Set up CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Focus on first input
            $('#name').focus();
            
            // Auto-hide alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Refresh CSRF token every 5 minutes to prevent 419 errors
            setInterval(function() {
                $.get('/csrf-token').done(function(data) {
                    $('input[name="_token"]').val(data.csrf_token);
                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                });
            }, 300000); // 5 minutes
        });
    </script>
@stop
