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
        <h4 class="page-title">{{ __('Package Features') }}</h4>
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
                <a href="#">{{ __('Packages Management') }} </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Package Features') }} </a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Package Features') }} </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="ajaxEditForm" class="" action="{{ route('admin.package.features.update') }}"
                                method="post">
                                {{ csrf_field() }}
                                <div class="alert alert-warning">
                                    {{ __('Only these selected features will be visible in frontend Pricing Section') }}
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('Package Features') }} </label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Custom Domain"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Custom Domain', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Custom Domain') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Subdomain"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Subdomain', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Subdomain') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="QR Builder"
                                                class="selectgroup-input" @if (is_array($features) && in_array('QR Builder', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('QR Builder') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="vCard"
                                                class="selectgroup-input" @if (is_array($features) && in_array('vCard', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('vCard') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Online CV & Export"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Online CV & Export', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Online CV & Export') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Follow/Unfollow"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Follow/Unfollow', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Follow/Unfollow') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Blog"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Blog', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Blog') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Portfolio"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Portfolio', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Portfolio') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Achievements"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Achievements', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Achievements') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Skill"
                                                class="selectgroup-input" @if (is_array($features) && in_array('Skill', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Skill') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Service"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Service', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Service') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Experience"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Experience', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Experience') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Testimonial"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Testimonial', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Testimonial') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Appointment"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Appointment', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Appointment') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Google Analytics"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Google Analytics', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Google Analytics') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Disqus"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Disqus', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Disqus') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="WhatsApp"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('WhatsApp', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('WhatsApp') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Facebook Pixel"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Facebook Pixel', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Facebook Pixel') }} </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Tawk.to"
                                                class="selectgroup-input"
                                                @if (is_array($features) && in_array('Tawk.to', $features)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Tawk.to') }} </span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="updateBtn" class="btn btn-success">{{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
