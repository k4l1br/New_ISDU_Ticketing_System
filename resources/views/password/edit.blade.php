@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Change Password') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                            <input id="current_password" type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" required autocomplete="current-password">
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">{{ __('New Password') }}</label>
                            <input id="new_password" type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" required autocomplete="new-password">
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                            <input id="new_password_confirmation" type="password" 
                                   class="form-control" 
                                   name="new_password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Change Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection