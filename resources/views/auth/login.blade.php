@extends('layouts.auth_layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class=" col-sm-8 col-md-6">
            <div class="card bg-white">
                <div class="card-body p-4">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 mb-4">
                        <h2 class="fw-bold text-primary">SIGN IN</h2>
                        <h1 class="fw-bold text-muted">ログイン</h1>
                        <p class="text-muted">ご登録のメールアドレスとパスワードをご入力ください。</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="col-form-label fw-bold fs-5">{{ __('メールアドレス ') }}<span
                                    class="text-danger">*</span></label>

                            <div class="">
                                <input id="email" type="email" placeholder="sagami@sample.com"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="col-form-label fw-bold fs-5">{{ __('パスワード（8文字以上）') }}<span
                                    class="text-danger">*</span></label>

                            <div class="">
                                <input id="password" type="password" placeholder="パスワードを入力してください"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary rounded-3 w-100 text-white fs-5">
                                {{ __('ログイン') }}
                            </button>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-center">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection