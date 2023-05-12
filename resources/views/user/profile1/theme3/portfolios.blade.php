@extends('user.profile1.theme3.layout')

@section('tab-title')
{{$keywords["Portfolios"] ?? "Portfolios"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->portfolios_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->portfolios_meta_keywords : '')

@section('content')
    <!--====== Start Main Wrapper ======-->
    <div class="main-wrapper inner">
        <section class="portfolio-area pt-120 pb-100" id="masonry-portfolio">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-5">
                        <div class="section-title mb-50">
                            <span class="sub-title">{{$home_text->portfolio_title ?? __('Portfolios')}}</span>
                            <h2><span class="light-text">{{$home_text->portfolio_subtitle ??  __('Portfolios')}}</span></h2>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="portfolio-filter-button mb-40">
                            <ul class="filter-btn">
                                <li data-filter="*" class="active">{{$keywords['All'] ?? "All"}}</li>
                                @foreach($portfolio_categories as $portfolio_category)
                                <li data-filter=".cat-{{$portfolio_category->id}}">{{$portfolio_category->name}}</li>
                                @endforeach 
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row masonry-row">
                    @foreach($portfolios as $portfolio)

                    <div class="col-lg-3 col-md-6 portfolio-column mb-20 cat-{{$portfolio->bcategory->id}}">
                        <a class="portfolio-item" href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}">
                            <div class="portfolio-img">
                                <img src="{{asset('assets/front/img/user/portfolios/'.$portfolio->image)}}" alt="Image">
                                <div class="portfolio-overlay">
                                    <div class="portfolio-content">
                                        <h3>
                                            {{strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title}}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div><!--====== End Main Wrapper ======-->
@endsection

@section('scripts')
    @if (!empty(request()->input('category')))
        <script>
            "use strict";
            $(window).on('load', function() {
                setTimeout(function() {
                    let catid = {{request()->input('category')}};
                    $("li[data-filter='.cat-" + catid + "']").trigger('click');
                }, 500);
            });
        </script>
    @endif
@endsection