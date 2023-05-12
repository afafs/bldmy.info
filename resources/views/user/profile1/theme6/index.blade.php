@extends('user.profile1.theme6.layout')

@section('tab-title')
    {{ $keywords['Home'] ?? 'Home' }}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->home_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->home_meta_keywords : '')

@section('content')
    <!--====== Hero Area Start ======-->
    <section class="hero-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="hero-content">
                        <h1 class="hero-title">{{ $home_text->first_name ?? $user->first_name }}
                            {{ $home_text->last_name ?? $user->last_name }}</h1>
                        <p class="animated-text cd-headline clip is-full-width">
                            <span>{{ $keywords["Hi_I'm,"] ?? "Hi I'm," }}</span>
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
            </div>
        </div>
        @if (isset($home_text->hero_image))
            <div class="hero-img lazy"
                data-bg="{{ asset('assets/front/img/user/home_settings/' . $home_text->hero_image) }}">
            </div>
        @endif
    </section>
    <!--====== Hero Area End ======-->

    <!--====== About Section Start ======-->
    <section class="about-section section-gap-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9">
                    <div class="about-img">
                        <img class="lazy"
                            data-src="{{ isset($home_text->about_image) ? asset('assets/front/img/user/home_settings/' . $home_text->about_image) : asset('assets/front/img/profile/about.png') }}"
                            alt="Image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-10">
                    <div class="about-text">
                        <div class="section-heading mb-20">
                            <span class="tagline">{{ $home_text->about_title ?? 'My Resume' }}</span>
                            <h2 class="title">{{ $home_text->about_subtitle ?? 'About Me' }}</h2>
                        </div>
                        <p>{!! nl2br($home_text->about_content ?? '') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== About Section End ======-->
    @if (is_array($userPermissions) && in_array('Skill', $userPermissions))
        <!--====== Skill Section Start ======-->
        <section class="skill-section section-gap bg-primary-color">
            <div class="container">
                <div class="section-heading heading-white text-center">
                    <span class="tagline">{{ $home_text->skills_subtitle ?? __('Technical Skills') }}</span>
                    <h2 class="title">{{ $home_text->skills_subtitle ?? __('Technical Skills') }}</h2>
                </div>
                <div class="row justify-content-center">
                    @foreach ($skills as $skill)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-skill-item mt-50">
                                <div class="chart" data-percent="{{ $skill->percentage }}"
                                    data-bar-color="#{{ $skill->color }}">
                                    <span>{{ $skill->percentage }}</span>
                                </div>
                                <p class="title">{{ $skill->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== Skill Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Service', $userPermissions))
        <!--====== Service Section Start ======-->
        <section class="service-section section-gap">
            <div class="container">
                <div class="section-heading text-center mb-20">
                    <span class="tagline">{{ $home_text->service_title ?? __('Services') }}</span>
                    <h2 class="title">{{ $home_text->service_subtitle ?? __('What I Do ?') }}</h2>
                </div>
                <div class="row justify-content-center services-loop">
                    @foreach ($services as $service)
                        <div class="col-lg-4 col-md-6 col-sm-9">
                            <div class="single-service-box mt-30">
                                <a
                                    @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>
                                    <div class="thumbnail">
                                        <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                            class="lazy" alt="Image">
                                    </div>
                                </a>
                                <h5 class="title">
                                    <a
                                        @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</a>
                                </h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== Service Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Achievements', $userPermissions))
        <!--====== Counter Section Start ======-->
        <section class="counter-section">
            <div class="container">
                <div class="row">
                    @foreach ($achievements as $achievement)
                        <div class="col-lg-3 col-md-6">
                            <div class="counter-item">
                                <div class="counter-inner">
                                    <div class="counter-wrap">
                                        <span class="counter">{{ $achievement->count }}</span>
                                        <span class="s">+</span>
                                    </div>
                                    <h6 class="title">{{ $achievement->title }}</h6>
                                </div>
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
                <div class="section-heading text-center mb-50">
                    <span class="tagline">{{ $home_text->portfolio_title ?? __('Portfolios') }}</span>
                    <h2 class="title">{{ $home_text->portfolio_subtitle ?? __('Portfolios') }}</h2>
                </div>
                <div class="project-filter">
                    <ul>
                        <li data-filter="*" class="active">All</li>
                        @foreach ($portfolio_categories as $portfolio_category)
                            <li data-filter=".cat-{{ $portfolio_category->id }}">{{ $portfolio_category->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="project-loop row">
                    @if (count($portfolios) > 0)
                        @foreach ($portfolios as $portfolio)
                            <div class="col-lg-3 col-md-6 cat-{{ $portfolio->bcategory->id ?? '' }}">
                                <div class="project-item">
                                    <div class="project-thumbnail">
                                        <img class="lazy"
                                            data-src="{{ asset('assets/front/img/user/portfolios/' . $portfolio->image) }}"
                                            alt="ProjectImage">
                                    </div>
                                    <div class="hover-content">
                                        <div class="content-inner">
                                            <a href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}"
                                                class="plus-icon"></a>
                                            <a href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}"
                                                class="title">{{ strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
                    <span class="tagline">{{ $home_text->testimonial_title ?? __('Testimonials') }}</span>
                    <h2 class="title">{{ $home_text->testimonial_subtitle ?? __('Testimonials') }}</h2>
                </div>
                <div class="row testimonial-slider">
                    @foreach ($testimonials as $testimonial)
                        <div class="col">
                            <div class="testimonial-box">
                                <div class="author-photo">
                                    <img class="lazy"
                                        data-src="{{ asset('assets/front/img/user/testimonials/' . $testimonial->image) }}"
                                        alt="Image">
                                    <svg width="117" height="114" viewBox="0 0 117 114">
                                        <path
                                            d="M89.8169 85.345L65.5127 98.7562C60.3865 101.585 54.145 101.463 49.1422 98.4367L25.3831 84.1077C20.3803 81.0815 17.3725 75.6305 17.4795 69.7846L18.0246 42.0444C18.1316 36.1985 21.3562 30.8531 26.4824 28.0244L50.7866 14.6132C55.9128 11.7846 62.1543 11.9065 67.1571 14.9327L90.9162 29.2617C95.919 32.2879 98.9268 37.7389 98.8198 43.5848L98.2747 71.325C98.1677 77.1709 94.9431 82.5163 89.8169 85.345Z" />
                                    </svg>
                                </div>
                                <div class="author-info">
                                    <h5 class="name">{{ $testimonial->name }}</h5>
                                    @if (!empty($testimonial->occupation))
                                        <span class="title">{{ $testimonial->occupation }}</span>
                                    @endif
                                </div>
                                <p class="content">{!! nl2br($testimonial->content) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== Testimonial Section End ======-->
    @endif
    @if (is_array($userPermissions) && in_array('Blog', $userPermissions))
        <!--====== Blog Section Start ======-->
        <section class="blog-section section-gap">
            <div class="container">
                <div class="section-heading text-center mb-20">
                    <span class="tagline">{{ $home_text->blog_title ?? __('Blogs') }}</span>
                    <h2 class="title">{{ $home_text->blog_subtitle ?? 'Blogs' }}</h2>
                </div>
                <div class="row justify-content-center latest-blog-loop">
                    @foreach ($blogs as $blog)
                        <div class="col-lg-4 col-md-6 col-sm-10">
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
                                        <a><i class="fas fa-user"></i>{{ $keywords['by'] ?? 'by' }}
                                            {{ $user->username }}</a>
                                    </li>
                                    <li>
                                        <a><i
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
    @if (is_array($userPermissions) && in_array('Contact', $userPermissions))
        <!--====== Contact Section Start ======-->
        <section class="contact-section section-gap-bottom">
            <div class="container">
                <div class="section-heading text-center mb-50">
                    <span class="tagline">{{ $home_text->contact_title ?? __('Get in touch') }}</span>
                    <h2 class="title">{{ $home_text->contact_subtitle ?? __('Get in touch') }}</h2>
                </div>
                <div class="contact-form">
                    <form action="{{ route('front.contact.message', [getParam()]) }}" enctype="multipart/form-data"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="input-field mb-30">
                                    <label for="name">{{ $keywords['Name'] ?? 'Name' }}</label>
                                    <input type="text" placeholder="{{ $keywords['Name'] ?? 'Name' }}"
                                        name="fullname" required id="name">
                                    @if ($errors->has('fullname'))
                                        <p class="text-danger mb-0">{{ $errors->first('fullname') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="input-field mb-30">
                                    <label for="email">{{ $keywords['Email_Address'] ?? 'Email Address' }}</label>
                                    <input type="email"
                                        placeholder="{{ $keywords['Email_Address'] ?? 'Email Address' }}" name="email"
                                        required id="email">
                                    @if ($errors->has('email'))
                                        <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-field mb-30">
                                    <label for="subject">{{ $keywords['Subject'] ?? 'Subject' }}</label>
                                    <input type="text" placeholder="{{ $keywords['Subject'] ?? 'Subject' }}"
                                        name="subject" required id="subject">
                                    @if ($errors->has('subject'))
                                        <p class="text-danger mb-0">{{ $errors->first('subject') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-field mb-30">
                                    <label for="message">{{ $keywords['Message'] ?? 'Message' }}</label>
                                    <textarea placeholder="{{ $keywords['Message'] ?? 'Message' }}" name="message" id="message"></textarea>
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
    @endif
@endsection
