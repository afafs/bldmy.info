<!DOCTYPE html>
<html lang="en" @if ($userCurrentLang->rtl == 1) dir="rtl" @endif>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Success!</title>
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
                        <h4>{{ __('payment_success') }}</h4>
                    </div>
                    <div class="content p-3">
                        <table class="table table-borderless text-left ">
                            <tbody>
                                <tr>
                                    <th class="p-0" scope="row">{{ __('Transaction ID') }}</th>
                                    <td class="p-0">{{ $appointment->transaction_id }}</td>
                                </tr>
                                <tr>
                                    <th class="p-0" scope="row">{{ __('Serial no.') }}</th>
                                    <td class="p-0">{{ $appointment->serial_number }}</td>
                                </tr>
                                <tr>
                                    <th class="p-0" scope="row">{{ __('Date') }}</th>
                                    <td class="p-0">
                                        @php
                                            $data = Carbon\Carbon::parse($appointment->date);
                                        @endphp
                                        {{ $data->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-0" scope="row">{{ __('Time slot') }}</th>
                                    <td class="p-0">{{ $appointment->time }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-3">
                            @auth
                                <a href="{{ route('customer.dashboard', getParam()) }}">{{ __('Go to Dashboard') }}</a>
                            @endauth
                            @guest
                                <a href="{{ route('front.user.detail.view', getParam()) }}">{{ __('Back to Home') }}</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
