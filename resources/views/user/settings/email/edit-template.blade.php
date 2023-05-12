@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Email_Settings'] ?? __('Email Settings') }}</h4>
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
                <a href="#">{{ $keywords['Basic_Settings'] ?? __('Basic Settings') }}</a>
            </li>

            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Mail_Templates'] ?? __('Mail Templates') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Mail_Template'] ?? __('Edit Mail Template') }}
                    </div>
                    <a class="btn btn-primary btn-sm float-right d-inline-block "
                        href="{{ route('user.basic_settings.mail_templates') . '?language=' . request('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward" style="font-size: 12px;"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>

                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-7">
                            <form id="mailTemplateForm"
                                action="{{ route('user.basic_settings.update_mail_template', ['id' => $templateInfo->id]) }}"
                                method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            @php $mailType = str_replace('_', ' ', $templateInfo->email_type); @endphp
                                            <label>{{ $keywords['Email_Type'] ?? __('Email Type') }}</label>
                                            <input type="text" class="form-control text-capitalize" name="email_type"
                                                value="{{ $mailType }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ $keywords['Mail_Subject'] ?? __('Mail Subject') }} *</label>
                                            <input type="text" class="form-control" name="email_subject"
                                                placeholder="{{ $keywords['Enter_Mail_Subject'] ?? __('Enter Mail Subject') }}"
                                                value="{{ $templateInfo->email_subject }}">
                                            @if ($errors->has('email_subject'))
                                                <p class="mt-1 mb-0 text-danger">{{ $errors->first('email_subject') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ $keywords['Mail_Body'] ?? __('Mail Body') }} *</label>
                                            <textarea class="form-control summernote" name="email_body"
                                                placeholder="{{ $keywords['Enter_Email_Body_Format'] ?? __('Enter Email Body Format') }}" data-height="300">
                                                      @if (!empty($templateInfo->email_body))
{!! replaceBaseUrl($templateInfo->email_body, 'summernote') !!}
@endif </textarea>
                                            @if ($errors->has('email_body'))
                                                <p class="text-danger">{{ $errors->first('email_body') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @includeIf('user.settings.email.bbcode')
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="mailTemplateForm" class="btn btn-success">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
