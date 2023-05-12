@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Information'] ?? __('Information') }}</h4>
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
                <a href="#">{{ $keywords['Information'] ?? __('Information') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="ajaxForm" action="{{ route('user.basic_settings.update_info') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ $keywords['Update_Information'] ?? __('Update Information') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>{{ $keywords['Website_Title'] ?? __('Website Title') }}</label>
                                    <input type="text" class="form-control" name="website_title"
                                        value="{{ isset($data->website_title) ? $data->website_title : '' }}"
                                        placeholder="{{ $keywords['Enter_Website_Title'] ?? __('Enter Website Title') }}">
                                    <p id="errwebsite_title" class="em text-danger mb-0"></p>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <br>
                                            <h3 class="text-warning">
                                                {{ $keywords['Currency_Settings'] ?? __('Currency Settings') }}</h3>
                                            <hr class="divider">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">

                                            <label>{{ $keywords['Base_Currency_Symbol'] ?? __('Base Currency Symbol') }}
                                                **</label>
                                            <input type="text" class="form-control ltr" name="base_currency_symbol"
                                                value="{{ $data->base_currency_symbol }}">
                                            <p id="errbase_currency_symbol" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ $keywords['Base_Currency_Symbol_Position'] ?? __('Base Currency Symbol Position') }}
                                                **</label>
                                            <select name="base_currency_symbol_position" class="form-control ltr">
                                                <option value="left"
                                                    {{ $data->base_currency_symbol_position == 'left' ? 'selected' : '' }}>
                                                    {{ $keywords['Left'] ?? __('Left') }}
                                                </option>
                                                <option value="right"
                                                    {{ $data->base_currency_symbol_position == 'right' ? 'selected' : '' }}>
                                                    {{ $keywords['Right'] ?? __('Right') }}
                                                </option>
                                            </select>
                                            <p id="errbase_currency_symbol_position" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ $keywords['Base_Currency_Text'] ?? __('Base Currency Text') }}
                                                **</label>
                                            <input type="text" class="form-control ltr" name="base_currency_text"
                                                value="{{ $data->base_currency_text }}">
                                            <p id="errbase_currency_text" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ $keywords['Base_Currency_Text_Position'] ?? __('Base Currency Text Position') }}
                                                **</label>
                                            <select name="base_currency_text_position" class="form-control ltr">
                                                <option value="left"
                                                    {{ $data->base_currency_text_position == 'left' ? 'selected' : '' }}>
                                                    {{ $keywords['Left'] ?? __('Left') }}
                                                </option>
                                                <option value="right"
                                                    {{ $data->base_currency_text_position == 'right' ? 'selected' : '' }}>
                                                    {{ $keywords['Right'] ?? __('Right') }}
                                                </option>
                                            </select>
                                            <p id="errbase_currency_text_position" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ $keywords['Base_Currency_Rate'] ?? __('Base Currency Rate') }}
                                                **</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{{ __('1 USD') }} =</span>
                                                </div>
                                                <input type="text" name="base_currency_rate" class="form-control ltr"
                                                    value="{{ $data->base_currency_rate }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ $data->base_currency_text }}</span>
                                                </div>
                                            </div>
                                            <p id="errbase_currency_rate" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" data-form="ajaxForm" id=""
                                    class="submitBtn btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
