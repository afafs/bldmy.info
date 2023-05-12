@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Payment Gateways') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Payment Gateways') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-lg-4">
      <div class="card">
        <form action="{{route('admin.paypal.update')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Paypal') }}</div>
                  </div>
              </div>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                @csrf

                <div class="form-group">
                  <label>{{ __('Paypal') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="status" value="1" class="selectgroup-input" {{$paypal->status == 1 ? 'checked' : ''}}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="status" value="0" class="selectgroup-input" {{$paypal->status == 0 ? 'checked' : ''}}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>
                @php
                    $paypalInfo = json_decode($paypal->information, true);
                @endphp
                <div class="form-group">
                  <label>{{ __('Paypal Test Mode') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="sandbox_check" value="1" class="selectgroup-input" {{$paypalInfo["sandbox_check"] == 1 ? 'checked' : ''}}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="sandbox_check" value="0" class="selectgroup-input" {{$paypalInfo["sandbox_check"] == 0 ? 'checked' : ''}}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Paypal Client ID') }}</label>
                  <input class="form-control" name="client_id" value="{{$paypalInfo["client_id"]}}">
                  @if ($errors->has('client_id'))
                    <p class="mb-0 text-danger">{{$errors->first('client_id')}}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label>{{ __('Paypal Client Secret') }}</label>
                  <input class="form-control" name="client_secret" value="{{$paypalInfo["client_secret"]}}">
                  @if ($errors->has('client_secret'))
                    <p class="mb-0 text-danger">{{$errors->first('client_secret')}}</p>
                  @endif
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{route('admin.stripe.update')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Stripe') }}</div>
                  </div>
              </div>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                @csrf
                @php
                    $stripeInfo = json_decode($stripe->information, true);
                    // dd($stripeInfo);
                @endphp
                <div class="form-group">
                    <label>{{ __('Stripe') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="1" class="selectgroup-input" {{$stripe->status == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="0" class="selectgroup-input" {{$stripe->status == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Stripe') }} {{ __('Key') }}</label>
                    <input class="form-control" name="key" value="{{$stripeInfo['key']}}">
                    @if ($errors->has('key'))
                        <p class="mb-0 text-danger">{{$errors->first('key')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Stripe') }} {{ __('Secret') }} </label>
                    <input class="form-control" name="secret" value="{{$stripeInfo['secret']}}">
                    @if ($errors->has('secret'))
                        <p class="mb-0 text-danger">{{$errors->first('secret')}}</p>
                    @endif
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{route('admin.paytm.update')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Paytm') }} </div>
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
                    <label>{{ __('Paytm') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="1" class="selectgroup-input" {{$paytm->status == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="0" class="selectgroup-input" {{$paytm->status == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">  
                  <label>{{ __('Paytm Environment') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="environment"
                        value="local"
                        class="selectgroup-input"
                        {{ $paytmInfo['environment'] == 'local' ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Local') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="environment"
                        value="production"
                        class="selectgroup-input"
                        {{ $paytmInfo['environment'] == 'production' ? 'checked' : '' }}
                      >
                      <span class="selectgroup-button">{{ __('Production') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('environment'))
                    <p class="mb-0 text-danger">{{ $errors->first('environment') }}</p>
                  @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Paytm Merchant Key') }}</label>
                    <input class="form-control" name="secret" value="{{$paytmInfo['secret']}}">
                    @if ($errors->has('secret'))
                        <p class="mb-0 text-danger">{{$errors->first('secret')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Paytm Merchant mid') }}</label>
                    <input class="form-control" name="merchant" value="{{$paytmInfo['merchant']}}">
                    @if ($errors->has('merchant'))
                        <p class="mb-0 text-danger">{{$errors->first('merchant')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Paytm Merchant website') }}</label>
                    <input class="form-control" name="website" value="{{$paytmInfo['website']}}">
                    @if ($errors->has('website'))
                        <p class="mb-0 text-danger">{{$errors->first('website')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Industry type id') }}</label>
                    <input class="form-control" name="industry" value="{{$paytmInfo['industry']}}">
                    @if ($errors->has('industry'))
                        <p class="mb-0 text-danger">{{$errors->first('industry')}}</p>
                    @endif
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{route('admin.instamojo.update')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Instamojo') }}</div>
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
                    <label>{{ __('Instamojo') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="1" class="selectgroup-input" {{$instamojo->status == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="0" class="selectgroup-input" {{$instamojo->status == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Test Mode') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="sandbox_check" value="1" class="selectgroup-input" {{$instamojoInfo['sandbox_check'] == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="sandbox_check" value="0" class="selectgroup-input" {{$instamojoInfo['sandbox_check'] == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Instamojo API Key') }}</label>
                    <input class="form-control" name="key" value="{{$instamojoInfo['key']}}">
                    @if ($errors->has('key'))
                        <p class="mb-0 text-danger">{{$errors->first('key')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Instamojo Auth Token') }}</label>
                    <input class="form-control" name="token" value="{{$instamojoInfo['token']}}">
                    @if ($errors->has('token'))
                        <p class="mb-0 text-danger">{{$errors->first('token')}}</p>
                    @endif
                </div>

              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{route('admin.paystack.update')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Paystack') }}</div>
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
                    <label>{{ __('Paystack') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="1" class="selectgroup-input" {{$paystack->status == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="0" class="selectgroup-input" {{$paystack->status == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Paystack Secret Key') }}</label>
                    <input class="form-control" name="key" value="{{$paystackInfo['key']}}">
                    @if ($errors->has('key'))
                        <p class="mb-0 text-danger">{{$errors->first('key')}}</p>
                    @endif
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{route('admin.flutterwave.update')}}" method="post">
          @csrf
          <div class="card-header">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-title">{{ __('Flutterwave') }}</div>
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
                    <label>{{ __('Flutterwave') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="1" class="selectgroup-input" {{$flutterwave->status == 1 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="status" value="0" class="selectgroup-input" {{$flutterwave->status == 0 ? 'checked' : ''}}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Flutterwave Public Key') }}</label>
                    <input class="form-control" name="public_key" value="{{$flutterwaveInfo['public_key']}}">
                    @if ($errors->has('public_key'))
                        <p class="mb-0 text-danger">{{$errors->first('public_key')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('Flutterwave Secret Key') }}</label>
                    <input class="form-control" name="secret_key" value="{{$flutterwaveInfo['secret_key']}}">
                    @if ($errors->has('secret_key'))
                        <p class="mb-0 text-danger">{{$errors->first('secret_key')}}</p>
                    @endif
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <form class="" action="{{route('admin.mollie.update')}}" method="post">
            @csrf
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-title">{{ __('Mollie Payment') }}</div>
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
                        <label>{{ __('Mollie Payment') }}</label>
                        <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="1" class="selectgroup-input" {{$mollie->status == 1 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="0" class="selectgroup-input" {{$mollie->status == 0 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Mollie Payment Key') }}</label>
                        <input class="form-control" name="key" value="{{$mollieInfo['key']}}">
                        @if ($errors->has('key'))
                            <p class="mb-0 text-danger">{{$errors->first('key')}}</p>
                        @endif
                    </div>

                </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="form">
                <div class="form-group from-show-notify row">
                    <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                    </div>
                </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <form class="" action="{{route('admin.razorpay.update')}}" method="post">
            @csrf
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-title">{{ __('Razorpay') }}</div>
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
                        <label>{{ __('Razorpay') }}</label>
                        <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="1" class="selectgroup-input" {{$razorpay->status == 1 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="0" class="selectgroup-input" {{$razorpay->status == 0 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Razorpay Key') }}</label>
                        <input class="form-control" name="key" value="{{$razorpayInfo['key']}}">
                        @if ($errors->has('key'))
                            <p class="mb-0 text-danger">{{$errors->first('key')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Razorpay Secret') }}</label>
                        <input class="form-control" name="secret" value="{{$razorpayInfo['secret']}}">
                        @if ($errors->has('secret'))
                            <p class="mb-0 text-danger">{{$errors->first('secret')}}</p>
                        @endif
                    </div>

                </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="form">
                <div class="form-group from-show-notify row">
                    <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                    </div>
                </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <form class="" action="{{route('admin.anet.update')}}" method="post">
            @csrf
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-title">{{ __('Authorize.Net') }}</div>
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
                        <label>{{ __('Authorize.Net') }}</label>
                        <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="1" class="selectgroup-input" {{$anet->status == 1 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="0" class="selectgroup-input" {{$anet->status == 0 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Authorize.Net Test Mode') }}</label>
                        <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="sandbox_check" value="1" class="selectgroup-input" {{$anetInfo['sandbox_check'] == 1 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="sandbox_check" value="0" class="selectgroup-input" {{$anetInfo['sandbox_check'] == 0 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('API Login ID') }}</label>
                        <input class="form-control" name="login_id" value="{{$anetInfo['login_id']}}">
                        @if ($errors->has('login_id'))
                            <p class="mb-0 text-danger">{{$errors->first('login_id')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Transaction Key') }}</label>
                        <input class="form-control" name="transaction_key" value="{{$anetInfo['transaction_key']}}">
                        @if ($errors->has('transaction_key'))
                            <p class="mb-0 text-danger">{{$errors->first('transaction_key')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{ __('Public Client Key') }}</label>
                        <input class="form-control" name="public_key" value="{{$anetInfo['public_key']}}">
                        @if ($errors->has('public_key'))
                            <p class="mb-0 text-danger">{{$errors->first('public_key')}}</p>
                        @endif
                    </div>

                </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="form">
                <div class="form-group from-show-notify row">
                    <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                    </div>
                </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <form class="" action="{{route('admin.mercadopago.update')}}" method="post">
            @csrf
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-title">{{ __('Mercadopago') }}</div>
                    </div>
                </div>
            </div>


            <div class="card-body pt-5 pb-5">

                    @csrf
                    @php
                        $mercadopagoInfo = json_decode($mercadopago->information, true);
                    @endphp
                    <div class="form-group">
                        <label>{{ __('Mercado Pago') }}</label>
                        <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="1" class="selectgroup-input" {{$mercadopago->status == 1 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="status" value="0" class="selectgroup-input" {{$mercadopago->status == 0 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                        </div>
                    </div>

                    <div class="form-group">
                      <label>{{ __('Mercado Pago Test Mode') }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="sandbox_check" value="1" class="selectgroup-input" {{$mercadopagoInfo["sandbox_check"] == 1 ? 'checked' : ''}}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="sandbox_check" value="0" class="selectgroup-input" {{$mercadopagoInfo["sandbox_check"] == 0 ? 'checked' : ''}}>
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Mercadopago Token') }}</label>
                        <input class="form-control" name="token" value="{{$mercadopagoInfo['token']}}">
                        @if ($errors->has('token'))
                            <p class="mb-0 text-danger">{{$errors->first('token')}}</p>
                        @endif
                    </div>
            </div>

            <div class="card-footer">
                <div class="form">
                <div class="form-group from-show-notify row">
                    <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                    </div>
                </div>
                </div>
            </div>
            </form>
        </div>
    </div>
  </div>

@endsection
