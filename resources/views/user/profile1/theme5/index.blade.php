@extends('user.profile1.theme5.layout')

@section('tab-title')
{{$keywords["Home"] ?? "Home"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->home_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->home_meta_keywords : '')

@section('content')
    <!--====== Start Hero Section ======-->
    <div class="main-wrapper">
        <!--====== Start Hero Section ======-->
        @php
            if (!empty($home_text->hero_image)) {
                $heroImg = 'assets/front/img/user/home_settings/'.$home_text->hero_image;
            } else {
                $heroImg = 'assets/front/img/profile1/theme5/hero-bg.jpg';
            }
        @endphp
        <section class="hero-area">
            <div class="hero-wrapper-one bg_cover lazy" data-bg="{{asset("$heroImg")}}">
                <div id="particles-js"></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero-content text-center">
                                <h1>{{$home_text->first_name ?? $user->first_name}} {{$home_text->last_name ?? $user->last_name}}</h1>
                                <h4>{{$keywords["Hi_I'm,"] ?? "Hi I'm,"}} <span id="typed"></span></h4>

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
                                <a href="#contact" class="main-btn filled-btn">{{$keywords["Hire_me"] ?? 'Hire me'}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== End Hero Section ======-->
        <!--====== Start About Section ======-->
        <section class="about-area pb-80 pt-120">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-img-box mb-40">
                            @if (!empty($home_text->about_image))
                                <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->about_image)}}" class="lazy" alt="About Image">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-content-box mb-40">
                            <div class="section-title mb-20">
                                <span class="sub-title">{{$home_text->about_title ?? 'My Resume'}}</span>
                                <h2>{{$home_text->about_subtitle ?? 'About Me'}}</h2>
                            </div>
                            <p>{!! nl2br($home_text->about_content ?? "") !!}</p>
                            @if(isset($userBs->cv))
                                <a href="{{asset('assets/front/img/user/cv/'.$userBs->cv)}}" class="main-btn" download="{{$user->username}}.pdf">{{$keywords["Download_CV"] ?? "Download CV"}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== End About Section ======-->

        @if(is_array($userPermissions) && in_array('Skill',$userPermissions))
        <!--====== Start Skills Section ======-->
        <section class="skills-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="skills-content-box">
                            <div class="section-title mb-20">
                                <span class="sub-title">{{$home_text->skills_title ?? __('Skills')}}</span>
                                <h2>{{$home_text->skills_subtitle ?? __('Technical Skills')}}</h2>
                            </div>
                            <p>{!! nl2br($home_text->skills_content ?? "") !!}</p>
                            <ul class="skill-list">
                                @foreach($skills as $skill)
                                    <li class="single-skill">
                                        <h5>{{$skill->title}} <span>{{$skill->percentage}}%</span></h5>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{$skill->percentage}}%; background-color: #{{$skill->color}}"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="skills-img-box">
                            @isset($home_text->skills_image)
                                <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->skills_image)}}" class="lazy" alt="Image">
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== End Skills Section ======-->
        @endif

        @if(is_array($userPermissions) && in_array('Service',$userPermissions))
            <!--====== Start Service Section ======-->
            <section class="service-area pt-120 pb-80">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-45">
                                <span class="sub-title">{{$home_text->service_title ?? __('Services')}}</span>
                                <h2>{{$home_text->service_subtitle ?? __('What I Do ?')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($services as $service)
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="service-item service-item-one mb-40">
                                    <a class="service-img d-block" href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}">
                                        <img data-src="{{isset($service->image) ? asset('assets/front/img/user/services/'.$service->image) : asset('assets/front/img/profile/service-1.jpg')}}" class="lazy" alt="Service Image">
                                    </a>
                                    <div class="service-content">
                                        <h4 class="title"><a href="{{route('front.user.service.detail',[getParam(),'slug' => $service->slug,'id' => $service->id])}}">{{$service->name}}</a></h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section><!--====== End Service Section ======-->
        @endif

        @if(is_array($userPermissions) && in_array('Experience',$userPermissions))
            <!--====== Start Resume Section ======-->
            <section class="resume-area light-bg pt-120 pb-200">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-45">
                                <span class="sub-title">{{$home_text->experience_title ?? __('Experience')}}</span>
                                <h2>{{$home_text->experience_subtitle ?? __('Experience')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        @if (count($educations) > 0)
                            <div class="col-lg-5">
                                <div class="resume-title text-center">
                                    <h5>{{$keywords["Education"] ?? "Education"}}</h5>
                                </div>
                                @foreach($educations as $education)
                                    <div class="resume-item mb-30">
                                        <div class="resume-content">
                                            <h5>{{$education->degree_name}}</h5>
                                            <span class="date">
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
                        @endif

                        @if (count($educations) > 0 && count($job_experiences) > 0)
                            <div class="col-lg-2">
                                <div class="resume-line text-center"><img class="lazy" data-src="{{asset('assets/front/img/profile1/theme5/line.png')}}" alt="line image"></div>
                            </div>
                        @endif


                        @if (count($job_experiences) > 0)

                            <div class="col-lg-5">
                                <div class="resume-title text-center">
                                    <h5>{{$keywords["Job"] ?? "Job"}}</h5>
                                </div>
                                @foreach($job_experiences as $job_experience)
                                    <div class="resume-item mb-30">
                                        <div class="resume-content">
                                            <h5>{{$job_experience->designation}} [{{$job_experience->company_name}}]</h5>
                                            <span class="date">
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
                        @endif
                    </div>
                </div>
            </section>
            <!--====== End Resume Section ======-->
        @endif

        
        @if(is_array($userPermissions) && in_array('Achievements',$userPermissions))
            <!--====== Start Counter Section ======-->
            <section class="counter-area">
                <div class="container">
                    <div class="counter-wrapper-one bg_cover lazy" data-bg="{{!empty($home_text->achievement_image) ? asset('assets/front/img/user/home_settings/' . $home_text->achievement_image) : asset('assets/front/img/profile1/theme4/counter-bg.jpg')}}">
                        <div class="row">
                            @foreach ($achievements as $achievement)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="counter-item counter-item-one text-center mb-30">
                                        <div class="content">
                                            <h2><span class="count">{{$achievement->count}}</span>+</h2>
                                            <h5>{{$achievement->title}}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            <!--====== End Counter Section ======-->
        @endif

        
        @if(is_array($userPermissions) && in_array('Portfolio',$userPermissions))
            <!--====== Start Project Section ======-->
            <section class="portfolio-area pt-120 pb-90" id="masonry-portfolio">
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
                                <ul class="filter-btn mb-60 wow fadeInUp">
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
                            <div class="col-lg-6 portfolio-column cat-{{$portfolio->bcategory->id}}">
                                <div class="portfolio-item portfolio-item-one mb-30">
                                    <div class="portfolio-img">
                                        <a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}" class="d-block">
                                            <img src="{{asset('assets/front/img/user/portfolios/'.$portfolio->image)}}" alt="Image">
                                        </a>
                                        <div class="portfolio-content">
                                            <h4 class="title"><a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}">{{strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title}}</a></h4>
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
            <section class="testimonial-area pt-120 pb-120 light-bg">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-45">
                                <span class="sub-title">{{$home_text->testimonial_title ?? __('Testimonials')}}</span>
                                <h2>{{$home_text->testimonial_subtitle ?? __('Testimonials')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slider-one">
                        @foreach($testimonials as $testimonial)
                            <div class="testimonial-item testimonial-item-one">
                                <div class="testimonial-content">
                                    <div class="tm-author-info d-flex">
                                        <div class="author-thumb">
                                            <img src="{{asset('assets/front/img/user/testimonials/'.$testimonial->image)}}" alt="">
                                        </div>
                                        <div class="author-info">
                                            <h5>{{$testimonial->name}}</h5>
                                            @if (!empty($testimonial->occupation))
                                            <span class="position">{{$testimonial->occupation}}</span>
                                            @endif
                                        </div>
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
            <section class="blog-area pt-120 pb-80">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-45">
                                <span class="sub-title">{{$home_text->blog_title ?? __('Blogs')}}</span>
                                <h2>{{$home_text->blog_subtitle ?? "Blogs"}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($blogs as $blog)
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="blog-post-item blog-post-item-one mb-40">
                                    <a class="post-thumbnail d-block" href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">
                                        <img class="lazy" data-src="{{asset('assets/front/img/user/blogs/'.$blog->image)}}" alt="Blog Image">
                                    </a>
                                    <div class="entry-content">
                                        <h3 class="title"><a href="{{route('front.user.blog.detail', [getParam(), $blog->slug, $blog->id])}}">{{strlen($blog->title) > 45 ? mb_substr($blog->title, 0, 45, 'UTF-8') . '...' : $blog->title}}</a></h3>
                                        <div class="post-meta">
                                            <ul>
                                                <li><span><i class="fas fa-user"></i>{{$keywords['by'] ?? "by"}} <a>{{$user->username}}</a></span></li>
                                                <li><span><i class="fas fa-calendar-alt"></i><a>{{\Carbon\Carbon::parse($blog->created_at)->format('F j, Y')}}</a></span></li>
                                            </ul>
                                        </div>
                                        <p>{!! strlen(strip_tags($blog->content)) > 100 ? mb_substr(strip_tags($blog->content), 0, 100, 'utf-8') . '...' : strip_tags($blog->content) !!}</p>
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
            <section id="contact" class="contact-area light-bg pt-120 pb-120">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-title text-center mb-45">
                                <span class="sub-title">{{$home_text->contact_title ??  __('Get in touch')}}</span>
                                <h2>{{$home_text->contact_subtitle ?? __('Get in touch')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <div class="contact-form-wrap">
                                <form action="{{route('front.contact.message', [getParam()])}}" enctype="multipart/form-data" method="post">
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