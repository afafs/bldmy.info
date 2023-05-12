@extends('admin.layout')
@if (Session::has('admin_lang'))
    @php
        $admin_lang = Session::get('admin_lang');
        $cd = str_replace('admin_', '', $admin_lang);
        $default = \App\Models\Language::where('code', $cd)->first();
    @endphp
@else
    @php
        $default = \App\Models\Language::where('is_default', 1)->first();
    @endphp
@endif
@php
    $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='language'] {
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
        <h4 class="page-title">{{ __('Payment Logs') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') . '?language=' . $default->code }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Payment') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Payment Log Page') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Payment Logs') }}</div>
                        </div>
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <form action="{{ url()->current() }}" class="d-inline-block float-right">
                                <input class="form-control" type="text" name="search"
                                    placeholder="{{ __('Search by Transaction ID') }}"
                                    value="{{ request()->input('search') ? request()->input('search') : '' }}">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($memberships) == 0)
                                <h3 class="text-center">{{ __('NO MEMBERSHIP FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Transaction Id') }}</th>
                                                <th scope="col">{{ __('Username') }}</th>
                                                <th scope="col">{{ __('Package') }}</th>
                                                <th scope="col">{{ __('Amount') }}</th>
                                                <th scope="col">{{ __('Payment Status') }}</th>
                                                <th scope="col">{{ __('Payment Method') }}</th>
                                                <th scope="col">{{ __('Receipt') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($memberships as $key => $membership)
                                                <tr>
                                                    <td>{{ strlen($membership->transaction_id) > 30 ? mb_substr($membership->transaction_id, 0, 30, 'UTF-8') . '...' : $membership->transaction_id }}
                                                    </td>
                                                    <td><a target="_blank"
                                                            href="{{ route('register.user.view', $membership->user_id) }}">{{ $membership->user->username }}</a>
                                                    </td>
                                                    <td><a target="_blank"
                                                            href="{{ route('admin.package.edit', $membership->package_id) }}">{{ $membership->package->title }}
                                                            [{{ $membership->package->term }}]</a></td>
                                                    @php
                                                        $bex = json_decode($membership->settings);
                                                    @endphp
                                                    <td>
                                                        @if ($membership->price == 0)
                                                            {{ __('Free') }}
                                                        @else
                                                            {{ format_price($membership->price) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (json_decode($membership->transaction_details) !== 'offline')
                                                            @if ($membership->status == 1)
                                                                <h3 class="d-inline-block badge badge-success">
                                                                    {{ __('Success') }}</h3>
                                                            @elseif ($membership->status == 0)
                                                                <h3 class="d-inline-block badge badge-warning">
                                                                    {{ __('Pending') }}</h3>
                                                            @elseif ($membership->status == 2)
                                                                <h3 class="d-inline-block badge badge-danger">
                                                                    {{ __('Rejected') }}</h3>
                                                            @endif
                                                        @else
                                                            <form id="statusForm{{ $membership->id }}"
                                                                class="d-inline-block"
                                                                action="{{ route('admin.payment-log.update') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $membership->id }}">
                                                                <select
                                                                    class="form-control form-control-sm
                                                    @if ($membership->status == 1) bg-success
                                                    @elseif ($membership->status == 0)
                                                    bg-warning
                                                    @elseif ($membership->status == 2)
                                                    bg-danger @endif
                                                    "
                                                                    name="status"
                                                                    onchange="document.getElementById('statusForm{{ $membership->id }}').submit();">
                                                                    <option value=0
                                                                        {{ $membership->status == 0 ? 'selected' : '' }}>
                                                                        {{ __('Pending') }}</option>
                                                                    <option value=1
                                                                        {{ $membership->status == 1 ? 'selected' : '' }}>
                                                                        {{ __('Success') }}</option>
                                                                    <option value=2
                                                                        {{ $membership->status == 2 ? 'selected' : '' }}>
                                                                        {{ __('Rejected') }}</option>
                                                                </select>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td>{{ $membership->payment_method }}</td>
                                                    <td>
                                                        @if (!empty($membership->receipt))
                                                            <a class="btn btn-sm btn-info" href="#"
                                                                data-toggle="modal"
                                                                data-target="#receiptModal{{ $membership->id }}">{{ __('Show') }}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!empty($membership->name !== 'anonymous'))
                                                            <a class="btn btn-sm btn-info" href="#"
                                                                data-toggle="modal"
                                                                data-target="#detailsModal{{ $membership->id }}">{{ __('Detail') }}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="receiptModal{{ $membership->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    {{ __('Receipt Image') }}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('assets/front/img/membership/receipt/' . $membership->receipt) }}"
                                                                    alt="Receipt" width="100%">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ __('Close') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="detailsModal{{ $membership->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    {{ __('Owner Details') }}
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h3 class="text-warning">{{ __('Member details') }}</h3>
                                                                <label>{{ __('Name') }}</label>
                                                                <p>{{ $membership->user->first_name . ' ' . $membership->user->last_name }}
                                                                </p>
                                                                <label>{{ __('Email') }}</label>
                                                                <p>{{ $membership->user->email }}</p>
                                                                <label>{{ __('Phone') }}</label>
                                                                <p>{{ $membership->user->phone }}</p>
                                                                <h3 class="text-warning">{{ __('Payment details') }}</h3>
                                                                <p><strong>{{ __('Cost') }}: </strong>
                                                                    {{ $membership->price == 0 ? 'Free' : $membership->price }}
                                                                </p>
                                                                <p><strong>{{ __('Currency') }}: </strong>
                                                                    {{ $membership->currency }}
                                                                </p>
                                                                <p><strong>{{ __('Method') }}: </strong>
                                                                    {{ $membership->payment_method }}
                                                                </p>
                                                                <h3 class="text-warning">{{ __('Package Details') }}</h3>
                                                                <p><strong>{{ __('Title') }}:
                                                                    </strong>{{ __($membership->package->title) }}
                                                                </p>
                                                                <p><strong>{{ __('Term') }}: </strong>
                                                                    {{ __($membership->package->term) }}
                                                                </p>
                                                                <p><strong>{{ __('Start Date') }}: </strong>
                                                                    @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                                                        <span
                                                                            class="badge badge-danger">{{ __('Never  Activated') }}</span>
                                                                    @else
                                                                        {{ \Illuminate\Support\Carbon::parse($membership->start_date)->format('M-d-Y') }}
                                                                    @endif
                                                                </p>
                                                                <p><strong>{{ __('Expire Date') }}: </strong>

                                                                    @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                                                        -
                                                                    @else
                                                                        @if ($membership->modified == 1)
                                                                            {{ \Illuminate\Support\Carbon::parse($membership->expire_date)->addDay()->format('M-d-Y') }}
                                                                            <span
                                                                                class="badge badge-primary btn-xs">{{ __('modified by Admin') }}</span>
                                                                        @else
                                                                            @if ($membership->package->term == 'lifetime')
                                                                                {{ __('lifetime') }}
                                                                            @else
                                                                                {{ \Illuminate\Support\Carbon::parse($membership->expire_date)->format('M-d-Y') }}
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </p>
                                                                <p>
                                                                    <strong>{{ __('Purchase Type') }}: </strong>
                                                                    @if ($membership->is_trial == 1)
                                                                        {{ __('Trial') }}
                                                                    @else
                                                                        {{ $membership->price == 0 ? __('Free') : __('Regular') }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    {{ __('Close') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-inline-block mx-auto">
                            {{ $memberships->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
