@extends('user.profile1.theme8.layout')

@section('tab-title')
    {{ $keywords['Home'] ?? 'Home' }}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->home_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->home_meta_keywords : '')

@section('content')

    <!--====== Start Hero Section ======-->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-6">
                    <div class="hero-text">
                        <h2 class="hero-title">{{ $keywords["Hi_I'm,"] ?? "Hi I'm," }}
                            <span>{{ $home_text->first_name ?? $user->first_name }}
                                {{ $home_text->last_name ?? $user->last_name }}</span>
                        </h2>
                        <p class="animated-text cd-headline clip is-full-width">
                            <span>{{ $keywords['I_am'] ?? 'I am' }}</span>
                            @php
                                $designations = explode(',', $home_text->designation ?? '');
                            @endphp
                            <span class="cd-words-wrapper">
                                @foreach ($designations as $key => $designation)
                                    <b class="{{ $key == 0 ? 'is-visible' : '' }}">{{ $designation }}</b>
                                @endforeach
                            </span>
                        </p>
                        @if (is_array($userPermissions) && in_array('Contact', $userPermissions))
                            <a href="{{ route('front.user.contact', getParam()) }}"
                                class="main-btn">{{ $keywords['Hire_me'] ?? 'Hire me' }}</a>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6 col-lg-5">
                    <div class="hero-image-wrapper">
                        @if (isset($home_text->hero_image))
                            <img class="lazy"
                                data-src="{{ asset('assets/front/img/user/home_settings/' . $home_text->hero_image) }}"
                                alt="Image">
                        @endif
                        <ul class="icons d-none">
                            <li><img src="{{ asset('assets/front/css/profile/theme6-8/video-camera.png') }}"
                                    alt="VideoCamera"></li>
                            <li><img src="{{ asset('assets/front/css/profile/theme6-8/camera.png') }}" alt="Camera"></li>
                            <li><img src="{{ asset('assets/front/css/profile/theme6-8/gallery.png') }}" alt="Gallery">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="hero-big-text">{{ $home_text->first_name ?? $user->first_name }}
            {{ $home_text->last_name ?? $user->last_name }}</h1>
        <ul class="social-links">
            @if (isset($social_medias))
                @foreach ($social_medias as $social_media)
                    <li><a target="_blank" href="{{ $social_media->url }}"><i class="{{ $social_media->icon }}"></i></a>
                    </li>
                @endforeach
            @endif
        </ul>
    </section>
    <!--====== End Hero Section ======-->

    <!--====== Start About Section ======-->
    <section class="about-section section-gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9">
                    <div class="about-gallery">
                        <div class="img-one">
                            <div class="img-inner">
                                @if (!empty($home_text->about_image))
                                    <img data-src="{{ asset('assets/front/img/user/home_settings/' . $home_text->about_image) }}"
                                        class="lazy" alt="About Image">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-10">
                    <div class="about-text">
                        <div class="section-heading mb-25">
                            <span class="tagline">{{ $home_text->about_title ?? 'My Resume' }}</span>
                            <h2 class="title">
                                {{ $home_text->about_subtitle ?? 'About Me' }}
                            </h2>
                        </div>
                        <p>{!! nl2br($home_text->about_content ?? '') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End About Section ======-->
    @if (is_array($userPermissions) && in_array('Skill', $userPermissions))
        <!--====== Skill Section Start ======-->
        <section class="skill-section lazy" data-bg="{{ asset('assets/front/css/profile/theme6-8/skill-bg.jpg') }}">
            <div class="container">
                <div class="section-heading heading-white text-center text-xl-center mb-15">
                    <span class="tagline">{{ $home_text->skills_subtitle ?? __('Technical Skills') }}</span>
                    <h2 class="title">{{ $home_text->skills_subtitle ?? __('Technical Skills') }}</h2>
                </div>
                <div class="row justify-content-xl-start justify-content-center">
                    @foreach ($skills as $skill)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="single-skill-item mt-50">
                                <div class="chart" data-percent="{{ $skill->percentage }}">
                                    <span>{{ $skill->percentage }}</span>
                                </div>
                                <p class="title">{{ $skill->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @isset($home_text->skills_image)
                <div class="skill-img d-none d-xl-block">
                    <img data-src="{{ asset('assets/front/img/user/home_settings/' . $home_text->skills_image) }}"
                        class="lazy" alt="Image">
                </div>
            @endisset
        </section>
        <!--====== Skill Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Service', $userPermissions))
        <!--====== Start Service Section ======-->
        <section class="service-section section-gap bg-grey-color" id="services">
            <div class="container">
                <div class="section-heading text-center mb-20">
                    <span class="tagline">{{ $home_text->service_title ?? __('Services') }}</span>
                    <h2 class="title">{{ $home_text->service_subtitle ?? __('What I Do ?') }}</h2>
                </div>
                <div class="services-loop">
                    <div class="row justify-content-center">
                        @foreach ($services as $service)
                            <div class="col-lg-4 col-md-6 col-sm-10">
                                <div class="single-service-box mt-30">
                                    <div class="thumbnail">
                                        <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                            class="lazy" alt="Service Images">
                                    </div>
                                    <h4 class="title">
                                        {{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}
                                    </h4>
                                    <div class="hover-content">
                                        <div class="content-inner">
                                            <h5><a
                                                    @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>{{ $service->name }}</a>
                                            </h5>
                                            <p>{!! strlen($service->content) > 120 ? mb_substr($service->content, 0, 120, 'UTF-8') . '...' : $service->content !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!--====== End Service Section ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Experience', $userPermissions))
        <!--====== Resume Section Start ======-->
        <section class="resume-section section-gap">
            <div class="container">
                <div class="section-heading text-center mb-50">
                    <div class="tagline">
                        {{ !empty($home_text->experience_title) ? $home_text->experience_title : __('RESUME') }}</div>
                    <h2 class="title">
                        {{ !empty($home_text->experience_subtitle) ? $home_text->experience_subtitle : __('Experience') }}
                    </h2>
                </div>
                <div class="resume-wrapper">
                    <div class="row justify-content-md-between justify-content-center">
                        @if (count($educations) > 0)
                            <div class="col-md-5 col-sm-10">
                                <div class="resume-header">
                                    <div class="inner">
                                        <img data-src="{{ asset('assets/front/css/profile/theme6-8/open-book.png') }}"
                                            class="lazy" alt="Icon">
                                        <span>{{ $keywords['Education'] ?? 'Education' }}</span>
                                    </div>
                                </div>
                                <div class="resume-loop">
                                    @foreach ($educations as $education)
                                        <div class="single-resume-item">
                                            <h5 class="title">{{ $education->degree_name }}</h5>
                                            <span
                                                class="duration">{{ \Carbon\Carbon::parse($education->start_date)->format('M j, Y') }}
                                                -@if (!empty($education->end_date))
                                                    {{ \Carbon\Carbon::parse($education->end_date)->format('M j, Y') }}
                                                @else
                                                    {{ $keywords['Present'] ?? 'Present' }}
                                                @endif
                                            </span>
                                            <p>{!! nl2br($education->short_description) !!}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if (count($educations) > 0 && count($job_experiences) > 0)
                            <div class="col-md-2 col-12 align-self-end">
                                <div class="resume-line"></div>
                            </div>
                        @endif
                        @if (count($job_experiences) > 0)
                            <div class="col-md-5 col-sm-10">
                                <div class="resume-header">
                                    <div class="inner">
                                        <img data-src="{{ asset('assets/front/css/profile/theme6-8/employee.png') }}"
                                            class="lazy" alt="Icon">
                                        <span>{{ $keywords['Job'] ?? 'Job' }}</span>
                                    </div>
                                </div>
                                <div class="resume-loop">
                                    @foreach ($job_experiences as $job_experience)
                                        <div class="single-resume-item">
                                            <h5 class="title">{{ $job_experience->designation }}
                                                [{{ $job_experience->company_name }}]</h5>
                                            <span
                                                class="duration">{{ \Carbon\Carbon::parse($job_experience->start_date)->format('M j, Y') }}
                                                - @if ($job_experience->is_continue == 0)
                                                    {{ \Carbon\Carbon::parse($job_experience->end_date)->format('M j, Y') }}
                                                @else
                                                    {{ $keywords['Present'] ?? 'Present' }}
                                                @endif
                                            </span>
                                            <p>{!! nl2br($job_experience->content) !!}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!--====== Resume Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Achievements', $userPermissions))
        <!--====== Counter Section Start ======-->
        <section class="counter-section bg-secondary-color">
            <div class="container">
                <div class="row">
                    @foreach ($achievements as $achievement)
                        <div class="col-lg-3 col-md-6">
                            <div class="counter-item">
                                <div class="counter-wrap">
                                    <span class="counter">{{ $achievement->count }}</span>
                                    <span class="suffix">+</span>
                                </div>
                                <h6 class="title">{{ $achievement->title }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== Counter Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Portfolio', $userPermissions))
        <!--====== Project Section Start ======-->
        <section class="project-section section-gap">
            <div class="container">
                <div class="section-heading text-center mb-40">
                    <span
                        class="tagline">{{ !empty($home_text->portfolio_title) ? $home_text->portfolio_title : __('Portfolios') }}</span>
                    <h2 class="title">
                        {{ !empty($home_text->portfolio_subtitle) ? $home_text->portfolio_subtitle : __('My Portfolios') }}
                    </h2>
                </div>
                <div class="project-filter">
                    <ul>
                        <li data-filter="*" class="active">{{ $keywords['All'] ?? __('All') }}</li>
                        @foreach ($portfolio_categories as $portfolio_category)
                            <li data-filter=".cat-{{ $portfolio_category->id }}">{{ $portfolio_category->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="project-loop row">
                            @foreach ($portfolios as $portfolio)
                                <div class="col-lg-4 col-md-4 cat-{{ $portfolio->bcategory->id }}">
                                    <div class="project-item">
                                        <img class="lazy"
                                            data-src="{{ asset('assets/front/img/user/portfolios/' . $portfolio->image) }}"
                                            alt="ProjectImage">
                                        <a href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}"
                                            class="title">
                                            <span>{{ strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Project Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Testimonial', $userPermissions))
        <!--====== Testimonial Section Start ======-->
        <section class="testimonial-section">
            <div class="container">
                <div class="section-heading mb-50">
                    <span
                        class="tagline">{{ !empty($home_text->testimonial_title) ? $home_text->testimonial_title : __('Testimonials') }}</span>
                    <h2 class="title">
                        {{ !empty($home_text->testimonial_subtitle) ? $home_text->testimonial_subtitle : __('Testimonials') }}
                    </h2>
                </div>
                <div class="row testimonial-slider">
                    @foreach ($testimonials as $testimonial)
                        <div class="col">
                            <div class="testimonial-box">
                                <p class="content">{!! nl2br($testimonial->content) !!}</p>
                                <div class="author">
                                    <div class="photo">
                                        <img class="lazy"
                                            data-src="{{ asset('assets/front/img/user/testimonials/' . $testimonial->image) }}"
                                            alt="Image">
                                    </div>
                                    <div class="info">
                                        <h5 class="name">{{ $testimonial->name }}</h5>
                                        @if (!empty($testimonial->occupation))
                                            <span class="title">{{ $testimonial->occupation }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== Testimonial Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Testimonial', $userPermissions))
        <!--====== Blog Section Start ======-->
        <section class="blog-section section-gap">
            <div class="container">
                <div class="section-heading text-center mb-20">
                    <span
                        class="tagline">{{ !empty($home_text->blog_title) ? $home_text->blog_title : __('Blogs') }}</span>
                    <h2 class="title">{{ !empty($home_text->blog_subtitle) ? $home_text->blog_subtitle : __('Blogs') }}
                    </h2>
                </div>
                <div class="row justify-content-center latest-blog-loop">
                    @foreach ($blogs as $blog)
                        <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="single-latest-post mt-30">
                                <div class="thumbnail">
                                    <a href="{{ route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id]) }}">
                                        <img class="lazy"
                                            data-src="{{ asset('assets/front/img/user/blogs/' . $blog->image) }}"
                                            alt="Image">
                                    </a>
                                </div>
                                <ul class="post-meta">
                                    <li>
                                        <a
                                            href="{{ route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id]) }}"><i
                                                class="fas fa-user"></i>{{ $keywords['by'] ?? 'by' }}
                                            {{ $user->username }}</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id]) }}"><i
                                                class="far fa-calendar-alt"></i>{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</a>
                                    </li>
                                </ul>
                                <h3 class="title">
                                    <a
                                        href="{{ route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id]) }}">{{ strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title }}</a>
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== Blog Section End ======-->
    @endif

    <!--====== Contact Section Start ======-->
    <section class="contact-section section-gap-bottom">
        <div class="container">
            <div class="section-heading text-center mb-50">
                <span
                    class="tagline">{{ !empty($home_text->contact_title) ? $home_text->contact_title : __('Get in touch') }}</span>
                <h2 class="title">
                    {{ !empty($home_text->contact_subtitle) ? $home_text->contact_subtitle : __('Get in touch') }}</h2>
            </div>
            <div class="contact-form">
                <form action="{{ route('front.contact.message', [getParam()]) }}" enctype="multipart/form-data"
                    method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="input-field mb-30">
                                <input type="text" placeholder="{{ $keywords['Name'] ?? 'Name' }}" name="fullname"
                                    required>
                                @if ($errors->has('fullname'))
                                    <p class="text-danger mb-0">{{ $errors->first('fullname') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="input-field mb-30">
                                <input type="email" placeholder="{{ $keywords['Email_Address'] ?? 'Email Address' }}"
                                    name="email" required>
                                @if ($errors->has('email'))
                                    <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-field mb-30">
                                <input type="text" placeholder="{{ $keywords['Subject'] ?? 'Subject' }}"
                                    name="subject" required>
                                @if ($errors->has('subject'))
                                    <p class="text-danger mb-0">{{ $errors->first('subject') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-field mb-30">
                                <textarea placeholder="{{ $keywords['Message'] ?? 'Message' }}" name="message"></textarea>
                                @if ($errors->has('message'))
                                    <p class="text-danger mb-0">{{ $errors->first('message') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-field text-center">
                                <button type="submit"
                                    class="main-btn">{{ $keywords['Send_Message'] ?? 'Send Message' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!--====== Contact Section End ======-->
@endsection
