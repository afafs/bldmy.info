@extends('user.layout')

@includeIf('user.partials.rtl-style')

@php
    $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission(Auth::user()->id);
    $permissions = json_decode($permissions, true);
@endphp

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Home_Sections'] ?? __('Home Sections') }}</h4>
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
                <a href="#">{{ $keywords['Home_Sections'] ?? __('Home Sections') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title d-inline-block">{{ $keywords['Home_Sections'] ?? __('Home Sections') }}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="ajaxForm" class="" action="{{ route('user.home.page.text.update') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $home_setting->id }}">
                                <input type="hidden" name="language_id" value="{{ $home_setting->language_id }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <br>
                                            <h3 class="text-warning">
                                                {{ $keywords['Hero_Section'] ?? __('Hero Section') }}</h3>
                                            <hr class="border-top">
                                        </div>
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="logo"><strong>{{ $keywords['Hero_Section_Image'] ?? __('Hero Section Image') }}</strong></label>
                                            </div>
                                            <div class="showHeroImage mb-3">
                                                <img width="200" src="{{ $home_setting->hero_image ? asset('assets/front/img/user/home_settings/' . $home_setting->hero_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                                @isset($home_setting->hero_image)
                                                    <button class="btn btn-danger btn-sm image-cross-btn" data-type="hero">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endisset
                                            </div>
                                            <input type="hidden" name="types[]" value="hero_image">
                                            <input type="file" name="hero_image" id="hero_image"
                                                class="form-control ltr">
                                            <p id="errhero_image" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 pr-0">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['First_Name'] ?? __('First Name') }}</label>
                                            <input type="hidden" name="types[]" value="first_name">
                                            <input type="text" class="form-control" name="first_name"
                                                placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                                                value="{{ $home_setting->first_name }}">
                                            <p id="errfirst_name" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pr-0">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Last_Name'] ?? __('Last Name') }}</label>
                                            <input type="hidden" name="types[]" value="last_name">
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                                                value="{{ $home_setting->last_name }}">
                                            <p id="errlast_name" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Designation'] ?? __('Designation') }}
                                                **</label>
                                            <input type="hidden" name="types[]" value="designation">
                                            <input type="text" class="form-control" name="designation"
                                                placeholder="{{ $keywords['Enter_designations'] ?? __('Enter designations') }}"
                                                value="{{ $home_setting->designation }}" data-role="tagsinput">
                                            <p class="text-warning mb-0">
                                                {{ $keywords['multiple_designations_text'] ?? __('use comma (,) to add multiple designations') }}
                                            </p>
                                            <p id="errdesignation" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <br>
                                            <h3 class="text-warning">
                                                {{ $keywords['About_Section'] ?? __('About Section') }} </h3>
                                            <hr class="border-top">
                                        </div>
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="logo"><strong>{{ $keywords['About_Section_Image'] ?? __('About Section Image') }}</strong></label>
                                            </div>
                                            <div class="col-md-12 showAboutImage mb-3">
                                                <img  width="200" src="{{ $home_setting->about_image ? asset('assets/front/img/user/home_settings/' . $home_setting->about_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="hidden" name="types[]" value="about_image">
                                            <input type="file" name="about_image" id="about_image"
                                                class="form-control ltr">
                                            <p id="errabout_image" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 pr-0">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['About_Section_Title'] ?? __('About Section Title') }}
                                            </label>
                                            <input type="hidden" name="types[]" value="about_title">
                                            <input type="text" class="form-control" name="about_title" placeholder=""
                                                value="{{ $home_setting->about_title }}">
                                            <p id="errabout_title" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pl-0">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['About_Section_Subtitle'] ?? __('About Section Subtitle') }}
                                            </label>
                                            <input type="hidden" name="types[]" value="about_subtitle">
                                            <input type="text" class="form-control" name="about_subtitle"
                                                placeholder="" value="{{ $home_setting->about_subtitle }}">
                                            <p id="errabout_subtitle" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="">{{ $keywords['About_Section_Content'] ?? __('About Section Content') }}
                                    </label>
                                    <input type="hidden" name="types[]" value="about_content">
                                    <textarea class="form-control" name="about_content" rows="5">{{ $home_setting->about_content }}</textarea>
                                    <p id="errabout_content" class="mb-0 text-danger em"></p>
                                </div>

                                @if ($userBs->theme != 3)
                                    @if (!empty($permissions) && in_array('Skill', $permissions))
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <br>
                                                    <h3 class="text-warning">
                                                        {{ $keywords['Skills_Section'] ?? __('Skills Section') }}</h3>
                                                    <hr class="border-top">
                                                </div>
                                                @if ($userBs->theme != 6 && $userBs->theme != 7)
                                                    <div class="form-group">
                                                        <div class="col-12 mb-2">
                                                            <label
                                                                for="logo"><strong>{{ $keywords['Skills_Image'] ?? __('Skills Image') }}</strong></label>
                                                        </div>
                                                        <div class="col-md-12 showSkillsImage mb-3">
                                                            <img width="200" src="{{ $home_setting->skills_image ? asset('assets/front/img/user/home_settings/' . $home_setting->skills_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                                alt="..." class="img-thumbnail">
                                                        </div>
                                                        <input type="hidden" name="types[]" value="skills_image">
                                                        <input type="file" name="skills_image" id="skills_image"
                                                            class="form-control ltr">
                                                        <p id="errskills_image" class="mb-0 text-danger em"></p>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-lg-6 pr-0">
                                                        <div class="form-group">
                                                            <label
                                                                for="">{{ $keywords['Skills_Section_Title'] ?? __('Skills Section Title') }}</label>
                                                            <input type="hidden" name="types[]" value="skills_title">
                                                            <input type="text" class="form-control"
                                                                name="skills_title" placeholder=""
                                                                value="{{ $home_setting->skills_title }}">
                                                            <p id="errskills_title" class="mb-0 text-danger em"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 pl-0">
                                                        <div class="form-group">
                                                            <label
                                                                for="">{{ $keywords['Skills_Section_Subtitle'] ?? __('Skills Section Subtitle') }}</label>
                                                            <input type="hidden" name="types[]" value="skills_subtitle">
                                                            <input type="text" class="form-control"
                                                                name="skills_subtitle" placeholder=""
                                                                value="{{ $home_setting->skills_subtitle }}">
                                                            <p id="errskills_subtitle" class="mb-0 text-danger em"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($userBs->theme != 6 && $userBs->theme != 7 && $userBs->theme != 8)
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Skills_Section_Content'] ?? __('Skills Section Content') }}</label>
                                                        <input type="hidden" name="types[]" value="skills_content">
                                                        <textarea class="form-control" name="skills_content" rows="5" placeholder="">{{ $home_setting->skills_content }}</textarea>
                                                        <p id="errskills_content" class="mb-0 text-danger em"></p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endif


                                @if (!empty($permissions) && in_array('Service', $permissions))
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <br>
                                                <h3 class="text-warning">
                                                    {{ $keywords['Service_Section'] ?? __('Service Section') }}</h3>
                                                <hr class="border-top">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 pr-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Service_Section_Title'] ?? __('Service Section Title') }}</label>
                                                        <input type="hidden" name="types[]" value="service_title">
                                                        <input type="text" class="form-control" name="service_title"
                                                            placeholder="" value="{{ $home_setting->service_title }}">
                                                        <p id="errservice_title" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 pl-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Service_Section_Subtitle'] ?? __('Service Section Subtitle') }}</label>
                                                        <input type="hidden" name="types[]" value="service_subtitle">
                                                        <input type="text" class="form-control"
                                                            name="service_subtitle" placeholder=""
                                                            value="{{ $home_setting->service_subtitle }}">
                                                        <p id="errservice_subtitle" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($userBs->theme != 6 && $userBs->theme != 7)
                                    @if (!empty($permissions) && in_array('Experience', $permissions))
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <br>
                                                    <h3 class="text-warning">
                                                        {{ $keywords['Experience_Section'] ?? __('Experience Section') }}
                                                    </h3>
                                                    <hr class="border-top">
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="">{{ $keywords['Experience_Section_Title'] ?? __('Experience Section Title') }}</label>
                                                            <input type="hidden" name="types[]"
                                                                value="experience_title">
                                                            <input type="text" class="form-control"
                                                                name="experience_title" placeholder=""
                                                                value="{{ $home_setting->experience_title }}">
                                                            <p id="errexperience_title" class="mb-0 text-danger em"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="">{{ $keywords['Experience_Section_Subtitle'] ?? __('Experience Section Subtitle') }}</label>
                                                            <input type="hidden" name="types[]"
                                                                value="experience_subtitle">
                                                            <input type="text" class="form-control"
                                                                name="experience_subtitle" placeholder=""
                                                                value="{{ $home_setting->experience_subtitle }}">
                                                            <p id="errexperience_subtitle" class="mb-0 text-danger em">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($userBs->theme != 3 && $userBs->theme != 6 && $userBs->theme != 7 && $userBs->theme != 8)
                                    @if (!empty($permissions) && in_array('Achievements', $permissions))
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <br>
                                                    <h3 class="text-warning">
                                                        {{ $keywords['Achievements_Section'] ?? __('Achievements Section') }}
                                                    </h3>
                                                    <hr class="border-top">
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-12 mb-2">
                                                        <label
                                                            for="logo"><strong>{{ $keywords['Achievements_Image'] ?? __('Achievements Image') }}</strong></label>
                                                    </div>
                                                    <div class="col-md-12 showAchievementImage mb-3">
                                                        <img width="200" src="{{ $home_setting->achievement_image ? asset('assets/front/img/user/home_settings/' . $home_setting->achievement_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                            alt="..." class="img-thumbnail">
                                                    </div>
                                                    <input type="hidden" name="types[]" value="achievement_image">
                                                    <input type="file" name="achievement_image" id="achievement_image"
                                                        class="form-control ltr">
                                                    <p id="errachievement_image" class="mb-0 text-danger em"></p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 pr-0">
                                                        <div class="form-group">
                                                            <label
                                                                for="">{{ $keywords['Achievements_Section_Title'] ?? __('Achievement Section Title') }}</label>
                                                            <input type="hidden" name="types[]"
                                                                value="achievement_title">
                                                            <input type="text" class="form-control"
                                                                name="achievement_title" placeholder=""
                                                                value="{{ $home_setting->achievement_title }}">
                                                            <p id="errachievement_title" class="mb-0 text-danger em"></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 pl-0">
                                                        <div class="form-group">
                                                            <label
                                                                for="">{{ $keywords['Achievements_Section_Subtitle'] ?? __('Achievement Section Subtitle') }}</label>
                                                            <input type="hidden" name="types[]"
                                                                value="achievement_subtitle">
                                                            <input type="text" class="form-control"
                                                                name="achievement_subtitle" placeholder=""
                                                                value="{{ $home_setting->achievement_subtitle }}">
                                                            <p id="errachievement_subtitle" class="mb-0 text-danger em">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif


                                @if (!empty($permissions) && in_array('Portfolio', $permissions))
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <br>
                                                <h3 class="text-warning">
                                                    {{ $keywords['Portfolio_Section'] ?? __('Portfolio Section') }}</h3>
                                                <hr class="border-top">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 pr-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Portfolio_Section_Title'] ?? __('Portfolio Section Title') }}</label>
                                                        <input type="hidden" name="types[]" value="portfolio_title">
                                                        <input type="text" class="form-control" name="portfolio_title"
                                                            placeholder="" value="{{ $home_setting->portfolio_title }}">
                                                        <p id="errportfolio_title" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 pl-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Portfolio_Section_Subtitle'] ?? __('Portfolio Section Subtitle') }}</label>
                                                        <input type="hidden" name="types[]" value="portfolio_subtitle">
                                                        <input type="text" class="form-control"
                                                            name="portfolio_subtitle" placeholder=""
                                                            value="{{ $home_setting->portfolio_subtitle }}">
                                                        <p id="errportfolio_subtitle" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($permissions) && in_array('Testimonial', $permissions))
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <br>
                                                <h3 class="text-warning">
                                                    {{ $keywords['Testimonial_Section'] ?? __('Testimonial Section') }}
                                                </h3>
                                                <hr class="border-top">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 pr-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Testimonial_Section_Title'] ?? __('Testimonial Section Title') }}</label>
                                                        <input type="hidden" name="types[]" value="testimonial_title">
                                                        <input type="text" class="form-control"
                                                            name="testimonial_title" placeholder=""
                                                            value="{{ $home_setting->testimonial_title }}">
                                                        <p id="errtestimonial_title" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 pl-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Testimonial_Section_Subtitle'] ?? __('Testimonial Section Subtitle') }}</label>
                                                        <input type="hidden" name="types[]"
                                                            value="testimonial_subtitle">
                                                        <input type="text" class="form-control"
                                                            name="testimonial_subtitle" placeholder=""
                                                            value="{{ $home_setting->testimonial_subtitle }}">
                                                        <p id="errtestimonial_subtitle" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($permissions) && in_array('Blog', $permissions))
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <br>
                                                <h3 class="text-warning">
                                                    {{ $keywords['Blog_Section'] ?? __('Blog Section') }}</h3>
                                                <hr class="border-top">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 pr-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Blog_Section_Title'] ?? __('Blog Section Title') }}</label>
                                                        <input type="hidden" name="types[]" value="blog_title">
                                                        <input type="text" class="form-control" name="blog_title"
                                                            placeholder="" value="{{ $home_setting->blog_title }}">
                                                        <p id="errblog_title" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 pl-0">
                                                    <div class="form-group">
                                                        <label
                                                            for="">{{ $keywords['Blog_Section_Subtitle'] ?? __('Blog Section Subtitle') }}</label>
                                                        <input type="hidden" name="types[]" value="blog_subtitle">
                                                        <input type="text" class="form-control" name="blog_subtitle"
                                                            placeholder="" value="{{ $home_setting->blog_subtitle }}">
                                                        <p id="errblog_subtitle" class="mb-0 text-danger em"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <br>
                                            <h3 class="text-warning">
                                                {{ $keywords['Contact_Section'] ?? __('Contact Section') }}</h3>
                                            <hr class="border-top">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 pr-0">
                                                <div class="form-group">
                                                    <label
                                                        for="">{{ $keywords['Contact_Section_Title'] ?? __('Contact Section Title') }}</label>
                                                    <input type="hidden" name="types[]" value="contact_title">
                                                    <input type="text" class="form-control" name="contact_title"
                                                        placeholder="" value="{{ $home_setting->contact_title }}">
                                                    <p id="errcontact_title" class="mb-0 text-danger em"></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 pl-0">
                                                <div class="form-group">
                                                    <label
                                                        for="">{{ $keywords['Contact_Section_Subtitle'] ?? __('Contact Section Subtitle') }}</label>
                                                    <input type="hidden" name="types[]" value="contact_subtitle">
                                                    <input type="text" class="form-control" name="contact_subtitle"
                                                        placeholder="" value="{{ $home_setting->contact_subtitle }}">
                                                    <p id="errcontact_subtitle" class="mb-0 text-danger em"></p>
                                                </div>
                                            </div>
                                        </div>
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

@section('scripts')
    <script>
        var imageRemoveRoute = "{{ route('user.home.image.remove') }}";
        var userId = {{ Auth::user()->id }};
        var langId = {{ $language->id }};
    </script>
    <script src="{{ asset('assets/admin/js/home-sections.js') }}"></script>
@endsection
