@extends('user.profile1.theme3.layout')

@section('tab-title')
{{$keywords["Home"] ?? "Home"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->home_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->home_meta_keywords : '')

@section('content')
<div class="main-wrapper">
    <section class="hero-area">
        <div class="hero-img d-lg-block d-none">
            @if(isset($home_text->hero_image))
            <img data-src="{{asset('assets/front/img/user/home_settings/'.$home_text->hero_image)}}" class="lazy" alt="">
            @endif
        </div>
        <div class="container">
            <div class="row align-items-center hero-wrapper">
                <div class="col-xl-6 col-lg-8">
                    <div class="hero-content">
                        <span class="span">{{$keywords["Hi_I'm,"] ?? "Hi I'm,"}}</span>
                        <h1><span class="h-b">{{$home_text->first_name ?? $user->first_name}}</span><span class="h-l">{{$home_text->last_name ?? $user->last_name}}</span></h1>
                        
                        @php
                        $designations = explode(",",$home_text->designation ?? "");
                        @endphp
                        @if (!empty($designations))
                        <h3>{{$keywords["I am"] ?? "I am"}} <span id="typed"></span></h3>
                        <div class="type-string">
                            @foreach($designations as $designation)
                            <p>{{$designation}}</p>
                            @endforeach
                        </div>
                        @endif

                        @if(is_array($userPermissions) && in_array('Contact',$userPermissions))
                        <a href="{{route('front.user.contact', getParam())}}" class="main-btn">{{$keywords["Hire_me"] ?? 'Hire me'}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection