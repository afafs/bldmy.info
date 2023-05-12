@extends('user.profile1.theme4.layout')

@section('tab-title')
{{$keywords["Home"] ?? "Home"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->home_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->home_meta_keywords : '')

@section('content')

    <!--====== Start Hero Section ======-->
    <section class="hero-area" id="home">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-1.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-2.png')}}" class="lazy shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/h-ob-1.png')}}" class="lazy shape shape-three" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/h-ob-2.png')}}" class="lazy shape shape-four" alt="">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="hero-content">
                        <h1><span class="sm-text">{{$keywords["Hi_I'm,"] ?? "Hi I'm,"}}</span><span class="ht-b">{{$home_text->first_name ?? $user->first_name}}</span><span class="ht-l">{{$home_text->last_name ?? $user->last_name}}</span></h1>

                        <h3>{{$keywords["I_am"] ?? "I am"}} <span id="typed"></span></h3>

                        @php
                        $designations = explode(",",$home_text->designation ?? "");
                        @endphp

                        @if (!empty($designations))
                            <div class="type-string">
                                @foreach($designations as $designation)
                                <p>{{$designation}}</p>
                                @endforeach
                            </div>
                        @endif

                        @if(is_array($userPermissions) && in_array('Contact',$userPermissions))
                        <a href="#contact" class="main-btn arrow-btn">{{$keywords["Hire_me"] ?? 'Hire me'}}</a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img">
                        <div class="image-shape">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-3.png')}}" class="lazy shape shape-one" alt="">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-1.png')}}" class="lazy shape shape-two" alt="">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-1.png')}}" class="lazy shape shape-three" alt="">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-2.png')}}" class="lazy shape shape-four" alt="">
                        </div>
                        @if(isset($home_text->hero_image))
                        <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->hero_image)}}" class="lazy" alt="Hero Image">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Hero Section ======-->
    <!--====== Start About Section ======-->
    <section class="about-area pt-70 pb-70">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-5">
                    <div class="about-img text-center mb-50">
                        <div class="image-shape">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-1.png')}}" class="lazy shape shape-one" alt="">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-2.png')}}" class="lazy shape shape-two" alt="">
                            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-1.png')}}" class="lazy shape shape-three" alt="">
                            
                        </div>
                        @if (!empty($home_text->about_image))
                            <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->about_image)}}" class="lazy" alt="About Image">
                        @endif
                        <div class="about-shape"></div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="about-content-box mb-50">
                        <div class="section-title mb-20">
                            <span class="sub-title">{{$home_text->about_title ?? 'My Resume'}}</span>
                            <h2>{{$home_text->about_subtitle ?? 'About Me'}}</h2>
                        </div>
                        <p>{!! nl2br($home_text->about_content ?? "") !!}</p>

                        @if(isset($userBs->cv))
                            <a href="{{asset('assets/front/img/user/cv/'.$userBs->cv)}}" class="btn-link" download="{{$user->username}}.pdf">{{$keywords["Download_CV"] ?? "Download CV"}}<span class="btn-cirle"></span></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End About Section ======-->

    @if(is_array($userPermissions) && in_array('Skill',$userPermissions))
    <!--====== Start Skills Section ======-->
    <section class="skill-area pt-130 pb-80">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-3.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-4.png')}}" class="lazy shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/h-ob-1.png')}}" class="lazy shape shape-three" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/h-ob-2.png')}}" class="lazy shape shape-four" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/h-ob-1.png')}}" class="lazy shape shape-five" alt="">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="skill-img mb-50">
                        @isset($home_text->skills_image)
                            <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->skills_image)}}" class="lazy" alt="Image">
                        @endisset
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="skill-content-box mb-20">
                        <div class="section-title mb-50">
                            <span class="sub-title">{{$home_text->skills_title ?? __('Skills')}}</span>
                            <h2 class="mb-20">{{$home_text->skills_subtitle ?? __('Technical Skills')}}</h2>
                            <p>{!! nl2br($home_text->skills_content ?? "") !!}</p>
                        </div>
                        <div class="skill-wrapper">
                            @foreach($skills as $skill)
                                <div class="skill-item text-center mb-30">
                                    <div class="skill-circle">
                                        <div data-donutty data-radius="20" data-thickness="40" data-padding="6" data-round="false" data-bg="rgba(244, 190, 29, 0.22)" data-color="#{{$skill->color}}" data-value="{{$skill->percentage}}"></div>
                                        
                                    </div>
                                    <img data-src="{{asset('assets/front/img/profile1/theme4/objects/shadow.png')}}" class="lazy img-shadow mb-15" alt="shadow">
                                    <h5>{{$skill->title}}<span class="percentage">{{$skill->percentage}}%</span></h5>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Skills Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Service',$userPermissions))
    <!--====== Start Service Section ======-->
    <section class="service-area pt-110 pb-90" id="service">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-14.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-15.png')}}" class="lazy shape shape-two" alt="">
        </div>
        <div class="service-after-bg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center mb-50">
                        <span class="sub-title">{{$home_text->service_title ?? __('Services')}}</span>
                        <h2>{{$home_text->service_subtitle ?? __('What I Do ?')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($services as $service)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="service-item text-center mb-40">
                            <a class="service-img d-block" href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}">
                                <img data-src="{{isset($service->image) ? asset('assets/front/img/user/services/'.$service->image) : asset('assets/front/img/profile/service-1.jpg')}}" class="lazy" alt="Service Image">
                            </a>
                            <div class="service-content">
                                @if($service->detail_page == 1)
                                <h3 class="title"><a href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}">{{$service->name}}</a></h3>
                                @else
                                <h3 class="title">{{$service->name}}</h3>
                                @endif
                                <a @if($service->detail_page == 1) href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}" @endif class="btn-link">{{$keywords["Read_More"] ?? "Read More"}} <span class="btn-cirle"></span></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Service Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Experience',$userPermissions))
    <!--====== Start Resume Section ======-->
    <section class="resume-area pb-90">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-3.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-1.png')}}" class="lazy shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-6.png')}}" class="lazy shape shape-three" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-7.png')}}" class="lazy shape shape-four" alt="">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title text-center mb-70">
                        <span class="sub-title">{{$home_text->experience_title ?? __('Experience')}}</span>
                        <h2>{{$home_text->experience_subtitle ?? __('Experience')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @if (count($educations) > 0)
                    <div class="col-lg-6">
                        <div class="resume-wrapper">
                            <div class="resume-title text-center mb-50">
                                <h4 class="title title-one">{{$keywords["Education"] ?? "Education"}}</h4>
                            </div>
                            @foreach($educations as $education)
                                <div class="resume-item">
                                    <div class="resume-content">
                                        <h5>{{$education->degree_name}}</h5>
                                        <span class="date date-bg-one">
                                            {{\Carbon\Carbon::parse($education->start_date)->format('M j, Y')}}
                                            -
                                            @if (!empty($education->end_date))
                                                {{ \Carbon\Carbon::parse($education->end_date)->format('M j, Y') }}
                                            @else
                                                {{$keywords["Present"] ?? "Present"}}
                                            @endif
                                        </span>
                                        <p>{!! nl2br($education->short_description) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if (count($job_experiences) > 0)

                    <div class="col-lg-6">
                        <div class="resume-wrapper">
                            <div class="resume-title text-center mb-50">
                                <h4 class="title title-two">{{$keywords["Job"] ?? "Job"}}</h4>
                            </div>
                            @foreach($job_experiences as $job_experience)
                                <div class="resume-item">
                                    <div class="resume-content">
                                        <h5>{{$job_experience->designation}} [{{$job_experience->company_name}}]</h5>
                                        <span class="date date-bg-one">
                                            {{\Carbon\Carbon::parse($job_experience->start_date)->format('M j, Y')}} - 
                                            @if ($job_experience->is_continue == 0)
                                            {{ \Carbon\Carbon::parse($job_experience->end_date)->format('M j, Y') }}
                                            @else
                                            {{$keywords["Present"] ?? "Present"}}
                                            @endif
                                        </span>
                                        <p>{!! nl2br($job_experience->content) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!--====== End Resume Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Achievements',$userPermissions))
    <!--====== Start Counter Section ======-->
    <section class="counter-area bg_cover pt-120 pb-85 lazy" data-bg="{{!empty($home_text->achievement_image) ? asset('assets/front/img/user/home_settings/' . $home_text->achievement_image) : asset('assets/front/img/profile1/theme4/counter-bg.jpg')}}">
        <div class="container">
            <div class="row">
                @foreach ($achievements as $achievement)
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="counter-item text-center">
                            <h2 class="number"><span class="count">{{$achievement->count}}</span><span class="sm-text">+</span></h2>
                            <h5>{{$achievement->title}}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Counter Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Portfolio',$userPermissions))
    <!--====== Start Project Section ======-->
    <section class="portfolio-area pt-120 pb-90" id="masonry-portfolio">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-13.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-8.png')}}" class="lazy shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-7.png')}}" class="lazy shape shape-three" alt="">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title text-center mb-45">
                        <span class="sub-title">{{$home_text->portfolio_title ?? __('Portfolios')}}</span>
                        <h2>{{$home_text->portfolio_subtitle ??  __('Portfolios')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="portfolio-filter-button text-center">
                        <ul class="filter-btn mb-30">
                            <li data-filter="*" class="active">{{$keywords["All"] ?? "All"}}</li>
                            @foreach($portfolio_categories as $portfolio_category)
                            <li data-filter=".cat-{{$portfolio_category->id}}">{{$portfolio_category->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row masonry-row">
                @foreach($portfolios as $portfolio)
                    <div class="col-lg-4 col-md-6 col-sm-12 portfolio-column cat-{{$portfolio->bcategory->id}}">
                        <div class="portfolio-item mb-30">
                            <div class="portfolio-img">
                                <img src="{{asset('assets/front/img/user/portfolios/'.$portfolio->image)}}" alt="Image">
                                <div class="portfolio-overlay">
                                    <div class="portfolio-content">
                                        <h4 class="title"><a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}">{{strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title}}</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Project Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Testimonial',$userPermissions))
    <!--====== Start Testimonial Section ======-->
    <section class="testimonial-area light-bg pt-120 pb-130">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-2.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/h-ob-1.png')}}" class="lazy shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-9.png')}}" class="lazy shape shape-three" alt="">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center mb-60">
                        <span class="sub-title">{{$home_text->testimonial_title ?? __('Testimonials')}}</span>
                        <h2>{{$home_text->testimonial_subtitle ?? __('Testimonials')}}</h2>
                    </div>
                </div>
            </div>
            <div class="testimonial-slider-one">
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-item item-one">
                        <div class="wt-thumb">
                            <img src="{{asset('assets/front/img/user/testimonials/'.$testimonial->image)}}" alt="">
                            <svg width="130" height="125" viewBox="0 0 117 114">
                                <path fill="#F57236" d="M89.8169 85.345L65.5127 98.7562C60.3865 101.585 54.145 101.463 49.1422 98.4367L25.3831 84.1077C20.3803 81.0815 17.3725 75.6305 17.4795 69.7846L18.0246 42.0444C18.1316 36.1985 21.3562 30.8531 26.4824 28.0244L50.7866 14.6132C55.9128 11.7846 62.1543 11.9065 67.1571 14.9327L90.9162 29.2617C95.919 32.2879 98.9268 37.7389 98.8198 43.5848L98.2747 71.325C98.1677 77.1709 94.9431 82.5163 89.8169 85.345Z"/>
                            </svg>
                        </div>
                        <div class="testimonial-content">
                            <div class="author-info">
                                <h5>{{$testimonial->name}}</h5>
                                @if (!empty($testimonial->occupation))
                                <span class="location">{{$testimonial->occupation}}</span>
                                @endif
                            </div>
                            <p>{!! nl2br($testimonial->content) !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Testimonial Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Blog',$userPermissions))
    <!--====== Start Blog Section ======-->
    <section class="blog-area pt-110 pb-90" id="blog">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-6.png')}}" class="shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-5.png')}}" class="shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-8.png')}}" class="shape shape-three" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-12.png')}}" class="shape shape-four" alt="">
        </div>
        <div class="container">4
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center mb-60">
                        <span class="sub-title">{{$home_text->blog_title ?? __('Blogs')}}</span>
                        <h2>{{$home_text->blog_subtitle ?? "Blogs"}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="blog-post-item mb-40">
                            <a class="post-thumbnail d-block" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                                <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Blog Image">
                            </a>
                            <div class="entry-content">
                                <div class="post-meta">
                                    <ul>
                                        <li><span><i class="fas fa-user"></i>{{$keywords['by'] ?? "by"}} <a>{{$user->username}}</a></span></li>
                                        <li><span><i class="fas fa-calendar-alt"></i><a>{{\Carbon\Carbon::parse($blog->created_at)->format('F j, Y')}}</a></span></li>
                                    </ul>
                                </div>
                                <h3 class="title"><a href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">{{strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title}}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Blog Section ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Contact',$userPermissions))
    <!--====== Start Contact Section ======-->
    <section class="contact-area light-bg pt-110 pb-120" id="contact">
        <div class="objects-shape">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/circle-3.png')}}" class="lazy shape shape-one" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-2.png')}}" class="lazy shape shape-two" alt="">
            <img data-src="{{asset('assets/front/img/profile1/theme4/objects/ob-11.png')}}" class="lazy shape shape-three" alt="">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center mb-50">
                        <span class="sub-title">{{$home_text->contact_title ??  __('Get in touch')}}</span>
                        <h2>{{$home_text->contact_subtitle ?? __('Get in touch')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact-wrapper">
                        <form class="contact-form" action="{{route('front.contact.message', [getParam()])}}" enctype="multipart/form-data" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form_group">
                                        <input type="text" class="form_control" placeholder="{{$keywords["Name"] ?? "Name"}}" name="fullname" required>
                                        @if ($errors->has('fullname'))
                                            <p class="text-danger mb-0">{{$errors->first('fullname')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form_group">
                                        <input type="email" class="form_control" placeholder="{{$keywords["Email_Address"] ?? "Email Address"}}" name="email" required>
                                        @if ($errors->has('email'))
                                            <p class="text-danger mb-0">{{$errors->first('email')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form_group">
                                        <input type="text" class="form_control" placeholder="{{$keywords["Subject"] ?? "Subject"}}" name="subject" required>
                                        @if ($errors->has('subject'))
                                            <p class="text-danger mb-0">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form_group">
                                        <textarea class="form_control" placeholder="{{$keywords["Message"] ?? "Message"}}" name="message"></textarea>
                                        @if ($errors->has('message'))
                                            <p class="text-danger mb-0">{{$errors->first('message')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form_group text-center">
                                        <button type="submit" class="main-btn arrow-btn">{{$keywords["Send_Message"] ?? "Send Message"}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Contact Section ======-->    
    @endif
@endsection