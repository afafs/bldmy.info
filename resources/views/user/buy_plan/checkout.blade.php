@extends('user.layout')

@section('content')
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if (!empty($membership) && ($membership->package->term == 'lifetime' || $membership->is_trial == 1))
        <div class="alert bg-warning alert-warning text-white text-center">
            <h3>If you purchase this package <strong class="text-dark">({{ $package->title }})</strong>, then your current
                package <strong class="text-dark">({{ $membership->package->title }}@if ($membership->is_trial == 1) <span class="badge badge-secondary">Trial</span>
                    @endif)</strong> will be replaced immediately</h3>
        </div>
    @endif
    <div class="row justify-content-center align-items-center mb-1">
        <div class="col-md-1 pl-md-0">
        </div>

        <div class="col-md-6 pl-md-0 pr-md-0">
            <div class="card card-pricing card-pricing-focus card-secondary">
                <form id="my-checkout-form" action="{{ route('user.plan.checkout') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">
                    <div class="card-header">
                        <h4 class="card-title">{{ $package->title }}</h4>
                        <div class="card-price">
                            <span
                                class="price">{{ $package->price == 0 ? 'Free' : format_price($package->price) }}</span>
                            <span class="text">/{{ $package->term }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="specification-list">
                            <li>
                                <span class="name-specification">{{ $keywords['Membership'] ?? __('Membership') }}</span>
                                <span class="status-specification">{{ $keywords['Yes'] ?? __('Yes') }}</span>
                            </li>
                            <li>
                                <span class="name-specification">{{ $keywords['Start_Date'] ?? __('Start Date') }}</span>
                                @if ((!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                                    (!empty($membership) && $membership->is_trial == 1))
                                    <input type="hidden" name="start_date"
                                        value="{{ \Carbon\Carbon::today()->format('d-m-Y') }}">
                                    <span
                                        class="status-specification">{{ \Carbon\Carbon::today()->format('d-m-Y') }}</span>
                                @else
                                    <input type="hidden" name="start_date"
                                        value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}">
                                    <span
                                        class="status-specification">{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}</span>
                                @endif
                            </li>
                            <li>
                                <span class="name-specification">{{ $keywords['Expire_Date'] ?? __('Expire Date') }}</span>
                                <span class="status-specification">
                                    @if ($package->term == 'monthly')
                                        @if ((!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                                            (!empty($membership) && $membership->is_trial == 1))
                                            {{ \Carbon\Carbon::parse(now())->addMonth()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Carbon\Carbon::parse(now())->addMonth()->format('d-m-Y') }}">
                                        @else
                                            {{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addMonth()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addMonth()->format('d-m-Y') }}">
                                        @endif
                                    @elseif($package->term == 'lifetime')
                                        {{ __('Lifetime') }}
                                        <input type="hidden" name="expire_date"
                                            value="{{ \Carbon\Carbon::maxValue()->format('d-m-Y') }}">
                                    @else
                                        @if ((!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                                            (!empty($membership) && $membership->is_trial == 1))
                                            {{ \Carbon\Carbon::parse(now())->addYear()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Carbon\Carbon::parse(now())->addYear()->format('d-m-Y') }}">
                                        @else
                                            {{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addYear()->format('d-m-Y') }}
                                            <input type="hidden" name="expire_date"
                                                value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addYear()->format('d-m-Y') }}">
                                        @endif
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span class="name-specification">{{ $keywords['Total_Cost'] ?? __('Total Cost') }}</span>
                                <input type="hidden" name="price" value="{{ $package->price }}">
                                <span class="status-specification">
                                    {{ $package->price == 0 ? 'Free' : format_price($package->price) }}
                                </span>
                            </li>
                            @if ($package->price != 0)
                                <li>
                                    <div class="form-group px-0">
                                        <label class="text-white">{{ $keywords['Payment_Method'] ?? __('Payment Method') }}</label>
                                        <select name="payment_method" class="form-control input-solid" id="payment-gateway"
                                            required>
                                            <option value="" disabled selected>{{ $keywords['Select_a_Payment_Method'] ?? __('Select a Payment Method') }}</option>
                                            @foreach ($payment_methods as $payment_method)
                                                <option value="{{ $payment_method->name }}"
                                                    {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                                                    {{ $payment_method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            @endif



                            <div class="row gateway-details pt-3 text-left" id="tab-stripe" style="display: none;">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['Card_Number'] ?? __('Card Number') }} *</label>

                                        <input type="text" class="form-control" name="cardNumber"
                                            placeholder="{{ $keywords['Card_Number'] ?? __('Card Number') }}" autocomplete="off"
                                            oninput="validateCard(this.value);" disabled />

                                        @if ($errors->has('cardNumber'))
                                            <p class="text-danger mb-0">{{ $errors->first('cardNumber') }}</p>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{$keywords['CVC'] ??  __('CVC') }} *</label>

                                    <input type="text" class="form-control" placeholder="{{$keywords['CVC'] ?? __('CVC') }}"
                                        name="cardCVC" oninput="validateCVC(this.value);" disabled>

                                    @if ($errors->has('cardCVC'))
                                        <p class="text-danger mb-0">{{ $errors->first('cardCVC') }}</p>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ $keywords['Month'] ??  __('Month') }} *</label>

                                <input type="text" class="form-control" placeholder="{{ $keywords['Month'] ?? __('Month') }}"
                                    name="month" disabled>

                                @if ($errors->has('month'))
                                    <p class="text-danger mb-0">{{ $errors->first('month') }}</p>
                                @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <label>{{ $keywords['Year'] ??  __('Year') }} *</label>

                            <input type="text" class="form-control" placeholder="{{ $keywords['Year'] ??  __('Year') }}"
                                name="year" disabled>

                            @if ($errors->has('year'))
                                <p class="text-danger mb-0">{{ $errors->first('year') }}</p>
                            @enderror
                    </div>
                </div>
            </div>

            {{-- START: Authorize.net Card Details Form --}}
            <div class="row gateway-details pt-3" id="tab-anet" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <input class="form-control" type="text" id="anetCardNumber"
                            placeholder="{{ $keywords['Card_Number'] ?? __('Card Number') }}" disabled />
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="form-group">
                        <input class="form-control" type="text" id="anetExpMonth"
                            placeholder="{{ $keywords['Expire_Month'] ?? __('Expire Month') }}" disabled />
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <input class="form-control" type="text" id="anetExpYear"
                            placeholder="{{ $keywords['Expire_Year'] ?? __('Expire Year') }}" disabled />
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <input class="form-control" type="text" id="anetCardCode"
                            placeholder="{{ $keywords['Card_Code'] ?? __('Card Code') }}" disabled />
                    </div>
                </div>
                <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled />
                <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled />
                <ul id="anetErrors" style="display: none;"></ul>
            </div>
            {{-- END: Authorize.net Card Details Form --}}

            <div id="instructions" class="text-left"></div>
            <input type="hidden" name="is_receipt" value="0" id="is_receipt">
        </ul>

    </div>
    <div class="card-footer">
        <button class="btn btn-light btn-block" type="submit"><b>{{ $keywords['Checkout_Now'] ?? __('Checkout Now') }}</b></button>
    </div>
</form>
</div>
</div>
<div class="col-md-1 pr-md-0"></div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        "use strict";
        $("#payment-gateway").on('change', function() {
            let offline = @php echo json_encode($offline) @endphp;
            let data = [];
            offline.map(({
                id,
                name
            }) => {
                data.push(name);
            });
            let paymentMethod = $("#payment-gateway").val();

            $(".gateway-details").hide();
            $(".gateway-details input").attr('disabled', true);

            if (paymentMethod == 'Stripe') {
                $("#tab-stripe").show();
                $("#tab-stripe input").removeAttr('disabled');
            }

            if (paymentMethod == 'Authorize.net') {
                $("#tab-anet").show();
                $("#tab-anet input").removeAttr('disabled');
            }

            if (data.indexOf(paymentMethod) != -1) {
                let formData = new FormData();
                formData.append('name', paymentMethod);
                $.ajax({
                    url: '{{ route('front.payment.instructions') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    success: function(data) {
                        console.log(data);
                        let instruction = $("#instructions");
                        let instructions =
                            `<div class="gateway-desc">${data.instructions}</div>`;
                        if (data.description != null) {
                            var description =
                                `<div class="gateway-desc"><p>${data.description}</p></div>`;
                        } else {
                            var description = `<div></div>`;
                        }
                        let receipt = `<div class="form-element mb-2">
                                              <label>Receipt<span>*</span></label><br>
                                              <input type="file" name="receipt" value="" class="file-input" required>
                                              <p class="mb-0 text-warning">** Receipt image must be .jpg / .jpeg / .png</p>
                                           </div>`;
                        if (data.is_receipt == 1) {
                            $("#is_receipt").val(1);
                            let finalInstruction = instructions + description + receipt;
                            instruction.html(finalInstruction);
                        } else {
                            $("#is_receipt").val(0);
                            let finalInstruction = instructions + description;
                            instruction.html(finalInstruction);
                        }
                        $('#instructions').fadeIn();
                    },
                    error: function(data) {}
                })
            } else {
                $('#instructions').fadeOut();
            }
        });
    });
</script>

{{-- START: Authorize.net Scripts --}}
@php
$anet = App\Models\PaymentGateway::find(20);
$anerInfo = $anet->convertAutoData();
$anetTest = $anerInfo['sandbox_check'];

if ($anetTest == 1) {
$anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
} else {
$anetSrc = 'https://js.authorize.net/v1/Accept.js';
}
@endphp
<script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#my-checkout-form").on('submit', function(e) {
            e.preventDefault();
            let val = $("#payment-gateway").val();
            if (val == 'Authorize.net') {
                sendPaymentDataToAnet();
            } else {
                $(this).unbind('submit').submit();
            }
        });
    });

    function sendPaymentDataToAnet() {
        // Set up authorisation to access the gateway.
        var authData = {};
        authData.clientKey = "{{ $anerInfo['public_key'] }}";
        authData.apiLoginID = "{{ $anerInfo['login_id'] }}";

        var cardData = {};
        cardData.cardNumber = document.getElementById("anetCardNumber").value;
        cardData.month = document.getElementById("anetExpMonth").value;
        cardData.year = document.getElementById("anetExpYear").value;
        cardData.cardCode = document.getElementById("anetCardCode").value;

        // Now send the card data to the gateway for tokenisation.
        // The responseHandler function will handle the response.
        var secureData = {};
        secureData.authData = authData;
        secureData.cardData = cardData;
        Accept.dispatchData(secureData, responseHandler);
    }

    function responseHandler(response) {
        if (response.messages.resultCode == "Error") {
            var i = 0;
            let errorLists = ``;
            while (i < response.messages.message.length) {
                console.log(
                    response.messages.message[i].code + ": " +
                    response.messages.message[i].text
                );
                errorLists += `<li class="text-danger">${response.messages.message[i].text}</li>`;

                i = i + 1;
            }
            $("#anetErrors").show();
            $("#anetErrors").html(errorLists);
        } else {
            paymentFormUpdate(response.opaqueData);
        }
    }

    function paymentFormUpdate(opaqueData) {
        document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
        document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
        document.getElementById("my-checkout-form").submit();
    }
</script>
{{-- END: Authorize.net Scripts --}}
@endsection
