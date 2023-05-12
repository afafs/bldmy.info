@extends('admin.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Password') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Profile Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Password') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.updatePassword') }}" method="post" role="form">
                    <div class="card-header">
                        <div class="card-title">{{ __('Update Password') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <div class="form-group">
                                        <label>{{ __('Current Password') }}</label>
                                        <div class="">
                                            <input class="form-control" name="old_password"
                                                placeholder="{{ __('Your Current Password') }}" type="password">
                                            @if ($errors->has('old_password'))
                                                <span class="text-danger">
                                                    {{ $errors->first('old_password') }}
                                                </span>
                                            @else
                                                @if ($errors->first('oldPassMatch'))
                                                    <span class="text-danger">
                                                        {{ __('Old password does not match with the existing password!') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('New Password') }}</label>
                                        <div class="">
                                            <input class="form-control" name="password"
                                                placeholder="{{ __('New Password') }}" type="password">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">
                                                    {{ $errors->first('password') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('New Password Again') }}</label>
                                        <div class="">
                                            <input class="form-control" name="password_confirmation"
                                                placeholder="{{ __('New Password Again') }}" type="password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
