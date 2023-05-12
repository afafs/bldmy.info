@extends('user.profile1.theme5.layout')

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
    <section class="page-content-section pt-120 pb-120">
        <div class="container">
            <div class="row">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="service-item service-item-one mb-40">
                            <a class="service-img d-block"
                                @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>
                                <img data-src="{{ isset($service->image) ? asset('assets/front/img/user/services/' . $service->image) : asset('assets/front/img/profile/service-1.jpg') }}"
                                    class="lazy" alt="Service Image">
                            </a>
                            <div class="service-content">
                                <h4 class="title"><a
                                        @if ($service->detail_page == 1) href="{{ route('front.user.service.detail', [getParam(), 'slug' => $service->slug, 'id' => $service->id]) }}" @endif>{{ strlen($service->name) > 30 ? mb_substr($service->name, 0, 30, 'UTF-8') . '...' : $service->name }}</a>
                                </h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Page Content ======-->
@endsection
