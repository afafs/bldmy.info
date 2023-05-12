@extends('user.profile1.layout')

@section('tab-title')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->services_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->services_meta_keywords : '')

@section('br-title')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection
@section('br-link')
    {{ $keywords['Services'] ?? 'Services' }}
@endsection

@section('content')
    <!--====== Start Page Content ======-->
    <section class="page-content-section inner-section-gap">
        <div class="container">
            <div class="page-content-box">
                <div class="row service-boxes justify-content-center">
                    @foreach ($services as $service)
                        <div class="col-lg-4 col-md-6 col-sm-10">
                            <div class="service-box-one">
                                <a class="service-thumb"
                                    @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>
                                    <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                        class="lazy" alt="">
                                </a>
                                @if ($service->detail_page == 1)
                                    <h4 class="title"><a
                                            href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</a>
                                    </h4>
                                @else
                                    <h4 class="title">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</h4>
                                @endif
                                <p>
                                    {{ strlen(strip_tags($service->content)) > 90 ? mb_substr(strip_tags($service->content), 0, 90, 'UTF-8') : strip_tags($service->content) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--====== End Page Content ======-->
@endsection
