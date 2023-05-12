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
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{__('Edit Coupon')}}</h4>
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
                <a href="#">{{__('Packages')}}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{__('Coupons')}}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{__('Edit')}}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{__('Edit Coupon')}}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.coupon.index') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{__('Back')}}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">

                            <form id="ajaxForm" class="modal-form" action="{{ route('admin.coupon.update') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('Name')}} **</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $coupon->name }}" placeholder="{{__('Enter name')}}">
                                            <p id="errname" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('Code')}} **</label>
                                            <input type="text" class="form-control" name="code"
                                                value="{{ $coupon->code }}" placeholder="{{__('Enter code')}}">
                                            <p id="errcode" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('Type')}} **</label>
                                            <select name="type" id="" class="form-control">
                                                <option value="percentage"
                                                    {{ $coupon->type == 'percentage' ? 'selected' : '' }}>{{__('Percentage')}}
                                                </option>
                                                <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>
                                                    {{__('Fixed')}}
                                                </option>
                                            </select>
                                            <p id="errtype" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('Value')}} **</label>
                                            <input type="text" class="form-control" name="value"
                                                value="{{ $coupon->value }}" placeholder="{{__('Enter value')}}" autocomplete="off">
                                            <p id="errvalue" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Start Date **</label>
                                            <input type="text" class="form-control datepicker" name="start_date"
                                                value="{{ $coupon->start_date }}" placeholder="{{__('Enter start date')}}"
                                                autocomplete="off">
                                            <p id="errstart_date" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('End Date')}} **</label>
                                            <input type="text" class="form-control datepicker" name="end_date"
                                                value="{{ $coupon->end_date }}" placeholder="{{__('Enter end date')}}"
                                                autocomplete="off">
                                            <p id="errend_date" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('Packages')}}</label>
                                            <select class="select2" name="packages[]" multiple="multiple"
                                                placeholder="Select Packages">
                                                @foreach ($packages as $package)
                                                    <option value="{{ $package->id }}"
                                                        {{ is_array($selectedPackages) && in_array($package->id, $selectedPackages) ? 'selected' : '' }}>
                                                        {{ $package->title }} {{ ucfirst($package->term) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="mb-0 text-warning">{{__('This coupon can be applied to these packages')}}</p>
                                            <p class="mb-0 text-warning">{{__('Leave this field blank for all packages')}}</p>
                                            <p id="errpackages" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{__('Maximum uses limit')}} **</label>
                                            <input type="number" value="{{ $coupon->maximum_uses_limit }}"
                                                class="form-control " name="maximum_uses_limit" value=""
                                                placeholder="{{__('Enter Maximum uses limit')}}" autocomplete="off">
                                            <p id="errmaximum_uses_limit" class="mb-0 text-danger em"></p>
                                            <p class="mb-0 text-warning">{{__('Enter 999999 to make it unlimited')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" data-form="ajaxForm" id="" class="submitBtn btn btn-success">{{__('Update')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
