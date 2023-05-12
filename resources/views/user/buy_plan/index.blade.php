@extends('user.layout')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buy_plan.css') }}">
@endsection

@php
    $user = Auth::guard('web')->user();
    $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
@endphp

@section('content')

    @if (is_null($package))

        @php
            $pendingMemb = \App\Models\Membership::query()
                ->where([['user_id', '=', Auth::id()], ['status', 0]])
                ->whereYear('start_date', '<>', '9999')
                ->orderBy('id', 'DESC')
                ->first();
            $pendingPackage = isset($pendingMemb) ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id) : null;
        @endphp

        @if ($pendingPackage)
            <div class="alert alert-warning">
                {{ $keywords['buy_plan_approve_reject_text'] ?? __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken') }}
                .
            </div>
            <div class="alert alert-warning">
                <strong> {{ $keywords['Pending_Package'] ?? __('Pending Package') }}: </strong> {{ $pendingPackage->title }}
                <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
                <span class="badge badge-warning">{{ $keywords['Decision_Pending'] ?? __('Decision Pending') }}</span>
            </div>
        @else
            <div class="alert alert-warning">
                {{ $keywords['membership_expired_text'] ?? __('Your membership is expired. Please purchase a new package / extend the current package') }}
                .
            </div>
        @endif
    @else
        <div class="row justify-content-center align-items-center mb-1">
            <div class="col-12">
                <div class="alert border-left border-primary text-dark">
                    @if ($package_count >= 2)
                        @if ($next_membership->status == 0)
                            <strong
                                class="text-danger">{{ $keywords['buy_plan_approve_reject_text'] ?? __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken') }}.</strong><br>
                        @elseif ($next_membership->status == 1)
                            <strong
                                class="text-danger">{{ $keywords['another_package_activate_msg'] ?? __('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated') }}
                            </strong><br>
                        @endif
                    @endif

                    <strong>{{ $keywords['Current_Package'] ?? __('Current_Package') }}: </strong>
                    {{ $keywords[str_replace(' ', '_', $current_package->title)] ?? $current_package->title }}
                    <span
                        class="badge badge-secondary">{{ $keywords[str_replace(' ', '_', $current_package->term)] ?? __($current_package->term) }}</span>
                    @if ($current_membership->is_trial == 1)
                        ({{ $keywords['Expire_Date'] ?? __('Expire Date') }}:
                        {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                        <span class="badge badge-primary">{{ __('Trial') }}</span>
                    @else
                        ({{ $keywords['Expire_Date'] ?? __('Expire Date') }}:
                        {{ $current_package->term === 'lifetime' ? $keywords['lifetime'] ?? __('Lifetime') : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                    @endif

                    @if ($package_count >= 2)
                        <div>
                            <strong>{{ $keywords['Next_Package_To_Activate'] ?? __('Next Package To Activate') }}:
                            </strong> {{ $next_package->title }} <span
                                class="badge badge-secondary">{{ $next_package->term }}</span>
                            @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
                                (
                                {{ $keywords['Activation_Date'] ?? __('Activation Date') }}:
                                {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
                                {{ $keywords['Expire_Date'] ?? __('Expire Date') }}:
                                {{ $next_package->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }})
                            @endif
                            @if ($next_membership->status == 0)
                                <span
                                    class="badge badge-warning">{{ $keywords['Decision_Pending'] ?? __('Decision Pending') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <div class="row mb-5 justify-content-center">
        @foreach ($packages as $key => $package)
            <div class="col-md-3 pr-md-0 mb-5">
                <div class="card-pricing2 @if (isset($current_package->id) && $current_package->id === $package->id) card-success @else card-primary @endif">
                    <div class="pricing-header">
                        <h3 class="fw-bold d-inline-block">
                            {{ $keywords[str_replace(' ', '_', $package->title)] ?? __($package->title) }}
                        </h3>
                        @if (isset($current_package->id) && $current_package->id === $package->id)
                            <h3 class="badge badge-danger d-inline-block float-right ml-2">{{ __('Current') }}</h3>
                        @endif
                        @if ($package_count >= 2 && $next_package->id == $package->id)
                            @if ($next_membership->status == 1)
                                <h3 class="badge badge-warning d-inline-block float-right ml-2">
                                    {{ $keywords['Next'] ?? __('Next') }}</h3>
                            @endif
                        @endif
                        <span class="sub-title"></span>
                    </div>
                    <div class="price-value">
                        <div class="value">
                            <span
                                class="amount">{{ $package->price == 0 ? $keywords['Free'] ?? __('Free') : format_price($package->price) }}</span>
                            <span
                                class="month">/{{ $keywords[str_replace(' ', '_', $package->term)] ?? __($package->term) }}</span>
                        </div>
                    </div>
                    <ul class="pricing-content">
                        @foreach (json_decode($package->features) as $feature)
                            <li>{{ $keywords[str_replace(' ', '_', $feature)] ?? __($feature) }}</li>
                        @endforeach
                    </ul>
                    @php
                        $hasPendingMemb = \App\Http\Helpers\UserPermissionHelper::hasPendingMembership(Auth::id());
                    @endphp
                    @if ($package_count < 2 && !$hasPendingMemb)
                        <div class="px-4">
                            @if (isset($current_package->id) && $current_package->id === $package->id)
                                @if ($package->term != 'lifetime' || $current_membership->is_trial == 1)
                                    <a href="{{ route('user.plan.extend.checkout', $package->id) }}"
                                        class="btn btn-success btn-lg w-75 fw-bold mb-3">{{ $keywords['Extend'] ?? __('Extend') }}</a>
                                @endif
                            @else
                                <a href="{{ route('user.plan.extend.checkout', $package->id) }}"
                                    class="btn btn-primary btn-block btn-lg fw-bold mb-3">{{ $keywords['Buy_Now'] ?? __('Buy Now') }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
