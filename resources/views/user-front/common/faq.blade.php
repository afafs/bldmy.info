@extends('user-front.common.layout')

@section('pageHeading')
    @if (!empty($currentLanguageInfo->pageHeading->faq_title))
        {{ $currentLanguageInfo->pageHeading->faq_title }}
    @endif
@endsection
@section('meta-description', !empty($seo) ? $seo->meta_description_faq : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_faq : '')

@section('content')

    <!--====== PAGE BANNER PART START ======-->

    <section class="page-banner" style="background: linear-gradient(to right, #{{$websiteInfo->breadcrumb_color1}} 0%, #{{$websiteInfo->breadcrumb_color2}} 100%);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h4 class="title">{{ $currentLanguageInfo->pageHeading->faq_title ?? 'FAQ' }}</h4>
                <p>{{ $currentLanguageInfo->pageHeading->faq_subtitle ?? 'FAQ Sub' }}</p>
            </div>
        </div>
    </section>

    <!--====== PAGE BANNER PART ENDS ======-->


    <!--====== FAQ PART START ======-->

    <section class="faq-area pt-70 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-md-3">
                    <div class="faq-accordion pt-30">
                        <div class="accordion" id="accordionExample">
                            @foreach ($faqs as $key => $item)
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <a href="#" data-toggle="collapse" data-target="#collapse{{ $item->id }}"
                                            aria-expanded="true"
                                            aria-controls="collapse{{ $item->id }}">{{ $item->question }}</a>
                                    </div>
                                    <div id="collapse{{ $item->id }}" class="collapse <?= $key == 0 ? 'show' : '' ?>"
                                        aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>{{ $item->answer }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== FAQ PART ENDS ======-->


@endsection
