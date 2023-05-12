@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Customer_Details'] ?? __('Customer Details') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Customers'] ?? __('Customers') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Customer_Details'] ?? __('Customer Details') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <img src="{{ !empty($user->image) ? asset('assets/user/img/users/' . $user->image) : asset('assets/user/img/profile.jpg') }}"
                        alt="" width="100%">
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('user.register-user', ['language' => request('language')]) }}"
                        class="btn float-right btn-primary btn-sm">{{ $keywords['Back'] ?? __('Back') }}</a>
                    <h4 class="card-title">{{ $keywords['Customer_Details'] ?? __('Customer Details') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['User_name'] ?? __('Username') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->username ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['First_Name'] ?? __('First Name') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->first_name ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Last_Name'] ?? __('Last Name') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->last_name ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Email'] ?? __('Email') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->email ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Phone'] ?? __('Phone') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->phone ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['City'] ?? __('City') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->city ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['State'] ?? __('State') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->state ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Country'] ?? __('Country') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->country }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Address'] ?? __('Address') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            {{ $user->address }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Email_Status'] ?? __('Email Status') }}:</strong>
                        </div>
                        <div class="col-lg-6">
                            <form id="emailForm{{ $user->id }}" class="d-inline-block"
                                action="{{ route('register.customer.email') }}" method="post">
                                @csrf
                                <select
                                    class="form-control form-control-sm {{ $user->email_verified_at ? 'bg-success' : 'bg-danger' }}"
                                    name="email_verified"
                                    onchange="document.getElementById('emailForm{{ $user->id }}').submit();">
                                    <option value="1" {{ $user->email_verified_at != null ? 'selected' : '' }}>
                                        {{ $keywords['Verified'] ?? __('Verified') }}</option>
                                    <option value="2" {{ $user->email_verified_at == null ? 'selected' : '' }}>
                                        {{ $keywords['Unverified'] ?? __('Unverified') }}</option>
                                </select>
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <strong>{{ $keywords['Account_Status'] ?? __('Account Status') }}</strong>
                        </div>
                        <div class="col-lg-6">

                            <form id="userFrom{{ $user->id }}" class="d-inline-block"
                                action="{{ route('user.customer.ban') }}" method="post">
                                @csrf
                                <select
                                    class="form-control form-control-sm {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                    name="status"
                                    onchange="document.getElementById('userFrom{{ $user->id }}').submit();">
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
                                        {{ $keywords['Active'] ?? __('Active') }}</option>
                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
                                        {{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                                </select>
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}"
        const mainURL = "{{ url('/') }}";
    </script>
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/post.js') }}"></script>
@endsection
