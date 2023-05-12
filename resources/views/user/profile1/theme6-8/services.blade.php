@extends('user.profile1.' . $layout . '.layout')
@section('tab-title')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection
@section('styles')
@endsection
@section('meta-description', !empty($userSeo) ? $userSeo->services_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->services_meta_keywords : '')
@section('content')

@section('br-title')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection
@section('br-link')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection
<!--====== Breadcrumbs Start ======-->
<section class="breadcrumbs-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-10">
                <div class="page-title">
                    <h1>@yield('br-title')</h1>
                    <ul class="breadcrumbs-link">
                        <li><a
                                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                        </li>
                        <li class="">@yield('br-title')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== Breadcrumbs End ======-->

<!--====== Start Main Wrapper ======-->
<section class="page-content-section section-gap">
    <div class="container">
        <div class="service-slider">
            <div class="row">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item mb-30">
                            <div class="service-img">
                                <a
                                    @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>
                                    <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                        class="lazy" alt="">
                                </a>
                                <div class="service-overlay">
                                    <div class="service-content">
                                        @if ($service->detail_page == 1)
                                            <h3 class="title mt-3">
                                                <a
                                                    href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}">
                                                    {{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}
                                                </a>
                                            </h3>
                                        @else
                                            <h3 class="title">
                                                {{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}
                                            </h3>
                                        @endif
                                        @if ($service->detail_page == 1)
                                            <a href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}"
                                                class="btn-link">
                                                {{ $keywords['Read_More'] ?? 'Read More' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!--====== End Main Wrapper ======-->
@endsection
