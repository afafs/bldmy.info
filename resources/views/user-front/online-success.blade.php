<!DOCTYPE html>
<html lang="en" @if ($userCurrentLang->rtl == 1) dir="rtl" @endif>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $bs->website_title }} - {{ __('Success') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/plugin.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/success.css') }}" />
    <!-- base color change -->
    <link href="{{ asset('assets/front/css/style-base-color.php') . '?color=' . $bs->base_color }}" rel="stylesheet">
    @if($userCurrentLang->rtl == 1)
    <style>
         .success-row > [class*="col"]:first-child{
         margin-right: 30%;
         }
    </style>
    @else
    <style>
        .success-row > [class*="col"]:first-child{
         margin-left: 30%;
         }
    </style>
    @endif
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto" id="mt">
                <div class="payment">
                    <div class="payment_header text-center text-light">
                        <div class="check">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        <h4>{{ $keywords['Payment_Success'] ?? __('payment success') }}</h4>
                    </div>
                    <div class="content p-3">
                        <div class="row success-row">
                            <div class="col-lg-6 ">
                                <table class="table table-borderless ">
                            <tbody>
                                <tr>
                                    <th class="p-0" scope="row">
                                        {{ $keywords['Transaction_Id'] ?? __('Transaction ID') }}</th>
                                    <td class="p-0">{{ $appointment->transaction_id }}</td>
                                </tr>
                                <tr>
                                    <th class="p-0" scope="row">{{ $keywords['SL_No'] ?? __('Serial no.') }}</th>
                                    <td class="p-0">{{ $appointment->serial_number }}</td>
                                </tr>
                                <tr>
                                    <th class="p-0" scope="row">
                                        {{ $keywords['Booking_Date'] ?? __('Booking Date') }}</th>
                                    <td class="p-0">
                                        @php
                                            $data = Carbon\Carbon::parse($appointment->date);
                                        @endphp
                                        {{ $data->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-0" scope="row">
                                        {{ $keywords['Booking_Time'] ?? __('Booking Time') }}</th>
                                    <td class="p-0">{{ $appointment->time }}</td>
                                </tr>
                            </tbody>
                        </table>
                            </div>
                        </div>
                        
                        <div class="p-3">
                            @auth('customer')
                                <a
                                    href="{{ route('customer.appointments', getParam()) }}">{{ $keywords['My_Appointments'] ?? __('My Appointments') }}</a>
                            @endauth
                            <a
                                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Back_to_Home'] ?? __('Back to Home') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
