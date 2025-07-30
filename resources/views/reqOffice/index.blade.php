@extends('adminlte::page')

@section('title', 'Requesting Offices')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Requesting Offices</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Requesting Offices</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary card-outline shadow-sm h-100">
        <div class="card-header bg-white border-bottom-0">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="card-title mb-0 font-weight-bold text-primary" style="font-size: 1.25rem;">
                    <i class="fas fa-list mr-1"></i> Requesting Offices List
                </h3>
                <a href="{{ route('reqOffice.create') }}" class="btn btn-primary btn-sm ml-auto">
                    <i class="fas fa-plus mr-1"></i> Add Office
                </a>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body p-3">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Requesting Office</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offices as $office)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $office->reqOffice }}</td>
                            <td>
                                <a href="{{ route('reqOffice.edit', $office->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('reqOffice.destroy', $office->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No requesting offices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
