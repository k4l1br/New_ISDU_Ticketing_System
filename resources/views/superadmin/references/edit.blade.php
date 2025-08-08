@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Reference</h2>
    <form action="{{ route('references.update', $reference->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="reference_type">Reference Type</label>
            <input type="text" name="reference_type" value="{{ $reference->reference_type }}" class="form-control" required>
        </div>
        <button class="btn btn-primary mt-2">Update</button>
        <a href="{{ route('references.index') }}" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
@endsection
