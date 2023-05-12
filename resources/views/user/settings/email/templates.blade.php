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
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title">
                                {{ $keywords['Mail_Templates'] ?? __('Mail Templates') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($templates) == 0)
                                <h3 class="text-center">{{ $keywords['NO_MAIL_TEMPLATE_FOUND'] ?? __('NO MAIL TEMPLATE FOUND') }} !</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ $keywords['Email_Type'] ?? __('Email Type') }}</th>
                                                <th scope="col">{{ $keywords['Mail_Subject'] ?? __('Mail Subject') }}</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($templates as $template)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-capitalize">
                                                        {{ str_replace('_', ' ', $template->email_type) }}
                                                    </td>
                                                    <td>{{ $template->email_subject }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('user.basic_settings.edit_mail_template', ['id' => $template->id]) . '?language=' . request('language') }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
