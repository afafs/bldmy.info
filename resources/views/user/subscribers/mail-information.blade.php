@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Mail_Subscribers'] ?? __('Mail Subscribers') }}</h4>
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
                <a href="#">{{ $keywords['Subscribers'] ?? __('Subscribers') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="#">{{ $keywords['Mail_Information_For_Subscribers'] ?? __('Mail Information For Subscribers') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('user.mail.subscriber') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="card-title">{{ $keywords['Mail_Information'] ?? __('Mail Information') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <div class="form-group">
                                    <label for="email">{{ $keywords['Reply_To'] ?? __('Reply To') }} **</label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ $info->email }}"
                                        placeholder="{{ $keywords['Enter_Email_Address'] ?? __('Enter Email Address') }}">
                                    @if ($errors->has('email'))
                                        <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="from-name">{{ $keywords['From_Name'] ?? __('From Name') }} **</label>
                                    <input id="from-name" type="text" class="form-control" name="from_name"
                                        value="{{ $info->from_name }}"
                                        placeholder="{{ $keywords['Enter_From_Name'] ?? __('Enter From name') }}">
                                    @if ($errors->has('from_name'))
                                        <p class="text-danger mb-0">{{ $errors->first('from_name') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success">
                            {{ $keywords['save'] ?? __('save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
