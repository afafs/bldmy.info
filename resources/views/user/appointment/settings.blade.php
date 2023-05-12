@extends('user.layout')
@if (!empty($input->language) && $input->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            .nicEdit-main {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Appointment_Settings'] ?? __('Appointment Settings') }} </h4>
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
                    href="{{ route('user.appointment.setting', ['language' => request('language')]) }}">{{ $keywords['Settings'] ?? __('Settings') }}</a>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    <div class="col-lg-6">
                        {{ $keywords['Settings'] ?? __('Settings') }}
                    </div>
                     <div class="col-lg-6 ">
                     
                    </div>
                   
                </div>
                 <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6 ">
                           
                        </div>
                    </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 ">
                       <button id="resetBtn"
                        onclick="document.getElementById('resetSerialForm').submit(); document.getElementById('resetBtnTxt').innerHTML='Resetting ';"
                        class="btn btn-info btn-sm ">
                        <i class="fas fa-retweet"></i>
                        <span id="resetBtnTxt">
                            {{ $keywords['reset'] ?? __('Reset') }}
                        </span>
                        {{ $keywords['serial'] ?? __('Serial') }}
                        <form method="get" id="resetSerialForm" action="{{ route('user.reset-serial-number') }}">
                        </form>
                    </button>
                     <p class="text-warning ">If you click the reset serial button, the serial number of the appointment will start from the beginning</p>
                </div>
            </div>
            <div class="row" id="app">
                
                <div class="col-lg-6 offset-lg-3">
                    <form id="ajaxForm" action="{{ route('user.appointment.category.control') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" id="cat" value="{{ $userBs->appointment_category }}">
                            <label>{{ $keywords['Appointment_Category_is_Enabled'] ?? __('Appointment Category is Enabled') }}
                                ? </label>
                            <div class="selectgroup w-100">

                                <label class="selectgroup-item">
                                    <input type="radio" name="appointment_category" value="1"
                                        class="selectgroup-input appointment_category"
                                        {{ $userBs->appointment_category == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Yes'] ?? __('Yes') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="appointment_category" value="0"
                                        class="selectgroup-input appointment_category"
                                        {{ $userBs->appointment_category == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['No'] ?? __('No') }}</span>
                                </label>
                            </div>
                            <p id="errappointment_category" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group" id="totalFeeBox">
                            <label for="">{{ $keywords['Total_Fee'] ?? __('Total Fee') }}
                                ({{ $userBs->base_currency_symbol }})</label>
                            <input type="number" name="appointment_price" id="price"
                                placeholder="{{ $keywords['appointment_booking_fee'] ?? __('appointment booking fee') }}"
                                value="{{ $userBs->appointment_price }}" class="form-control ">
                            <p id="errappointment_price" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="pp" value="{{ $userBs->full_payment }}">
                            <label>{{ $keywords['Full_Payment_Enabled'] ?? __('Full Payment Enabled ') }} ?</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="full_payment" value="1"
                                        class="selectgroup-input full-payment"
                                        {{ $userBs->full_payment == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Yes'] ?? __('Yes') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="full_payment" value="0"
                                        class="selectgroup-input full-payment"
                                        {{ $userBs->full_payment == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['No'] ?? __('No') }}</span>
                                </label>
                            </div>
                            <p id="errfull_payment" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group" id="PercentageBox">
                            <label for="">{{ $keywords['Advance_Percentage'] ?? __('Advance Percentage') }}</label>
                            <div class="input-group mb-3">
                                <input type="number" name="advance_percentage" value="{{ $userBs->advance_percentage }}"
                                    class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <p id="erradvance_percentage" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="pp" value="{{ $userBs->guest_checkout }}">
                            <label>{{ $keywords['Guest_Checkout_Enabled'] ?? __('Guest Checkout Enabled') }} ?</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="guest_checkout" value="1" class="selectgroup-input "
                                        {{ $userBs->guest_checkout == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Yes'] ?? __('Yes') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="guest_checkout" value="0" class="selectgroup-input "
                                        {{ $userBs->guest_checkout == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['No'] ?? __('No') }}</span>
                                </label>
                            </div>
                            <p id="errguest_checkout" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="text-center form-group">
                            <button data-form="ajaxForm" id="" type="submit"
                                class="submitBtn btn btn-primary">{{ $keywords['save'] ?? __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
