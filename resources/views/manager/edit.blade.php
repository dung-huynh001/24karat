@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<div class="container-fluid">
    <form action="" method="POST" class="py-3">
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">契約ユーザー <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <select class="form-select" aria-label="契約ユーザー" required>
                    <option value="" disabled selected>--選択--</option>
                    @foreach ($subscriptionUsers as $subscriptionUser)
                        <option value="{{$subscriptionUser['subscription_user_id']}}"
                            @if($manager['subscription_user_id'] == $subscriptionUser['subscription_user_id']) selected
                            @endif>
                            {{$subscriptionUser['company_name']}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">名前 <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required value="{{$manager['name']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">メールアドレス <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="email" class="form-control" placeholder="email@example.com" required disabled tabindex="-1"
                    value="{{$manager['email']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="chkChangePassword">
                    <label class="form-check-label" for="chkChangePassword">
                        パスワードを変更する
                    </label>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="newPassword" class="col-sm-2 col-form-label">新しいパスワード <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="newPassword" disabled>
                <div class="d-flex flex-column text-sunset-orange fs-7">
                    <span>新しいパスワードの文字数は、8文字以上である必要があります。</span>
                    <span>パスワードは最低1桁数を含む必要があります。</span>
                    <span>パスワードは最低1特殊文字を含む必要があります。特殊文字 （ !@#$%^&*-_+=）</span>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="confirmPassword" class="col-sm-2 col-form-label">パスワード（確認）<span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="confirmPassword" disabled>
                <div class="d-flex flex-column text-sunset-orange fs-7">
                    <span>パスワード（確認）の文字数は、8文字以上である必要があります。</span>
                    <span>パスワード（確認）と新しいパスワードが一致しません。</span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{route("manager.list")}}" type="button" class="btn btn-lavender d-flex align-items-center shadow-sm">
                <span class="me-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-circle-chevron-left p-0 m-0"></i>
                </span>
                <span>＜戻る</span>
            </a>
            <button type="submit" class="btn btn-royal-blue d-flex align-items-center shadow-sm">
                <span>登録</span>
                <span class="ms-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-floppy-disk p-0 m-0"></i>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    $('#chkChangePassword').on('change', (event) => {
        const chkChangePassword = event.target;
        const newPasswordInput = $('#newPassword');
        const confirmPasswordInput = $('#confirmPassword');
        if (chkChangePassword.checked) {
            newPasswordInput.removeAttr('disabled');
            confirmPasswordInput.removeAttr('disabled');
        } else {
            newPasswordInput.val("");
            confirmPasswordInput.val("");
            newPasswordInput.attr('disabled', true);
            confirmPasswordInput.attr('disabled', true);
        };
    });
</script>
@endsection