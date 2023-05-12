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
        <h4 class="page-title">{{ __('Section Customization') }}</h4>
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
                <a href="#">{{ __('Website Pages') }} </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Section Customization') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form class="" action="{{ route('admin.sections.update') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Customize Sections') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                @csrf
                                <div class="form-group">
                                    <label>{{ __('Introduction Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="intro_section" value="1"
                                                class="selectgroup-input" {{ $abs->intro_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="intro_section" value="0"
                                                class="selectgroup-input" {{ $abs->intro_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Features Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="feature_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->feature_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="feature_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->feature_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Work Process Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="process_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->process_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="process_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->process_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Preview Templates Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="templates_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->templates_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="templates_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->templates_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Featured Users Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured_users_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->featured_users_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured_users_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->featured_users_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Pricing Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="pricing_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->pricing_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="pricing_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->pricing_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Partners Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="partners_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->partners_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="partners_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->partners_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Testimonial Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="testimonial_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->testimonial_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="testimonial_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->testimonial_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('News Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="news_section" value="1"
                                                class="selectgroup-input" {{ $abs->news_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="news_section" value="0"
                                                class="selectgroup-input" {{ $abs->news_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Top Footer Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="top_footer_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->top_footer_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="top_footer_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->top_footer_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Copyright Section') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="copyright_section" value="1"
                                                class="selectgroup-input"
                                                {{ $abs->copyright_section == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="copyright_section" value="0"
                                                class="selectgroup-input"
                                                {{ $abs->copyright_section == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif"
                                        class="btn btn-success">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
