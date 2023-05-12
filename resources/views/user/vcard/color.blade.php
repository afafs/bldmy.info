@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Button_and_Icon_Colors'] ?? __('Button & Icon Colors') }}</h4>
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
                <a href="#">{{ $keywords['vCards'] ?? __('vCards') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $vcard->vcard_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Button_and_Icon_Colors'] ?? __('Button & Icon Colors') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-title d-inline-block">
                                {{ $keywords['Button_and_Icon_Colors'] ?? __('Button & Icon Colors') }}</div>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('user.vcard') . '?language=' . request('language') }}"
                                class="btn btn-primary btn-sm"><i class="fas fa-chevron-left"></i>
                                {{ $keywords['Back'] ?? __('Back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <form id="ajaxForm" class="" action="{{ route('user.vcard.colorUpdate', $vcard->id) }}"
                                method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Base_Color'] ?? __('Base Color') }}
                                                **</label>
                                            <input type="text" class="form-control jscolor" name="base_color"
                                                value="{{ $vcard->base_color }}">
                                        </div>
                                    </div>

                                    @if ($vcard->template == 6)
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Summary_Section_Background_Color'] ?? __('Summary Section Background Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="summary_background_color"
                                                    value="{{ $vcard->summary_background_color }}">
                                            </div>
                                        </div>
                                    @endif

                                    @if ($vcard->template != 5 && $vcard->template != 6 && $vcard->template != 9 && $vcard->template != 10)
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Call_Button_Color'] ?? __('Call Button Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor" name="call_button_color"
                                                    value="{{ $vcard->call_button_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Whatsapp_Button_Color'] ?? __('Whatsapp Button Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="whatsapp_button_color"
                                                    value="{{ $vcard->whatsapp_button_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Mail_Button_Color'] ?? __('Mail Button Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor" name="mail_button_color"
                                                    value="{{ $vcard->mail_button_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Add_to_Contact_Button_Color'] ?? __("'Add to Contact' Button Color") }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="add_to_contact_button_color"
                                                    value="{{ $vcard->add_to_contact_button_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Share_vCard_Button_Color'] ?? __("'Share vCard' Button Color") }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="share_vcard_button_color"
                                                    value="{{ $vcard->share_vcard_button_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Phone_Icon_Color'] ?? __('Phone Icon Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor" name="phone_icon_color"
                                                    value="{{ $vcard->phone_icon_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Email_Icon_Color'] ?? __('Email Icon Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor" name="email_icon_color"
                                                    value="{{ $vcard->email_icon_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Address_Icon_Color'] ?? __('Address Icon Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="address_icon_color" value="{{ $vcard->address_icon_color }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Website_URL_Color'] ?? __('Website URL Color') }}
                                                    **</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="website_url_icon_color"
                                                    value="{{ $vcard->website_url_icon_color }}">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button data-form="ajaxForm" type="submit" id=""
                                    class="submitBtn btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
