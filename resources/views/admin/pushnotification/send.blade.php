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
        <h4 class="page-title">{{ __('Send Notification') }}</h4>
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
                <a href="#">{{ __('Push Notification') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Send Notification') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <form class="" action="{{ route('admin.pushnotification.push') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="card-title">{{ __('Send Notification') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <div class="form-group">
                                    <label for="">{{ __('Title') }} **</label>
                                    <input type="text" class="form-control" name="title" value=""
                                        placeholder="{{ __('Enter title of Notification') }}">
                                    @if ($errors->has('title'))
                                        <p class="text-danger mb-0">{{ $errors->first('title') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Message') }}</label>
                                    <textarea name="message" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Button Text') }} **</label>
                                    <input type="text" class="form-control" name="button_text" value=""
                                        placeholder="{{ __('Enter Button Text') }}">
                                    @if ($errors->has('button_text'))
                                        <p class="text-danger mb-0">{{ $errors->first('button_text') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Button URL') }} **</label>
                                    <input type="text" class="form-control" name="button_url" value=""
                                        placeholder="{{ __('Enter Button URL') }}">
                                    @if ($errors->has('button_url'))
                                        <p class="text-danger mb-0">{{ $errors->first('button_url') }}</p>
                                    @endif
                                    <p class="mb-0 text-warning">
                                        {{ __('Only those users will receive push notification, who have allowed it') }}.
                                    </p>
                                    <p class="text-warning mb-0">
                                        {{ __("Push notification won't work for 'http', it needs 'https'") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success">
                            <span class="btn-label">
                                <i class="fa fa-check"></i>
                            </span>
                            {{ __('Send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
