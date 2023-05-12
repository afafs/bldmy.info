@extends('user.layout')

@php
    $selLang = \App\Models\User\Language::where([['code', \Illuminate\Support\Facades\Session::get('currentLangCode')], ['user_id', \Illuminate\Support\Facades\Auth::id()]])->first();
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id], ['is_default', 1]])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
   
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='userLanguage'] {
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
        <h4 class="page-title">{{ $keywords['SEO_Information'] ?? __('SEO Informations') }}</h4>
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
                <a href="#">{{ $keywords['SEO_Information'] ?? __('SEO Informations') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.basic_settings.update_seo_informations',['language' => $selLang->code]) }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="card-title">
                                    {{ $keywords['Update_SEO_Information'] ?? __('Update SEO Informations') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Home_Page'] ?? __('Meta Keywords For Home Page') }}</label>
                                    <input class="form-control" name="home_meta_keywords"
                                        value="{{ $data->home_meta_keywords }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Home_Page'] ?? __('Meta Description For Home Page') }}</label>
                                    <textarea class="form-control" name="home_meta_description" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->home_meta_description }}</textarea>
                                </div>
                            </div>
                            @if ($userBs->theme == 3)
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Keywords_For_About_Page'] ?? __('Meta Keywords For About Page') }}</label>
                                        <input class="form-control" name="about_meta_keywords"
                                            value="{{ $data->about_meta_keywords }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Description_For_About_Page'] ?? __('Meta Description For About Page') }}</label>
                                        <textarea class="form-control" name="about_meta_description" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->about_meta_description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Keywords_For_Experience_Page'] ?? __('Meta Keywords For Experience Page') }}</label>
                                        <input class="form-control" name="experience_meta_keywords"
                                            value="{{ $data->experience_meta_keywords }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Description_For_Experience_Page'] ?? __('Meta Description For Experience Page') }}</label>
                                        <textarea class="form-control" name="experience_meta_description" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->experience_meta_description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Keywords_For_Testimonial_Page'] ?? __('Meta Keywords For Testimonial Page') }}</label>
                                        <input class="form-control" name="testimonial_meta_keywords"
                                            value="{{ $data->testimonial_meta_keywords }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Description_For_Testimonial_Page'] ?? __('Meta Description For Testimonial Page') }}</label>
                                        <textarea class="form-control" name="testimonial_meta_description" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->testimonial_meta_description }}</textarea>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Blogs_Page'] ?? __('Meta Keywords For Blogs Page') }}</label>
                                    <input class="form-control" name="blogs_meta_keywords"
                                        value="{{ $data->blogs_meta_keywords }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Blogs_Page'] ?? __('Meta Description For Blogs Page') }}</label>
                                    <textarea class="form-control" name="blogs_meta_description" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->blogs_meta_description }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Services_Page'] ?? __('Meta Keywords For Services Page') }}</label>
                                    <input class="form-control" name="services_meta_keywords"
                                        value="{{ $data->services_meta_keywords }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Services_Page'] ?? __('Meta Description For Services Page') }}</label>
                                    <textarea class="form-control" name="services_meta_description" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->services_meta_description }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Portfolios_Page'] ?? __('Meta Keywords For Portfolios Page') }}</label>
                                    <input class="form-control" name="portfolios_meta_keywords"
                                        value="{{ $data->portfolios_meta_keywords }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Portfolios_Page'] ?? __('Meta Description For Portfolios Page') }}</label>
                                    <textarea class="form-control" name="portfolios_meta_description" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ $data->portfolios_meta_description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-success {{ $data == null ? 'd-none' : '' }}">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
