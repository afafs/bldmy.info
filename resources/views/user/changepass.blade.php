@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Password'] ?? __('Password') }}</h4>
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
                <a href="#">{{ $keywords['Profile_Settings'] ?? __('Profile Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Password'] ?? __('Password') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('user.updatePassword') }}" method="post" role="form">
                    <div class="card-header">
                        <div class="card-title">{{ $keywords['Update_Password'] ?? __('Update Password') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                {{ csrf_field() }}
                                <div class="form-body">
                                   
                                    <div class="form-group">
                                        <label>{{ $keywords['New_Password'] ?? __('New Password') }}</label>
                                        <div class="">
                                            <input class="form-control" name="password"
                                                placeholder="{{ $keywords['New_Password'] ?? __('New Password') }}"
                                                type="password">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">
                                                    {{ $errors->first('password') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ $keywords['New_Password_Again'] ?? __('New Password Again') }}</label>
                                        <div class="">
                                            <input class="form-control" name="password_confirmation"
                                                placeholder="{{ $keywords['New_Password_Again'] ?? __('New Password Again') }}"
                                                type="password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit"
                                    class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
