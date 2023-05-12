@extends('user.layout')
@php
    $selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id]])->first();
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id], ['is_default', 1]])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='userLanguage'] {
                direction: rtl;
            }

            form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['All_Appointments'] ?? __('All Appointments') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['All_Appointments'] ?? __('All Appointments') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="card-title d-inline-block">
                                {{ $keywords['All_Appointments'] ?? __('All Appointments') }}</div>
                        </div>
                        <div class="col-lg-2">
                            <input type="text" id="_slno" class="form-control"
                                value="{{ !empty(request()->input('sl_no')) ? request()->input('sl_no') : '' }}"
                                placeholder="{{ $keywords['search_sl_no'] ?? 'Search SL.No.' }}">
                        </div>
                        <div class="col-lg-2">
                            <input type="text" id="_date" class="form-control datepicker"
                                value="{{ !empty(request()->input('date')) ? request()->input('date') : '' }}"
                                placeholder="{{ $keywords['Search_Date'] ?? 'Search Date' }}">
                        </div>
                        <div class="col-lg-2">
                            <input type="text" id="_tID" class="form-control"
                                value="{{ !empty(request()->input('t_id')) ? request()->input('t_id') : '' }}"
                                placeholder="{{ $keywords['Search_transaction_id'] ?? 'Search Transaction ID' }}">
                        </div>
                        <div class="col-lg-2">
                            <input type="text" id="_name" class="form-control"
                                value="{{ !empty(request()->input('name')) ? request()->input('name') : '' }}"
                                placeholder="{{ $keywords['Search_Name'] ?? 'Search Name' }}">
                        </div>
                        <div class="col-lg-2 text-right">
                            <button id="resetBtn"
                                onclick="document.getElementById('resetSerialForm').submit(); document.getElementById('resetBtnTxt').innerHTML='Resetting ';"
                                class="btn btn-info btn-sm float-right">
                                <i class="fas fa-retweet"></i>
                                <span id="resetBtnTxt">
                                    {{ $keywords['reset'] ?? __('Reset') }}
                                </span>
                                {{ $keywords['serial'] ?? __('Serial') }}
                                <form method="get" id="resetSerialForm" action="{{ route('user.reset-serial-number') }}">
                                </form>
                            </button>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.bulk-delete-appointment') }}">
                                <i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (count($appointments) == 0)
                                <h3 class="text-center">
                                    {{ $keywords['No_Appointment_Found'] ?? __('No Appointment Found') }} !</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['SL_No'] ?? __('SL. No') }} </th>
                                                <th scope="col">
                                                    {{ $keywords['Transaction_Id'] ?? __('Transaction ID') }} </th>
                                                <th scope="col">{{ $keywords['Booking_Date'] ?? __('Booking date') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Time_Slots'] ?? __('Time slots') }} </th>
                                                <th scope="col">{{ $keywords['Category'] ?? __('Category') }} </th>
                                                <th scope="col">
                                                    {{ $keywords['Name'] ?? __('Name') }} </th>
                                                <th scope="col">
                                                    {{ $keywords['Status'] ?? __('Status') }} </th>
                                                <th scope="col">
                                                    {{ $keywords['Payment_status'] ?? __('Payment status') }} </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($appointments as $appointment)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $appointment->id }}">
                                                    </td>
                                                    <td>{{ $appointment->serial_number }}</td>
                                                    <td>{{ $appointment->transaction_id }}</td>
                                                    <td>{{ $appointment->date }}</td>
                                                    <td>{{ $appointment->time }}</td>
                                                    <td>{{ $appointment->category->name ?? '-' }}</td>
                                                    <td>{{ $appointment->name }}</td>
                                                    <td>
                                                        <form id="appointmentStatus{{ $appointment->id }}"
                                                            class="d-inline-block"
                                                            action="{{ route('user.change.appointment.status') }}"
                                                            method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $appointment->status == 2 ? 'bg-success' : ($appointment->status == 3 ? 'bg-primary' : ($appointment->status == 4 ? 'bg-danger' : 'bg-info')) }}"
                                                                name="status"
                                                                onchange="document.getElementById('appointmentStatus{{ $appointment->id }}').submit();">
                                                                <option value="1"
                                                                    {{ $appointment->status == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Pending'] ?? __('Pending') }}</option>
                                                                <option value="2"
                                                                    {{ $appointment->status == 2 ? 'selected' : '' }}>
                                                                    {{ $keywords['Approved'] ?? __('Approved') }}
                                                                </option>
                                                                <option value="3"
                                                                    {{ $appointment->status == 3 ? 'selected' : '' }}>
                                                                    {{ $keywords['Completed'] ?? __('Completed') }}
                                                                </option>
                                                                <option value="4"
                                                                    {{ $appointment->status == 4 ? 'selected' : '' }}>
                                                                    {{ $keywords['Rejected'] ?? __('Rejected') }}
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="appointment_id"
                                                                value="{{ $appointment->id }}">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form id="appointmentPaymentStatus{{ $appointment->id }}"
                                                            class="d-inline-block"
                                                            action="{{ route('user.change.appointment.payment') }}"
                                                            method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $appointment->payment_status == 2 ? 'bg-success' : ($appointment->payment_status == 3 ? 'bg-primary' : ($appointment->payment_status == 4 ? 'bg-danger' : 'bg-info')) }}"
                                                                name="payment_status"
                                                                onchange="document.getElementById('appointmentPaymentStatus{{ $appointment->id }}').submit();">
                                                                <option value="1"
                                                                    {{ $appointment->payment_status == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Pending'] ?? __('Pending') }}
                                                                </option>
                                                                <option value="2"
                                                                    {{ $appointment->payment_status == 2 ? 'selected' : '' }}>
                                                                    {{ $keywords['Paid'] ?? __('Paid') }}
                                                                </option>
                                                                <option value="3"
                                                                    {{ $appointment->payment_status == 3 ? 'selected' : '' }}>
                                                                    {{ $keywords['Advanced'] ?? __('Advanced') }}
                                                                </option>
                                                                @if ($appointment->transaction_details == 'offline')
                                                                    <option value="4"
                                                                        {{ $appointment->payment_status == 4 ? 'selected' : '' }}>
                                                                        {{ $keywords['Rejected'] ?? __('Rejected') }}
                                                                    </option>
                                                                @endif
                                                            </select>
                                                            <input type="hidden" name="appointment_id"
                                                                value="{{ $appointment->id }}">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('user.appointment.view', ['appointment' => $appointment->id]) . '?language=' . request('language') }}"
                                                            type="submit" class="btn btn-info btn-sm ">
                                                            <span class="btn-label">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                            {{ $keywords['Details'] ?? __('Details') }}
                                                        </a>
                                                        <a href="{{ route('user.appointment.edit', ['appointment' => $appointment->id]) . '?language=' . request('language') }}"
                                                            type="submit" class="btn btn-primary btn-sm ">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.appointment.delete', ['appointment' => $appointment->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ $keywords['Delete'] ?? __('Delete') }}
                                                            </button>
                                                        </form>
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
                    <nav class="pagination-nav pull-right {{ $appointments->count() > 15 ? 'mb-4' : '' }}">
                        {{ $appointments->appends(['language' => request()->input('language')])->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form id="searchForm" class="d-none" action="{{ $route . '?language=' . request()->input('language') }}"
        method="get">
        <input type="hidden" id="sl_no" name="sl_no"
            value="{{ !empty(request()->input('sl_no')) ? request()->input('sl_no') : '' }}">
        <input type="hidden" id="date" name="date"
            value="{{ !empty(request()->input('date')) ? request()->input('date') : '' }}">
        <input type="hidden" id="t_id" name="t_id"
            value="{{ !empty(request()->input('t_id')) ? request()->input('t_id') : '' }}">
        <input type="hidden" id="name" name="name"
            value="{{ !empty(request()->input('name')) ? request()->input('name') : '' }}">
        <button id="searchButton" type="submit"></button>
    </form>
@endsection
@section('scripts')
    <script>
        "use strict";
        let sl_no = '';
        let date = '';
        let t_id = '';
        let name = '';
        $(document).on('change', '#_slno', function() {
            sl_no = $(this).val();
            $('#sl_no').val(sl_no);
            $('#searchButton').click();
        })
        $(document).on('change', '#_date', function() {
            date = $(this).val();
            $('#date').val(date);
            $('#searchButton').click();
        })
        $(document).on('change', '#_tID', function() {
            t_id = $(this).val();
            $('#t_id').val(t_id);
            $('#searchButton').click();
        })
        $(document).on('change', '#_name', function() {
            name = $(this).val();
            $('#name').val(name);
            $('#searchButton').click();
        })
    </script>
@endsection
