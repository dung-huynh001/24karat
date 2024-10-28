@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<style>
    .pageLoader {
        background: url(../images/loader.gif) no-repeat center center;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 9999999;
        background-color: #ffffff8c;

    }
</style>

<div class="container-fluid">
    <form id="managerForm" action="/manager/store" method="POST" class="py-3">
        @csrf
        <div class="mb-3 row">
            <label for="subscription_user" class="col-sm-2 col-form-label">契約ユーザー <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <select name="subscription_user" class="form-select" aria-label="契約ユーザー">
                    <option value="" disabled selected>--選択--</option>
                    @foreach ($subscriptionUsers as $subscriptionUser)
                        <option value="{{ $subscriptionUser['subscription_user_id'] }}" {{ old('subscription_user') == $subscriptionUser['subscription_user_id'] ? 'selected' : '' }}>
                            {{ $subscriptionUser['company_name'] }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('subscription_user'))
                    @foreach ($errors->get('subscription_user') as $subscriptionUserErrors)
                        @if (is_array($subscriptionUserErrors))
                            @foreach ($subscriptionUserErrors as $msg)
                                <div class="text-sunset-orange error-messages">{{$msg}}</div>
                            @endforeach
                        @else
                            <div class="text-sunset-orange error-messages">{{$subscriptionUserErrors}}</div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">名前 <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" autocomplete="off">
                @error('name')
                    <div class="text-sunset-orange error-messages">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">メールアドレス <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input name="email" class="form-control" placeholder="email@example.com" autocomplete="off"
                    value="{{ old('email') }}">
                @if ($errors->has('email'))
                    @foreach ($errors->get('email') as $emailErrors)
                        @if (is_array($emailErrors))
                            @foreach ($emailErrors as $msg)
                                <div class="text-sunset-orange error-messages">{{$msg}}</div>
                            @endforeach
                        @else
                            <div class="text-sunset-orange error-messages">{{$emailErrors}}</div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">新しいパスワード <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="password" name="password" class="form-control" autocomplete="off">
                @if ($errors->has('password'))
                    @foreach ($errors->get('password') as $passwordErrors)
                        @if (is_array($passwordErrors))
                            @foreach ($passwordErrors as $msg)
                                <div class="text-sunset-orange error-messages">{{$msg}}</div>
                            @endforeach
                        @else
                            <div class="text-sunset-orange error-messages">{{$passwordErrors}}</div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mb-3 row">
            <label for="confirm_password" class="col-sm-2 col-form-label">パスワード（確認）<span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="password" name="confirm_password" class="form-control" autocomplete="off">
                @if ($errors->has('confirm_password'))
                    @foreach ($errors->get('confirm_password') as $confirmPasswordErrors)
                        @if (is_array($confirmPasswordErrors))
                            @foreach ($confirmPasswordErrors as $msg)
                                <div class="text-sunset-orange error-messages">{{$msg}}</div>
                            @endforeach
                        @else
                            <div class="text-sunset-orange error-messages">{{$confirmPasswordErrors}}</div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <a href="{{route('manager.list')}}" type="button" class="btn btn-lavender d-flex align-items-center">
                <span class="me-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-pen p-0 m-0"></i>
                </span>
                <span>＜戻る</span>
            </a>
            <button type="submit" class="btn btn-royal-blue d-flex align-items-center">
                <span>登録</span>
                <span class="ms-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-trash p-0 m-0"></i>
                </span>
            </button>
        </div>
    </form>
</div>

@endsection