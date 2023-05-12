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
    <section class="service-section inner-section-gap bg-white" id="service">
        <div class="container">
            <div class="row service-boxes justify-content-center">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="service-box-two mb-40">
                            <a class="service-thumb"
                                @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>
                                <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                    class="lazy" alt="">
                            </a>
                            @if ($service->detail_page == 1)
                                <h4 class="title"><a
                                        href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</a>
                                </h4>
                                <a href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}"
                                    class="read-more-btn">{{ $keywords['Read_More'] ?? 'Read More' }}</a>
                            @else
                                <h4 class="title">{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</h4>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
