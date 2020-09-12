@extends('admin::layouts.auth')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>KIU</b>ERP</a>
    </div>

    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('Sign in to KIU ERP System') }}</p>
            <form action="{{ route('dashboard.login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('E-Mail Address') }}" value="test@test.com" required autocomplete="email" autofocus>
                    <!-- <input type="email" class="form-control" placeholder="Email"> -->
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" value="test@123" name="password" required autocomplete="current-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <!-- <div class="icheck-primary"> -->
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <!-- </div> -->
                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Sign In') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->
            @if (Route::has('password.request'))
            <p class="mb-1">
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </p>
            @endif
        </div>
    </div>
</div>
@endsection
