@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Translate_Keywords'] ?? __('Translate Keywords') }}</h4>
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
                <a href="#">{{ $keywords['Translate_Keywords'] ?? __('Translate Keywords') }}</a>
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
                                {{ $keywords['Translate_Keywords'] ?? __('Translate Keywords') }}</div>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('user.vcard') }}" class="btn btn-primary btn-sm"><i
                                    class="fas fa-chevron-left"></i> {{ $keywords['Back'] ?? __('Back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <form id="ajaxForm" class=""
                                action="{{ route('user.vcard.keywordsUpdate', $vcard->id) }}" method="post">
                                @csrf
                                <div class="row">
                                    @foreach ($keywords as $key => $value)
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">{{ str_replace('_', ' ', $key) }} **</label>
                                                <input type="text"
                                                    class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                    name="{{ $key }}" value="{{ $value }}">
                                            </div>
                                        </div>
                                    @endforeach
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
