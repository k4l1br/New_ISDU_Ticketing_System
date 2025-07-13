@extends('adminlte::page')

@section('title', 'Edit Position')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Position</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('position.index') }}">Positions</a></li>
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
                        <i class="fas fa-edit mr-2 text-primary"></i>Edit Position
                    </h3>
                </div>
                <hr class="my-0">
                <form action="{{ route('position.update', $position->id) }}" method="POST" autocomplete="off">
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
                            <label for="name">Position Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                </div>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $position->name) }}"
                                       data-original-value="{{ old('name', $position->name) }}"
                                       placeholder="Enter Position Name"
                                       maxlength="255"
                                       required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('position.index') }}" class="btn btn-danger mr-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Position
                            </button>
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
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
@stop

@section('js')
<script>
    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Clear textbox on focus if value matches original
    document.addEventListener('DOMContentLoaded', function() {
        var nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('focus', function() {
                var original = this.getAttribute('data-original-value');
                if (this.value === original) {
                    this.value = '';
                }
            });
        }
    });
</script>
@stop