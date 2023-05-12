@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">
            {{ $keywords['Registered_User'] ?? __('Registered User') }}
        </h4>
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
                <a href="#">{{ $keywords['Registered_User'] ?? __('Registered User') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title">
                                {{ $keywords['Registered_User'] ?? __('Registered User') }}
                            </div>
                        </div>
                        <div class="col-lg-6 mt-2 mt-lg-0">
                            <button class="btn btn-danger float-lg-right float-none btn-sm ml-2 mt-1 d-none bulk-delete"
                                data-href="{{ route('register.customer.bulk.delete') }}"><i
                                    class="flaticon-interface-5"></i>
                                {{ $keywords['Delete'] ?? __('Delete') }}</button>
                            {{-- <button class="btn btn-primary float-lg-right float-none btn-sm ml-2 mt-1" data-toggle="modal"
                                data-target="#addUserModal"><i class="fas fa-plus"></i> {{ __('Add User') }}</button> --}}
                            <form action="{{ url()->full() }}" class="float-lg-right float-none">
                                <input type="text" name="term" class="form-control min-w-250"
                                    value="{{ request()->input('term') }}"
                                    placeholder="{{ $keywords['Search_by_Username_Email'] ?? __('Search by Username / Email') }}">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (!$errors->isEmpty())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (count($users) == 0)
                                <h3 class="text-center">{{ $keywords['NO_USER_FOUND'] ?? __('NO USER FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['User_name'] ?? __('Username') }}</th>
                                                <th scope="col">{{ $keywords['Email'] ?? __('Email') }}</th>
                                                <th scope="col">{{ $keywords['Email_Status'] ?? __('Email Status') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Account'] ?? __('Account') }}</th>
                                                <td scope="col">{{ $keywords['Actions'] ?? __('Action') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $key => $user)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $user->id }}">
                                                    </td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <form id="emailForm{{ $user->id }}" class="d-inline-block"
                                                            action="{{ route('register.customer.email') }}" method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ strtolower($user->email_verified_at) != null ? 'bg-success' : 'bg-danger' }}"
                                                                name="email_verified"
                                                                onchange="document.getElementById('emailForm{{ $user->id }}').submit();">
                                                                <option value="1"
                                                                    {{ $user->email_verified_at != null ? 'selected' : '' }}>
                                                                    {{ $keywords['Verified'] ?? __('Verified') }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ $user->email_verified_at == null ? 'selected' : '' }}>
                                                                    {{ $keywords['Unverified'] ?? __('Unverified') }}
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user->id }}">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form id="userFrom{{ $user->id }}" class="d-inline-block"
                                                            action="{{ route('user.customer.ban') }}" method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                                                name="status"
                                                                onchange="document.getElementById('userFrom{{ $user->id }}').submit();">
                                                                <option value="1"
                                                                    {{ $user->status == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Active'] ?? __('Active') }}</option>
                                                                <option value="0"
                                                                    {{ $user->status == 0 ? 'selected' : '' }}>
                                                                    {{ $keywords['Deactive'] ?? __('Deactive') }}
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user->id }}">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-info btn-sm dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ $keywords['Actions'] ?? __('Actions') }}
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('register.customer.view', $user->id) . '?language=' . request('language') }}">{{ $keywords['Details'] ?? __('Details') }}</a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('register.customer.changePass', $user->id) . '?language=' . request('language') }}">{{ $keywords['Change_Password'] ?? __('Change Password') }}</a>
                                                                <form class="deleteform d-block"
                                                                    action="{{ route('register.customer.delete') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id"
                                                                        value="{{ $user->id }}">
                                                                    <button type="submit" class="deletebtn dropdown-item">
                                                                        {{ $keywords['Delete'] ?? __('Delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-inline-block mx-auto">
                            {{ $users->appends(['term' => request()->input('term')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_User'] ?? __('Add User') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('register.user.store') }}" method="POST" id="ajaxForm">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ $keywords['User_name'] ?? __('Username') }} *</label>
                            <input class="form-control" type="text" name="username">
                            <p id="errusername" class="text-danger mb-0 em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Email'] ?? __('Email') }} *</label>
                            <input class="form-control" type="email" name="email">
                            <p id="erremail" class="text-danger mb-0 em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Password'] ?? __('Password') }} *</label>
                            <input class="form-control" type="password" name="password">
                            <p id="errpassword" class="text-danger mb-0 em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Confirm_Password'] ?? __('Confirm Password') }} *</label>
                            <input class="form-control" type="password" name="password_confirmation">
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Package_or_Plan'] ?? __('Package / Plan') }} *</label>
                            <select name="package_id" class="form-control">
                                @if (!empty($packages))
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->title }}
                                            ({{ $package->term }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p id="errpackage_id" class="text-danger mb-0 em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Payment_Gateways'] ?? __('Payment Gateway') }} *</label>
                            <select name="payment_gateway" class="form-control">
                                @if (!empty($gateways))
                                    @foreach ($gateways as $gateway)
                                        <option value="{{ $gateway->name }}">{{ $gateway->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p id="errpayment_gateway" class="text-danger mb-0 em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Publicly_Hidden'] ?? __('Publicly Hidden') }} *</label>
                            <select name="online_status" class="form-control">
                                <option value="1">{{ $keywords['No'] ?? __('No') }}</option>
                                <option value="0">{{ $keywords['Yes'] ?? __('Yes') }}</option>
                            </select>
                            <p id="erronline_status" class="text-danger mb-0 em"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer text-center">
                    <button id="" data-form="ajaxForm" type="button"
                        class="submitBtn btn btn-primary">{{ $keywords['Add_User'] ?? __('Add User') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".template-select").on('change', function() {
                let userId = $(this).data('user_id');
                let val = $(this).val();
                if (val == 1) {
                    $("#templateModal" + userId).modal('show');
                }
                $(`#templateModal${userId} input[name='template']`).val(val);
                if (val == 0) {
                    $(`#templateForm${userId}`).trigger('submit');
                }
            });
        });
    </script>
@endsection
