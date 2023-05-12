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
        <h4 class="page-title">{{ $keywords['appointment_details'] ?? __('Appointment Details') }}</h4>
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
                <a
                    href="{{ route('user.bookedAppointment', ['language' => request('language')]) }}">{{ $keywords['Appointments'] ?? __('Appointments') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['appointment_details'] ?? __('Appointment Details') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-title d-inline-block">
                            {{ $keywords['appointment_details'] ?? __('Appointment Details') }}</div>
                    </div>
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0 text-right">
                        <a class="btn btn-sm btn-primary"
                            href="{{ route('user.bookedAppointment', ['language' => request('language')]) }}"> <i
                                class="fa fa-backword"></i> {{ $keywords['Back'] ?? __('Back') }}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-lg-12 text-center">
                                    <div class="card-title d-inline-block">
                                        {{ $keywords['Appointment'] ?? __('Appointment') }}</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-condensed mt-3">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ $keywords['SL_No'] ?? __('SL.No') }} </th>
                                                    <th scope="col">
                                                        {{ $keywords['Booking_Date'] ?? __('Booking Date') }}
                                                    </th>
                                                    <th scope="col">
                                                        {{ $keywords['Booking_Day'] ?? __('Booking Day') }}
                                                    </th>
                                                    <th scope="col">
                                                        {{ $keywords['Booking_Time'] ?? __('Booking Time') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $appointment->serial_number }}</td>
                                                    <td>{{ $appointment->date }}</td>
                                                    <td>
                                                        @php
                                                            $date = Carbon\Carbon::parse($appointment->date);
                                                        @endphp
                                                        {{ $date->format('l') }}
                                                    </td>
                                                    <td>{{ $appointment->time }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="col-lg-12 text-center">
                                    <div class="card-title d-inline-block">
                                        {{ $keywords['Customer_Details'] ?? __('Customer Details') }}</div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-condensed mt-3">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ $keywords['Name'] ?? __('Name') }} </th>
                                                    <td>{{ $appointment->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">{{ $keywords['Email'] ?? __('Email') }} </th>
                                                    <td>{{ $appointment->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">{{ $keywords['Phone'] ?? __('Phone') }} </th>
                                                    <td>{{ $appointment->customer->contact_number ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">{{ $keywords['City'] ?? __('City') }} </th>
                                                    <td>{{ $appointment->customer->city ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">{{ $keywords['State'] ?? __('State') }} </th>
                                                    <td>{{ $appointment->customer->state ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">{{ $keywords['Address'] ?? __('Address') }} </th>
                                                    <td>{{ $appointment->customer->address ?? '-' }}</td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-lg-12 text-center">
                                    <div class="card-title d-inline-block">
                                        {{ $keywords['appointment_details'] ?? __('Appointment Details') }}</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        @php
                                            $dtls = json_decode($appointment->details);
                                        @endphp
                                        <table class="table table-striped table-condensed mt-3">
                                            <thead>
                                                <tr>
                                                    <th>{{ $keywords['Category'] ?? __('Category') }}</th>
                                                    <td>{{ $appointment->category->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ $keywords['Status'] ?? __('Status') }}</th>
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
                                                </tr>
                                                <tr>
                                                    <th>{{ $keywords['Payment_status'] ?? __('Payment status') }}</th>
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
                                                </tr>
                                                <tr>
                                                    <th>{{ $keywords['Total_Fee'] ?? __('Total Fee') }}</th>
                                                    <td>
                                                        {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                        {{ $appointment->total_amount }}
                                                        {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ $keywords['Paid_Fee'] ?? __('Paid Fee') }}</th>
                                                    <td>
                                                        {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                        {{ $appointment->amount }}
                                                        {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                        ({{ $keywords['Payment_method'] ?? __('Payment method') }} :
                                                        {{ $appointment->payment_method }})
                                                    </td>
                                                </tr>
                                                @if (!empty($appointment->due_amount))
                                                    <tr>
                                                        <th>{{ $keywords['Due_amount'] ?? __('Due amount') }}</th>
                                                        <td>
                                                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                            {{ $appointment->due_amount }}
                                                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}

                                                        </td>
                                                    </tr>
                                                @endif
                                                
                                                @foreach ($dtls as $key => $details)
                                                    <tr>
                                                        <th scope="col">{{ str_replace('_', ' ', ucwords($key)) }} </th>
                                                        <td>
                                                            @if (!empty($details->type))
                                                                @if ($details->type == 5)
                                                                    <a class="badge badge-primary"
                                                                        href="{{ asset('assets/front/files/appointment/' . $details->value) }}"
                                                                        download=""><i class="fa fa-download"></i>
                                                                        {{ $keywords['Download'] ?? __('Download') }}
                                                                        {{ str_replace('_', ' ', $key) }}</a>
                                                                @elseif($details->type == 4)
                                                                    {{ Str::limit($details->value, 80, $end = '.....') }}
                                                                    @if (strlen($details->value) > 80)
                                                                        <i class="fa fa-eye text-info" title="see more"
                                                                            data-toggle="modal"
                                                                            data-target="#exampleModal"></i>
                                                                        <!-- Modal -->
                                                                        <div class="modal fade" id="exampleModal"
                                                                            tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="exampleModalLabel">
                                                                                            {{ str_replace('_', ' ', $key) }}
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                            <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        {{ $details->value }}
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-secondary"
                                                                                            data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @elseif($details->type == 3)
                                                                    @foreach ($details->value as $v)
                                                                        {{ ucwords($v) }}
                                                                        @if (!$loop->last)
                                                                            ,
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    {{ $details->value }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">

                    </div>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
@endsection
