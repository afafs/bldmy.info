@extends('user.profile1.' . $layout . '.layout')

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

    <!--====== Breadcrumbs Start ======-->
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10">
                    <div class="page-title">
                        <h1>{{ $keywords['Portfolios'] ?? 'Portfolios' }}</h1>
                        <ul class="breadcrumbs-link">
                            <li><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                            </li>
                            <li class="">{{ $keywords['Portfolios'] ?? 'Portfolios' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== Breadcrumbs End ======-->
    <!--====== Start Page Content ======-->
    <section class="page-content-section section-gap">
        <div class="container">
            <div class="project-filter">
                <ul>
                    <li data-filter="*" class="{{ empty(request('category')) ? 'active' : '' }}">
                        {{ $keywords['All'] ?? 'All' }}</li>
                    @foreach ($portfolio_categories as $portfolio_category)
                        <li class="{{ request('category') == $portfolio_category->id ? 'active' : '' }}"
                            data-filter=".cat-{{ $portfolio_category->id }}">{{ $portfolio_category->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="project-loop row">
                @foreach ($portfolios as $portfolio)
                    <div class="col-lg-3 col-md-6 cat-{{ $portfolio->bcategory->id }}">
                        <div class="project-item">
                            <div class="project-thumbnail">
                                <img class="lazy"
                                    data-src="{{ asset('assets/front/img/user/portfolios/' . $portfolio->image) }}"
                                    alt="ProjectImage">
                            </div>
                            <div class="hover-content">
                                <a href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}"
                                    class="plus-icon"></a>
                                <a href="{{ route('front.user.portfolio.detail', [getParam(), $portfolio->slug, $portfolio->id]) }}"
                                    class="title">{{ strlen($portfolio->title) > 25 ? mb_substr($portfolio->title, 0, 25, 'UTF-8') . '...' : $portfolio->title }}</a>
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
                    let catid = {{ request()->input('category') }};
                    $("li[data-filter='.cat-" + catid + "']").trigger('click');
                }, 500);
            });
        </script>
    @endif
@endsection
