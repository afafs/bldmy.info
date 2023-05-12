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
        <h4 class="page-title">{{ $keywords['Payment_Gateways'] ?? __('Payment Gateways') }}</h4>
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
                <a href="#">{{ $keywords['Payment_Gateways'] ?? __('Payment Gateways') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('user.paypal.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Paypal'] ?? __('Paypal') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf

                                <div class="form-group">
                                    <label>{{ $keywords['Paypal'] ?? __('Paypal') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1" class="selectgroup-input"
                                                {{ $paypal->status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0" class="selectgroup-input"
                                                {{ $paypal->status == 0 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $paypalInfo = json_decode($paypal->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Paypal_Test_Mode'] ?? __('Paypal Test Mode') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sandbox_check" value="1"
                                                class="selectgroup-input"
                                                {{ $paypalInfo ? ($paypalInfo['sandbox_check'] == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sandbox_check" value="0"
                                                class="selectgroup-input"
                                                {{ $paypalInfo ? ($paypalInfo['sandbox_check'] == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paypal_Client_ID'] ?? __('Paypal Client ID') }}</label>
                                    <input class="form-control" name="client_id"
                                        value="{{ $paypalInfo['client_id'] ?? '' }}">
                                    @if ($errors->has('client_id'))
                                        <p class="mb-0 text-danger">{{ $errors->first('client_id') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paypal_Client_Secret'] ?? __('Paypal Client Secret') }}</label>
                                    <input class="form-control" name="client_secret"
                                        value="{{ $paypalInfo['client_secret'] ?? '' }}">
                                    @if ($errors->has('client_secret'))
                                        <p class="mb-0 text-danger">{{ $errors->first('client_secret') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.stripe.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Stripe'] ?? __('Stripe') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $stripeInfo = json_decode($stripe->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Stripe'] ?? __('Stripe') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1" class="selectgroup-input"
                                                {{ $stripeInfo ? ($stripe->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0" class="selectgroup-input"
                                                {{ $stripeInfo ? ($stripe->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Stripe_Key'] ?? __('Stripe Key') }}</label>
                                    <input class="form-control" name="key" value="{{ $stripeInfo['key'] ?? '' }}">
                                    @if ($errors->has('key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('key') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Stripe_Secret'] ?? __('Stripe Secret') }}</label>
                                    <input class="form-control" name="secret" value="{{ $stripeInfo['secret'] ?? '' }}">
                                    @if ($errors->has('secret'))
                                        <p class="mb-0 text-danger">{{ $errors->first('secret') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.paytm.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Paytm'] ?? __('Paytm') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $paytmInfo = json_decode($paytm->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Paytm'] ?? __('Paytm') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $paytmInfo ? ($paytm->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $paytmInfo ? ($paytm->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paytm_Environment'] ?? __('Paytm Environment') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="environment" value="local"
                                                class="selectgroup-input"
                                                {{ $paytmInfo ? ($paytmInfo['environment'] == 'local' ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Local'] ?? __('Local') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="environment" value="production"
                                                class="selectgroup-input"
                                                {{ $paytmInfo ? ($paytmInfo['environment'] == 'production' ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Production'] ?? __('Production') }}</span>
                                        </label>
                                    </div>
                                    @if ($errors->has('environment'))
                                        <p class="mb-0 text-danger">{{ $errors->first('environment') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paytm_Merchant_Key'] ?? __('Paytm Merchant Key') }}</label>
                                    <input class="form-control" name="secret" value="{{ $paytmInfo['secret'] ?? '' }}">
                                    @if ($errors->has('secret'))
                                        <p class="mb-0 text-danger">{{ $errors->first('secret') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paytm_Merchant_mid'] ?? __('Paytm Merchant mid') }}</label>
                                    <input class="form-control" name="merchant"
                                        value="{{ $paytmInfo['merchant'] ?? '' }}">
                                    @if ($errors->has('merchant'))
                                        <p class="mb-0 text-danger">{{ $errors->first('merchant') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paytm_Merchant_website'] ?? __('Paytm Merchant website') }}</label>
                                    <input class="form-control" name="website"
                                        value="{{ $paytmInfo['website'] ?? '' }}">
                                    @if ($errors->has('website'))
                                        <p class="mb-0 text-danger">{{ $errors->first('website') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Industry_type_id'] ?? __('Industry type id') }}</label>
                                    <input class="form-control" name="industry"
                                        value="{{ $paytmInfo['industry'] ?? '' }}">
                                    @if ($errors->has('industry'))
                                        <p class="mb-0 text-danger">{{ $errors->first('industry') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.instamojo.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Instamojo'] ?? __('Instamojo') }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $instamojoInfo = json_decode($instamojo->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Instamojo'] ?? __('Instamojo') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $instamojo ? ($instamojo->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $instamojo ? ($instamojo->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Test_Mode'] ?? __('Test Mode') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sandbox_check" value="1"
                                                class="selectgroup-input"
                                                {{ $instamojoInfo ? ($instamojoInfo['sandbox_check'] == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sandbox_check" value="0"
                                                class="selectgroup-input"
                                                {{ $instamojoInfo ? ($instamojoInfo['sandbox_check'] == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Instamojo_API_Key'] ?? __('Instamojo API Key') }}</label>
                                    <input class="form-control" name="key"
                                        value="{{ $instamojoInfo['key'] ?? '' }}">
                                    @if ($errors->has('key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('key') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Instamojo_Auth_Token'] ?? __('Instamojo Auth Token') }}</label>
                                    <input class="form-control" name="token"
                                        value="{{ $instamojoInfo['token'] ?? '' }}">
                                    @if ($errors->has('token'))
                                        <p class="mb-0 text-danger">{{ $errors->first('token') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.paystack.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Paystack'] ?? __('Paystack') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $paystackInfo = json_decode($paystack->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Paystack'] ?? __('Paystack') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $paystackInfo ? ($paystack->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $paystackInfo ? ($paystack->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Paystack_Secret_Key'] ?? __('Paystack Secret Key') }}</label>
                                    <input class="form-control" name="key" value="{{ $paystackInfo['key'] ?? '' }}">
                                    @if ($errors->has('key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('key') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.flutterwave.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Flutterwave'] ?? __('Flutterwave') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $flutterwaveInfo = json_decode($flutterwave->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Flutterwave'] ?? __('Flutterwave') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $flutterwaveInfo ? ($flutterwave->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $flutterwaveInfo ? ($flutterwave->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Flutterwave_Public_Key'] ?? __('Flutterwave Public Key') }}</label>
                                    <input class="form-control" name="public_key"
                                        value="{{ $flutterwaveInfo['public_key'] ?? '' }}">
                                    @if ($errors->has('public_key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('public_key') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Flutterwave_Secret_Key'] ?? __('Flutterwave Secret Key') }}</label>
                                    <input class="form-control" name="secret_key"
                                        value="{{ $flutterwaveInfo['secret_key'] ?? '' }}">
                                    @if ($errors->has('secret_key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('secret_key') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.mollie.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Mollie_Payment'] ?? __('Mollie Payment') }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $mollieInfo = json_decode($mollie->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Mollie_Payment'] ?? __('Mollie Payment') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $mollieInfo ? ($mollie->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $mollieInfo ? ($mollie->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Mollie_Payment_Key'] ?? __('Mollie Payment Key') }}</label>
                                    <input class="form-control" name="key" value="{{ $mollieInfo['key'] ?? '' }}">
                                    @if ($errors->has('key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('key') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.razorpay.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Razorpay'] ?? __('Razorpay') }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $razorpayInfo = json_decode($razorpay->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Razorpay'] ?? __('Razorpay') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $razorpayInfo ? ($razorpay->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $razorpayInfo ? ($razorpay->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Razorpay_Key'] ?? __('Razorpay Key') }}</label>
                                    <input class="form-control" name="key"
                                        value="{{ $razorpayInfo['key'] ?? '' }}">
                                    @if ($errors->has('key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('key') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Razorpay_Secret'] ?? __('Razorpay Secret') }}</label>
                                    <input class="form-control" name="secret"
                                        value="{{ $razorpayInfo['secret'] ?? '' }}">
                                    @if ($errors->has('secret'))
                                        <p class="mb-0 text-danger">{{ $errors->first('secret') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.anet.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Authorize_Net'] ?? __('Authorize.Net') }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                @php
                                    $anetInfo = json_decode($anet->information, true);
                                @endphp
                                <div class="form-group">
                                    <label>{{ $keywords['Authorize_Net'] ?? __('Authorize.Net') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                                class="selectgroup-input"
                                                {{ $anetInfo ? ($anet->status == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                                class="selectgroup-input"
                                                {{ $anetInfo ? ($anet->status == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Authorize_Net_Test_Mode'] ?? __('Authorize.Net Test Mode') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sandbox_check" value="1"
                                                class="selectgroup-input"
                                                {{ $anetInfo ? ($anetInfo['sandbox_check'] == 1 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sandbox_check" value="0"
                                                class="selectgroup-input"
                                                {{ $anetInfo ? ($anetInfo['sandbox_check'] == 0 ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['API_Login_ID'] ?? __('API Login ID') }}</label>
                                    <input class="form-control" name="login_id"
                                        value="{{ $anetInfo['login_id'] ?? '' }}">
                                    @if ($errors->has('login_id'))
                                        <p class="mb-0 text-danger">{{ $errors->first('login_id') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Transaction_Key'] ?? __('Transaction Key') }}</label>
                                    <input class="form-control" name="transaction_key"
                                        value="{{ $anetInfo['transaction_key'] ?? '' }}">
                                    @if ($errors->has('transaction_key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('transaction_key') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Public_Client_Key'] ?? __('Public Client Key') }}</label>
                                    <input class="form-control" name="public_key"
                                        value="{{ $anetInfo['public_key'] ?? '' }}">
                                    @if ($errors->has('public_key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('public_key') }}</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <form class="" action="{{ route('user.mercadopago.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Mercadopago'] ?? __('Mercadopago') }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body pt-5 pb-5">

                        @csrf
                        @php
                            $mercadopagoInfo = json_decode($mercadopago->information, true);
                        @endphp
                        <div class="form-group">
                            <label>{{ $keywords['Mercado_Pago'] ?? __('Mercado Pago') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="1" class="selectgroup-input"
                                        {{ $mercadopagoInfo ? ($mercadopago->status == 1 ? 'checked' : '') : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="0" class="selectgroup-input"
                                        {{ $mercadopagoInfo ? ($mercadopago->status == 0 ? 'checked' : '') : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ $keywords['Mercado_Pago_Test_Mode'] ?? __('Mercado Pago Test Mode') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="sandbox_check" value="1" class="selectgroup-input"
                                        {{ $mercadopagoInfo ? ($mercadopagoInfo['sandbox_check'] == 1 ? 'checked' : '') : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="sandbox_check" value="0" class="selectgroup-input"
                                        {{ $mercadopagoInfo ? ($mercadopagoInfo['sandbox_check'] == 0 ? 'checked' : '') : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ $keywords['Mercadopago_Token'] ?? __('Mercadopago Token') }}</label>
                            <input class="form-control" name="token" value="{{ $mercadopagoInfo['token'] ?? '' }}">
                            @if ($errors->has('token'))
                                <p class="mb-0 text-danger">{{ $errors->first('token') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
