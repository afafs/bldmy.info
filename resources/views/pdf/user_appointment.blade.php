<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment</title>
    <link rel="stylesheet" href="{{ asset('assets/front/css/membership-pdf.css') }}">
</head>

<body>
    <div class="main">
        <table class="heading">
            <tr>
                <td>
                    @if ($bs->logo)
                        <img loading="lazy" src="{{ asset('assets/front/img/' . $bs->logo) }}" height="40"
                            class="d-inline-block">
                    @else
                        <img loading="lazy" src="{{ asset('assets/admin/img/noimage.jpg') }}" height="40"
                            class="d-inline-block">
                    @endif
                </td>
                <td class="text-right strong invoice-heading">{{ __('Appointment') }}</td>
            </tr>
        </table>
        <div class="header">
            <div class="ml-20">
                <table class="text-left">
                    <tr>
                        <td class="strong small gry-color">Customer:</td>
                    </tr>
                    <tr>
                        <td class="strong">{{ ucfirst($appointment->name) }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Email: </strong> {{ $appointment->email }}</td>
                    </tr>
                    @if (!empty($category))
                        <tr>
                            <td class="gry-color small"><strong>Category: </strong> {{ $category }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="order-details ">
                <table class="text-right">
                    <tr>
                        <td class="gry-color small"><strong>{{ __('Transaction ID') }}:</strong> #{{ $order_id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>{{ __('Amount') }}:</strong>
                            {{ $amount == 0 ? __('Free') : $amount }} {{ $appointment->currency }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>{{ __('Payment Method') }}:</strong>
                            {{ $request['payment_method'] }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small">
                            <strong>{{ __('Payment Status') }}:</strong>{{ __('Completed') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>{{ __('Booking Date') }}:</strong>
                            {{ \Illuminate\Support\Carbon::now()->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="package-info">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color info-titles">
                        <th width="20%">{{ __('Serial No.') }}</th>
                        <th width="20%">{{ __('Time Slot') }}</th>
                        <th width="20%">{{ __('Date') }}</th>
                    </tr>
                </thead>
                @php
                    $dt = Carbon\Carbon::parse($appointment->date);
                @endphp
                <tbody class="strong">
                    <tr class="info-titles">
                        <td>{{ $appointment->serial_number }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>{{ $dt->isoFormat('DD/MM/YYYY') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="mt-80">
            <tr>
                <td class="gry-color small text-right regards"><strong>{{ __('Total Amount') }}:</strong>
                    {{ $base_currency_symbol_position == 'left' ? $base_currency_symbol : '' }}
                    {{ $appointment->total_amount }}
                    {{ $base_currency_symbol_position == 'right' ? $base_currency_symbol : '' }}
                </td>
            </tr>
            <tr>
                <td class="gry-color small text-right regards"><strong>{{ __('Paid Amount') }}:</strong>
                    {{ $base_currency_symbol_position == 'left' ? $base_currency_symbol : '' }}
                    {{ $appointment->amount }}
                    {{ $base_currency_symbol_position == 'right' ? $base_currency_symbol : '' }}
                </td>
            </tr>
            @if (!empty($appointment->due_amount))
                <tr>
                    <td class="gry-color small text-right regards"><strong>{{ __('Due Amount') }}:</strong>
                        {{ $base_currency_symbol_position == 'left' ? $base_currency_symbol : '' }}
                        {{ $appointment->due_amount }}
                        {{ $base_currency_symbol_position == 'right' ? $base_currency_symbol : '' }}
                    </td>
                </tr>
            @endif
            <tr>
                <td class="text-right regards">{{ __('Thanks & Regards') }},</td>
            </tr>
            <tr>
                <td class="text-right strong regards">{{ $bs->website_title }}</td>
            </tr>
        </table>
    </div>


</body>

</html>
