@extends('adminlte::page')

@section('title', 'Add Requesting Office')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Add Requesting Office</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reqOffice.index') }}">Requesting Offices</a></li>
                <li class="breadcrumb-item active">Add</li>
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
                        <i class="fas fa-plus mr-2 text-primary"></i>Add Requesting Office
                    </h3>
                
                </div>
                <hr class="my-0">
                <form action="{{ route('reqOffice.store') }}" method="POST">
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
                        <div class="form-group">
                            <label for="reqOffice">Requesting Office Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input type="text" name="reqOffice" class="form-control @error('reqOffice') is-invalid @enderror" id="reqOffice" value="{{ old('reqOffice') }}" placeholder="Enter office name (e.g. CEISSAFP)" required>
                                @error('reqOffice')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('reqOffice.index') }}" class="btn btn-danger mr-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create
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
