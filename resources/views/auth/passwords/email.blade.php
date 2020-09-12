@extends('layouts.slave')

@section('content')
<div class="login-box">

    <div class="login-logo">
            <b>KIU</b>ERP
    </div>

            <div class="card card-outline card-primary">
                <div class="card-header"><h3 class="card-title float-none text-center">{{ __('Reset Password') }}</h3></div>

                <div class="card-body login-card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope "></span>
                                    </div>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

  
                                <button type="submit" class="btn btn-block btn-flat btn-primary">
                                    <span class="fas fa-share-square"></span>
                                    {{ __('Send Password Reset Link') }}
                                </button>
                        
                    </form>
            </div>
        </div>
@endsection