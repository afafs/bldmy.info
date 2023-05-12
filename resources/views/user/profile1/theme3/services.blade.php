@extends('user.profile1.theme3.layout')

@section('tab-title')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->services_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->services_meta_keywords : '')

@section('content')
    <!--====== Start Main Wrapper ======-->
    <div class="main-wrapper inner">
        <section class="service-area pt-110 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-title mb-60">
                            <span class="sub-title">{{ $home_text->service_title ?? __('Services') }}</span>
                            <h2><span class="light-text">{{ $home_text->service_subtitle ?? __('What I Do ?') }}</span></h2>
                        </div>
                    </div>
                </div>
                <div class="service-slider">
                    <div class="row">
                        @foreach ($services as $service)
                            <div class="col-lg-4 col-md-6">
                                <div class="service-item mb-30">
                                    <div class="service-img">
                                        @if ($service->detail_page == 1)
                                            <a
                                                @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>
                                                <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                                    class="lazy" alt="">
                                            </a>
                                            <div class="service-overlay">
                                                <div class="service-content">
                                                    @if ($service->detail_page == 1)
                                                        <h3 class="title">
                                                            <a
                                                                href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}">
                                                                {{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}
                                                            </a>
                                                        </h3>
                                                    @else
                                                        <h3 class="title">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</h3>
                                                    @endif
                                                    @if ($service->detail_page == 1)
                                                        <a href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}"
                                                            class="btn-link">
                                                            {{ $keywords['Read_More'] ?? 'Read More' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--====== End Main Wrapper ======-->
@endsection
