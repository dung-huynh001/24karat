@extends('layouts.app')
@extends('layouts.breadcrumb')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{ __('You are logged in!') }}
            </div>
        </div>
    </div>
</div>
@endsection