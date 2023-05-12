@extends('user.profile1.' . $layout . '.layout')
@section('tab-title')
    {{ $keywords['Service_Details'] ?? 'Service Details' }}
@endsection
@section('og-meta')
    <meta property="og:image" content="{{ asset('assets/front/img/user/services/' . $service->image) }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="1024">
@endsection
@section('meta-description', $service->meta_description)
@section('meta-keywords', $service->meta_keywords)
@section('br-title')
    {{ $keywords['Service_Details'] ?? 'Service Details' }}
@endsection
@section('br-link')
    {{ $keywords['Service_Details'] ?? 'Service Details' }}
@endsection
@section('content')
    <!--====== Breadcrumbs Start ======-->
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10">
                    <div class="page-title">
                        <h1>{{ $keywords['Service_Details'] ?? 'Service Details' }}</h1>
                        <ul class="breadcrumbs-link">
                            <li><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                            </li>
                            <li class="">{{ $keywords['Service_Details'] ?? 'Service Details' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== Breadcrumbs End ======-->



    <section class="page-content-section section-gap single-page-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-page-wrapper mb-30">
                        <div class="post-item">
                            <div class="post-img mb-20">
                                <img data-src="{{ asset('assets/front/img/user/services/' . $service->image) }}"
                                    class="img-fluid lazy" alt="">
                            </div>
                            <div class="entry-content">
                                <h3 class="title mb-4 mt-2">{{ $service->name }}</h3>
                                <div class="summernote-content">
                                    {!! replaceBaseUrl($service->content) !!}
                                </div>
                            </div>
                        </div>
                        <div class="post-share">
                            <ul class="social-link">
                                <li><a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        class="facebook"><i
                                            class="fab fa-facebook-f"></i>{{ $keywords['Facebook'] ?? 'Facebook' }}</a>
                                </li>
                                <li><a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                                        class="twitter"><i
                                            class="fab fa-twitter"></i>{{ $keywords['Twitter'] ?? 'Twitter' }}</a></li>
                                <li><a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $service->name }}"
                                        class="linkedin"><i
                                            class="fab fa-linkedin-in"></i>{{ $keywords['Linkedin'] ?? 'Linkedin' }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
