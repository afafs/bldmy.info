@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Social_Links'] ?? __('Social Links') }}</h4>
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
                <a href="#">{{ $keywords['Social_Links'] ?? __('Social Links') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="socialForm" action="{{ route('user.social.update') }}" method="post">
                    <div class="card-header">
                        <div class="card-title d-inline-block">{{ $keywords['Edit_Social_Link'] ?? __('Edit Social Link') }}
                        </div>
                        <a class="btn btn-info btn-sm float-right d-inline-block"
                            href="{{ route('user.social.index') . '?language=' . request('language') }}">
                            <span class="btn-label">
                                <i class="fas fa-backward"></i>
                            </span>
                            {{ $keywords['Back'] ?? __('Back') }}
                        </a>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                @csrf
                                <input type="hidden" name="socialid" value="{{ $social->id }}">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Social_Icon'] ?? __('Social Icon') }} **</label>
                                    <div class="btn-group d-block">
                                        <button type="button" class="btn btn-primary iconpicker-component"><i
                                                class="{{ $social->icon }}"></i></button>
                                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                            data-selected="fa-car" data-toggle="dropdown">
                                        </button>
                                        <div class="dropdown-menu"></div>
                                    </div>
                                    <input id="inputIcon" type="hidden" name="icon" value="{{ $social->icon }}">
                                    @if ($errors->has('icon'))
                                        <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <small>{{ $keywords['Social_Icon_nb_text'] ?? __('NB: click on the dropdown icon to select a social link icon') }}.</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['URL'] ?? __('URL') }} **</label>
                                    <input type="text" class="form-control" name="url" value="{{ $social->url }}"
                                        placeholder="{{ $keywords['Enter_URL_of_social_media_account'] ?? __('Enter URL of social media account') }}">
                                    @if ($errors->has('url'))
                                        <p class="mb-0 text-danger">{{ $errors->first('url') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                        **</label>
                                    <input type="number" class="form-control ltr" name="serial_number"
                                        value="{{ $social->serial_number }}"
                                        placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    @if ($errors->has('serial_number'))
                                        <p class="mb-0 text-danger">{{ $errors->first('serial_number') }}</p>
                                    @endif
                                    <p class="text-warning">
                                        <small>{{ $keywords['social_icon_serial_msg'] ??
                                            __('The higher the serial number is, the later the social
                                                                                                                      link will be shown') }}
                                            .</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-3">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-lg-3 col-md-3 col-sm-12">

                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
