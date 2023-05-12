@extends('user-front.common.layout')
@section('pageHeading')
    @if (!empty($details))
        {{ $details->title }}
    @endif
@endsection
@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/front/css/summernote-content.css') }}">
@endsection
@section('content')
    <!--====== PAGE BANNER PART START ======-->

    <section class="page-banner" style="background: linear-gradient(to right, #{{$websiteInfo->breadcrumb_color1}} 0%, #{{$websiteInfo->breadcrumb_color2}} 100%);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h4 class="title">Custom Page</h4>
                <p>Search from over 2000+ Active Ads in 29+ Categories for Free</p>
            </div>
        </div>
    </section>

    <!--====== PAGE BANNER PART ENDS ======-->


    <!-- Start Olima FAQ Section -->
    <section class="faq-area-v1 pt-120 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="summernote-content">
                        @if (!empty($details->content))
                            {!! replaceBaseUrl($details->content, 'summernote') !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima FAQ Section -->

    {{-- @includeIf('frontend.partials.instagram-v1') --}}
@endsection
