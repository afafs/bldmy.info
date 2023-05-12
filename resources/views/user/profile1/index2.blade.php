@extends('user.profile1.layout')

@section('tab-title')
{{$keywords["Home"] ?? "Home"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->home_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->home_meta_keywords : '')

@section('content')
    <!--====== Hero Area Start ======-->
    <section class="hero-section hero-section-two">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="hero-text">
                        <span class="tagline">{{$keywords["Hi_I'm,"] ?? "Hi I'm,"}}</span>
                        <h1 class="hero-title">{{$home_text->first_name ?? $user->first_name}} {{$home_text->last_name ?? $user->last_name}}</h1>
                        <div><p id="typed" class="d-inline-block"></p></div>
                        <div class="type-string">
                            @php
                            $designations = explode(",",$home_text->designation ?? "");
                            @endphp
                            @foreach($designations as $designation)
                            <p>{{$designation}}</p>
                            @endforeach
                        </div>
                        @if(isset($social_medias))
                        <ul class="social-links">
                            @foreach($social_medias as $social_media)
                            <li>
                                <a href="{{$social_media->url}}" target="_blank"><i class="{{$social_media->icon}}"></i></a>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        @if(is_array($userPermissions) && in_array('Contact',$userPermissions))
                        <a href="#contact" class="template-btn">{{$keywords["Hire_me"] ?? 'Hire me'}}</a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 col-sm-9">
                    <div class="hero-img text-center">
                        @if(isset($home_text->hero_image))
                        <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->hero_image)}}" class="animate-float-bob-y lazy" alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== Hero Area End ======-->

    <!--====== About Section Start ======-->
    <section class="about-section section-gap" id="about">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-sm-9">
                    <div class="block-img text-center pr-0 pr-lg-5 md-gap-50">
                        <img data-src="{{isset($home_text->about_image) ? asset('assets/front/img/user/home_settings/'.$home_text->about_image) : asset('assets/front/img/profile/about.png')}}" class="animate-float-bob-y lazy" alt="About Image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-10">
                    <div class="about-content">
                        <div class="common-heading mb-30">
                            <span class="tagline-two">{{$home_text->about_title ?? 'My Resume'}}</span>
                            <h2 class="title">{{$home_text->about_subtitle ?? 'About Me'}}</h2>
                        </div>
                        <p class="mb-30">
                            {!! nl2br($home_text->about_content ?? "") !!}
                        </p>
                        @if(isset($userBs->cv))
                        <a href="{{asset('assets/front/img/user/cv/'.$userBs->cv)}}" class="template-btn mt-30" download="{{$user->username}}.pdf" target="_blank">
                        {{$keywords["Download_CV"] ?? "Download CV"}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End About Section ======-->

    @if(is_array($userPermissions) && in_array('Skill',$userPermissions))
    <!--====== Skill Section Start ======-->
    <section class="skill-section section-gap" id="skill">
        <div class="container">
            <div class="row align-items-center justify-content-lg-between justify-content-center">
                <div class="col-lg-6 order-lg-last col-sm-9">
                    <div class="block-img pl-0 pl-xl-5 md-gap-50">
                        <img data-src="{{isset($home_text->skills_image) ? asset('assets/front/img/user/home_settings/'.$home_text->skills_image) : asset('assets/front/img/profile/skill.png')}}" class="animate-float-bob-y lazy" alt="Image">
                    </div>
                </div>
                <div class="col-lg-6 order-lg-first col-md-9">
                    <div class="skill-content pr-0 pr-lg-4">
                        <div class="common-heading mb-30">
                            <span class="tagline-two">{{$home_text->skills_title ?? __('Skills')}}</span>
                            <h2 class="title">{{$home_text->skills_subtitle ?? __('Technical Skills')}}</h2>
                        </div>
                        <p class="text-big">{!! nl2br($home_text->skills_content ?? "") !!} </p>
                        <div class="pie-charts mt-60">
                            @foreach($skills as $skill)
                            <div class="single-pie-chart">
                                <div class="chart" data-percent="{{$skill->percentage}}" data-track-color="#{{$skill->color}}2a" data-bar-color="#{{$skill->color}}"></div>
                                <div class="chart-inner">
                                    <div class="counter-wrap">
                                        <span class="counter">{{$skill->percentage}}</span>
                                        <span class="suffix">%</span>
                                    </div>
                                    <h4 class="title">{{$skill->title}}</h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== Skill Section End ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Service',$userPermissions))
    <!--====== Service Section Start ======-->
    <section class="service-section section-gap bg-offwhite-color" id="service">
        <div class="container">
            <div class="common-heading text-center mb-20">
                <span class="tagline-two">{{$home_text->service_title ?? __('Services')}}</span>
                <h2 class="title">{{$home_text->service_subtitle ?? __('What I Do ?')}}</h2>
            </div>
            <div class="row service-boxes justify-content-center">
                @foreach($services as $service)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="service-box-two mt-30">
                        <a class="service-thumb" @if($service->detail_page == 1) href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}" @endif>
                            <img data-src="{{isset($service->image) ? asset('assets/front/img/user/services/'.$service->image) : asset('assets/front/img/profile/service-1.jpg')}}" class="lazy" alt="">
                        </a>
                        @if($service->detail_page == 1)
                        <h4 class="title"><a href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</a>
                        </h4>
                        <a href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}" class="read-more-btn">{{$keywords["Read_More"] ?? "Read More"}}</a>
                        @else
                        <h4 class="title">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</h4>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== Service Section End ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Experience',$userPermissions))
    <!--====== Experience Section Start ======-->
    <section class="experience-section section-gap" id="experience">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9">
                    <div class="common-heading text-center mb-60">
                        <span class="tagline-two">{{$home_text->experience_title ?? __('Experience')}}</span>
                        <h2 class="title">{{$home_text->experience_subtitle ?? __('Experience')}}</h2>
                    </div>
                </div>
            </div>
            <div class="experience-wrapper">
                @if (count($educations) > 0)
                <h3 class="experience-wrapper-title title-icon-boxed">
                    <span><img class="lazy" data-src="{{asset('assets/front/img/profile1/education.png')}}" alt=""></span>
                    {{$keywords["Education"] ?? "Education"}}
                </h3>

                <div class="experience-boxes row justify-content-center">
                    
                    @foreach($educations as $education)
                    <div class="col-lg-4 col-md-6 col-sm-8">
                        <div class="single-experience mt-30">
                            <div class="experience-inner">
                                <span class="duration">
                                    {{\Carbon\Carbon::parse($education->start_date)->format('M j, Y')}}
                                    -
                                    @if (!empty($education->end_date))
                                        {{ \Carbon\Carbon::parse($education->end_date)->format('M j, Y') }}
                                    @else
                                        {{$keywords["Present"] ?? "Present"}}
                                    @endif
                                </span>
                                <h5 class="title">{{$education->degree_name}}</h5>
                                <p>{!! nl2br($education->short_description) !!}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
                @endif

                @if (count($educations) > 0 && count($job_experiences) > 0)
                <div class="experience-gap"></div>
                @endif

                @if (count($job_experiences) > 0)
                <h3 class="experience-wrapper-title title-icon-boxed">
                    <span><img class="lazy" data-src="{{asset('assets/front/img/profile1/job.png')}}" alt=""></span>
                    {{$keywords["Job"] ?? "Job"}}
                </h3>

                <div class="experience-boxes row justify-content-center">
                    @foreach($job_experiences as $job_experience)
                    <div class="col-lg-4 col-md-6 col-sm-8">
                        <div class="single-experience mt-30">
                            <div class="experience-inner">
                                <span class="duration">
                                    {{\Carbon\Carbon::parse($job_experience->start_date)->format('M j, Y')}} - 
                                    @if ($job_experience->is_continue == 0)
                                    {{ \Carbon\Carbon::parse($job_experience->end_date)->format('M j, Y') }}
                                    @else
                                    {{$keywords["Present"] ?? "Present"}}
                                    @endif
                                </span>
                                <h5 class="title">{{$job_experience->designation}} [{{$job_experience->company_name}}]</h5>
                                <p>{!! nl2br($job_experience->content) !!}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    <!--====== Experience Section End ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Achievements',$userPermissions))
    <!--====== Counter Section Start ======-->
    <section class="counter-section lazy" data-bg="{{!empty($home_text->achievement_image) ? asset('assets/front/img/user/home_settings/' . $home_text->achievement_image) : asset('assets/front/img/achievement_bg.jpg')}}">
        <div class="container">
            <div class="row">
                @foreach ($achievements as $achievement)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter-box mb-40">
                        <div class="counter-wrap">
                            <span class="count">
                                {{$achievement->count}}
                            </span>
                            <span class="suffix">+</span>
                        </div>
                        <h6 class="title">{{$achievement->title}}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== Counter Section End ======-->
    @endif
    

    @if(is_array($userPermissions) && in_array('Portfolio',$userPermissions))
    <!--====== Portfolios Section Start ======-->
    <section class="portfolio-section section-gap" id="portfolio">
        <div class="container">
            <div class="common-heading text-center mb-50">
                <span class="tagline-two">{{$home_text->portfolio_title ?? __('Portfolios')}}</span>
                <h2 class="title">{{$home_text->portfolio_subtitle ??  __('Portfolios')}}</h2>
            </div>

            <div class="portfolio-filter-wrap mb-20">
                <ul class="portfolio-filter">
                    <li><a href="#" data-filter="*" class="filter-active">{{$keywords['All'] ?? "All"}}</a></li>
                    @foreach($portfolio_categories as $portfolio_category)
                    <li><a href="#" data-filter=".cat-{{$portfolio_category->id}}">{{$portfolio_category->name}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="row portfolio-items filter-items justify-content-center">
                @foreach($portfolios as $portfolio)
                <div class="col-lg-4 col-md-6 filter-item cat-{{$portfolio->bcategory->id}}">
                    <div class="portfolio-item-two mt-30">
                        <div class="portfolio-thumb">
                            <img src="{{asset('assets/front/img/user/portfolios/'.$portfolio->image)}}" alt="Image">
                            <a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}" class="portfolio-link">
                                <span></span>
                            </a>
                        </div>
                        <div class="portfolio-content">
                            <h4 class="title"><a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}">{{strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title}}</a></h4>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== Portfolios Section End ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Testimonial',$userPermissions))
    <!--====== Testimonial Section Start ======-->
    <section class="testimonial-section section-gap bg-offwhite-color">
        <div class="container">
            <div class="common-heading text-center mb-50">
                <span class="tagline-two">{{$home_text->testimonial_title ?? __('Testimonials')}}</span>
                <h2 class="title">{{$home_text->testimonial_subtitle ?? __('Testimonials')}}</h2>
            </div>
            <div class="row testimonial-slider">
                @foreach($testimonials as $testimonial)
                <div class="col-lg-4">
                    <div class="testimonial-box-two">
                        <div class="author">
                            <img src="{{asset('assets/front/img/user/testimonials/'.$testimonial->image)}}" alt="Author">
                        </div>
                        <div class="content">
                            <span class="quote-icon">
                                <img src="{{asset('assets/front/img/profile1/quote2.png')}}" alt="">
                            </span>
                            <p>
                                {!! nl2br($testimonial->content) !!}
                            </p>
                            <div class="author-info">
                                <h6 class="name">{{$testimonial->name}}</h6>
                                @if (!empty($testimonial->occupation))
                                <span class="title">{{$testimonial->occupation}}</span>
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

    @if(is_array($userPermissions) && in_array('Blog',$userPermissions))
    <!--====== Latest Blog Section Start ======-->
    <section class="latest-blog-section section-gap" id="blog">
        <div class="container">
            <div class="common-heading text-center mb-20">
                <span class="tagline-two">{{$home_text->blog_title ?? __('Blogs')}}</span>
                <h2 class="title">{{$home_text->blog_subtitle ?? "Blogs"}}</h2>
            </div>
            <div class="row justify-content-center">
                @foreach($blogs as $blog)
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="latest-blog-post blog-post-two mt-30">
                        <a class="post-thumb" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                            <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Image">
                        </a>
                        <div class="post-content">
                            <h5 class="post-title">
                                <a href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">{{strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title}}</a>
                            </h5>
                            <ul class="post-meta">
                                <li><a><i class="fas fa-user"></i> {{$keywords['by'] ?? "by"}} {{$user->username}}</a></li>
                                <li><a><i class="fas fa-calendar-alt"></i> {{\Carbon\Carbon::parse($blog->created_at)->format('F j, Y')}}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== Latest Blog Section End ======-->
    @endif

    @if(is_array($userPermissions) && in_array('Contact',$userPermissions))
    <!--====== Contact Section Start ======-->
    <section class="contact-section bg-offwhite-color section-gap" id="contact">
        <div class="container">
            <div class="common-heading text-center mb-50">
                <span class="tagline-two">{{$home_text->contact_title ??  __('Get in touch')}}</span>
                <h2 class="title">{{$home_text->contact_subtitle ?? __('Get in touch')}}</h2>
            </div>

            <div class="contact-form-two">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <form action="{{route('front.contact.message', [getParam()])}}" enctype="multipart/form-data" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="row gutters-20">
                                <div class="col-lg-4 col-md-6">
                                    <div class="input-field mb-20">
                                        <input type="text" placeholder="{{$keywords["Name"] ?? "Name"}}" name="fullname">
                                        @if ($errors->has('fullname'))
                                            <p class="text-danger mb-0">{{$errors->first('fullname')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="input-field mb-20">
                                        <input type="email" placeholder="{{$keywords["Email_Address"] ?? "Email Address"}}" name="email" required>
                                        @if ($errors->has('email'))
                                            <p class="text-danger mb-0">{{$errors->first('email')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-field mb-20">
                                        <input type="text" placeholder="{{$keywords["Subject"] ?? "Subject"}}" name="subject" required>
                                        @if ($errors->has('subject'))
                                            <p class="text-danger mb-0">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-field mb-20">
                                        <textarea placeholder="{{$keywords["Message"] ?? "Message"}}" name="message" required></textarea>
                                        @if ($errors->has('message'))
                                            <p class="text-danger mb-0">{{$errors->first('message')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-field text-center mt-30">
                                        <button type="submit" class="template-btn">{{$keywords["Send_Message"] ?? "Send Message"}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== Contact Section End ======-->
    @endif
@endsection