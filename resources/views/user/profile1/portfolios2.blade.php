@extends('user.profile1.layout')

@section('tab-title')
{{$keywords["Portfolios"] ?? "Portfolios"}}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->portfolios_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->portfolios_meta_keywords : '')

@section('br-title')
{{$keywords["Portfolios"] ?? "Portfolios"}}
@endsection
@section('br-link')
{{$keywords["Portfolios"] ?? "Portfolios"}}
@endsection

@section('content')
    <!--====== Start Page Content ======-->
    <section class="portfolio-section inner-section-gap" id="portfolio">
        <div class="container">

            <div class="portfolio-filter-wrap mb-20">
                <ul class="portfolio-filter">
                    <li><a href="#" data-filter="*" class="filter-active">{{$keywords['All'] ?? "All"}}</a></li>
                    @foreach($portfolio_categories as $portfolio_category)
                    <li><a href="#" data-filter=".cat-{{$portfolio_category->id}}">{{$portfolio_category->name}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="row portfolio-items filter-items justify-content-center">
                @foreach($portfolios as $portfolio)
                <div class="col-lg-4 col-md-6 filter-item cat-{{$portfolio->bcategory->id}}">
                    <div class="portfolio-item-two mt-30">
                        <div class="portfolio-thumb">
                            <img src="{{asset('assets/front/img/user/portfolios/'.$portfolio->image)}}" alt="Image">
                            <a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}" class="portfolio-link">
                                <span></span>
                            </a>
                        </div>
                        <div class="portfolio-content">
                            <h4 class="title"><a href="{{route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id])}}">{{strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title}}</a></h4>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== End Page Content ======-->    
@endsection

@section('scripts')
    @if (!empty(request()->input('category')))
        <script>
            "use strict";
            $(window).on('load', function() {
                setTimeout(function() {
                    let catid = {{request()->input('category')}};
                    $("a[data-filter='.cat-" + catid + "']").trigger('click');
                }, 500);
            });
        </script>
    @endif
@endsection
