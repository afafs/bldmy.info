@extends('user.profile1.theme5.layout')
@section('tab-title')
    {{ $keywords['Portfolios'] ?? 'Portfolios' }}
@endsection
@section('meta-description', !empty($userSeo) ? $userSeo->portfolios_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->portfolios_meta_keywords : '')
@section('br-title')
    {{ $keywords['Portfolios'] ?? 'Portfolios' }}
@endsection
@section('br-link')
    {{ $keywords['Portfolios'] ?? 'Portfolios' }}
@endsection
@section('content')
    <!--====== Start Page Content ======-->
    <section class="page-content-section pt-120 pb-120">
        <!--====== Start Project Section ======-->
        <section class="portfolio-area" id="masonry-portfolio">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="portfolio-filter-button text-center">
                            <ul class="filter-btn mb-60 wow fadeInUp">
                                <li data-filter="*" class="active">{{ $keywords['All'] ?? 'All' }}</li>
                                @foreach ($portfolio_categories as $portfolio_category)
                                    <li data-filter=".cat-{{ $portfolio_category->id }}">{{ $portfolio_category->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row masonry-row">
                    @foreach ($portfolios as $portfolio)
                        <div class="col-lg-6 portfolio-column cat-{{ $portfolio->bcategory->id }}">
                            <div class="portfolio-item portfolio-item-one mb-30">
                                <div class="portfolio-img">
                                    <a href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}"
                                        class="d-block">
                                        <img src="{{ asset('assets/front/img/user/portfolios/' . $portfolio->image) }}"
                                            alt="Image">
                                    </a>
                                    <div class="portfolio-content">
                                        <h4 class="title"><a
                                                href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}">{{ strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title }}</a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--====== End Project Section ======-->
    </section>
    <!--====== End Page Content ======-->
@endsection

@section('scripts')
    @if (!empty(request()->input('category')))
        <script>
            "use strict";
            $(window).on('load', function() {
                setTimeout(function() {
                    let catid = {{ request()->input('category') }};
                    $("li[data-filter='.cat-" + catid + "']").trigger('click');
                }, 500);
            });
        </script>
    @endif
@endsection
