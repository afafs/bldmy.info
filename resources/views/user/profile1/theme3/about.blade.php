@extends('user.profile1.theme3.layout')

@section('tab-title')
{{$keywords["About"] ?? "About"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->about_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->about_meta_keywords : '')

@section('content')
    <!--====== Start Main Wrapper ======-->
    <div class="main-wrapper inner">
        <section class="about-area">
            @if (!empty($home_text->about_image))
                <div class="about-img">
                    <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->about_image)}}" class="lazy" alt="About Image">
                </div>
            @endif
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-8">
                        <div class="about-content-box">
                            <div class="section-title mb-30">
                                <span class="sub-title">{{$home_text->about_title ?? 'My Resume'}}</span>
                                <h2><span class="light-text">{{$home_text->about_subtitle ?? 'About Me'}}</span></h2>
                            </div>
                            <p>{!! nl2br($home_text->about_content ?? "") !!}
                            </p>

                            @if(is_array($userPermissions) && in_array('Achievements',$userPermissions))
                            <div class="counter-wrapper d-flex">
                                @foreach ($achievements as $achievement)
                                    <div class="single-counter text-center">
                                        <h2 class="number"><span class="count">{{$achievement->count}}</span><span class="sm-text">+</span></h2>
                                        <h6 class="title">{{$achievement->title}}</h6>
                                    </div>
                                @endforeach
                            </div>
                            @endif


                            @if(isset($userBs->cv))
                            <a href="{{asset('assets/front/img/user/cv/'.$userBs->cv)}}" class="main-btn" download="{{$user->username}}.pdf" target="_blank">
                            {{$keywords["Download_CV"] ?? "Download CV"}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div><!--====== End Main Wrapper ======-->
@endsection