<!DOCTYPE html>
<html lang="en" @if ($userCurrentLang->rtl == 1) dir="rtl" @endif>

<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta-description')">
    <meta name="keywords" content="@yield('meta-keywords')">
    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>{{ isset($userBs) && $userBs->website_title ? $userBs->website_title : '' }} -
        @yield('pageHeading')</title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/user/img/' . $userBs->favicon) }}">

    {{-- include styles --}}
    @includeIf('user-front.common.partials.styles')
    <link rel="stylesheet" href="{{ asset('assets/front/css/toastr.min.css') }}">


    @yield('styles')

</head>

<body>
    @if (isset($userBs) && $userBs->preloader_status == 1)
        {{-- preloader start --}}
        <div class="preloader">
            <div class="load">
                <img class="lazy" data-src="{{ asset('assets/user/img/' . $userBs->preloader) }}" alt="Preloader">
            </div>
        </div>
        {{-- preloader end --}}
    @endif


    {{-- header start --}}

    <section class="header-area">
        <div class="header-top header-top-{{ $userBs->theme_version }}">
            <div class="container">
                <div
                    class="header-top-wrapper d-flex justify-content-center justify-content-md-between align-items-center">
                    <div class="header-top-left d-none d-md-block">
                        <ul class="header-meta">
                            <li><a href="tel:{{ $userBs->header_phone }}"><i class="fal fa-phone"></i>
                                    {{ $userBs->header_phone }}</a></li>
                            <li><a href="mailto:{{ $userBs->header_email }}"><i class="fal fa-envelope"></i>
                                    {{ $userBs->header_email }}</a></li>
                        </ul>
                    </div>
                    <div class="header-top-right">
                        <div class="header-follow d-flex align-items-center">
                            <ul class="social">
                                <li><span>{{ $keywords['Follow_Us'] ?? __('Follow Us') }}:</span></li>
                                @foreach ($socials as $key => $social)
                                    <li><a target="_blank" href="{{ $social->url }}"><i
                                                class="{{ $social->icon }}"></i></a></li>
                                @endforeach
                            </ul>
                            <ul class="header-login">
                                <li>
                                    @if (Auth::guard('customer')->check())
                                        <a href="{{ route('customer.dashboard', getParam()) }}"><i
                                                class="far fa-chart-line"></i>
                                            {{ $keywords['Dashboard'] ?? __('Dashboard') }}
                                        </a>
                                    @else
                                        <a href="{{ route('customer.login', getParam()) }}"><i
                                                class="fal fa-sign-in-alt"></i>
                                            {{ $keywords['SignIn'] ?? 'Sign In' }}</a>
                                    @endif
                                </li>
                            </ul>
                            <ul class="header-login">
                                <form action="{{ route('changeUserLanguage', getParam()) }}" id="asdasdasds">
                                    @csrf
                                    <input type="hidden" name="username" value="{{ $user->username }}">
                                    <select onchange="submit('#asdasdasds')" name="code" id="" class="form-control">
                                        @foreach ($userLangs as $lang)
                                            <option {{ Session::get('user_lang') == $lang->code ? 'selected' : '' }}
                                                value="{{ $lang->code }}">{{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($userBs->theme_version == 4)
            <div class="header-center">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="header-center-wrapper d-none d-lg-block">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="desktoplogo">
                                            <a href="{{ route('front.user.detail.view', getParam()) }}">
                                                <img class="lazy" data-src="{{ $footerInfo ? asset('assets/user/img/footer/' . $footerInfo->logo) : asset('assets/user/img/lgoo.png') }}" alt="logo">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="get-quote d-flex">
                                            <div class="get-icon">
                                                <i class="fal fa-envelope-open"></i>
                                            </div>
                                            <div class="get-text media-body">
                                                <h6 class="title">
                                                    {{ $keywords['get_a_free_quote'] ?? 'get a free quote' }} »</h6>
                                                <p>{{ $keywords['Want_an_approximate_price'] ?? 'Want an approximate price' }}?
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="header-search d-sm-flex justify-content-center justify-content-lg-end">
                                <div class="search-seylon">
                                    <div class="seylon-dropdown">
                                        <div class="seylon-toggler">
                                            <span>All Categories</span>
                                            <i class="fal fa-chevron-down"></i>
                                        </div>
                                        <ul class="seylon-dropdown-menu">
                                            <li><a href="#">All Categories</a></li>
                                            <li><a href="#">Uncategorized</a></li>
                                            <li><a href="#">Cameras & Camcoders</a></li>
                                            <li><a href="#">Computer & Networking</a></li>
                                            <li><a href="#">Games & Consoles</a></li>
                                            <li><a href="#">Headphone & Speaker</a></li>
                                            <li><a href="#">Movies & Video Games</a></li>
                                            <li><a href="#">Smartphone</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="search-form">
                                    <form action="#" class="d-flex">
                                        <input type="text" placeholder="Enter your keywords.....">
                                        <button><i class="fal fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="header-menu header-menu-{{ $userBs->theme_version }}">
            <div class="horizontal-header d-lg-none">
                <div class="container">
                    <div class="horizontal-header d-flex justify-content-between align-items-center">
                        <a href="javascript:void(0)" id="horizontal-navtoggle" class="navtoggle"><i
                                class="fal fa-bars"></i></a>
                        <span class="smllogo">
                            <img class="lazy" data-src="{{ $userBs->logo ? asset('assets/user/img/' . $userBs->logo) : asset('assets/user/img/lgoo.png') }}" alt="logo" width="120">
                        </span>
                        <a href="tel:245-6325-3256" class="callusbtn"><i class="fal fa-phone"
                                aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
            <div class="horizontal-main">
                <div class="container">
                    <div class="horizontal-wrapper d-flex justify-content-between align-items-center">
                        <div class="desktoplogo">
                            <a href="{{ route('front.user.detail.view', getParam()) }}">
                                <img class="lazy" data-src="{{ $userBs->logo ? asset('assets/user/img/' . $userBs->logo) : asset('assets/user/img/lgoo.png') }} "
                                    alt="logo">
                            </a>
                        </div>
                        <!--Nav-->
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- header end --}}
    @yield('content')


    
    <div id="WAButton"></div>
    @php
        $cookieStatus = !empty($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1 ? true : false;
        $cookieName = str_replace(' ', '_', $userBs->website_title . '_' . $user->username);
        $cookieName = strtolower($cookieName) . '_cookie';
        \Config::set('cookie-consent.enabled', $cookieStatus);
        \Config::set('cookie-consent.cookie_name', $cookieName);
    @endphp
    {{-- cookie alert --}}

    @if ($be->cookie_alert_status == 1)
        <div class="cookie">
            @include('cookieConsent::index')
        </div>
    @endif

    {{-- include footer --}}
    @if ($userBs->theme_version == 1 || $userBs->theme_version == 3 || $userBs->theme_version == 5)
        @includeIf('user-front.theme1.partials.footer-v1')
    @elseif ($userBs->theme_version == 2)
        @includeIf('user-front.theme2.partials.footer-v2')
    @else
        @includeIf('user-front.theme4.partials.footer-v4')
    @endif

    {{-- include scripts --}}
    @includeIf('user-front.common.partials.scripts')
    <script src="{{ asset('assets/front/js/toastr.min.js') }}"></script>


    @if (session()->has('success'))
        <script>
            "use strict";
            toastr['success']("{{ __(session('success')) }}");
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            "use strict";
            toastr['error']("{{ __(session('error')) }}");
        </script>
    @endif

    @if (session()->has('warning'))
        <script>
            "use strict";
            toastr['warning']("{{ __(session('warning')) }}");
        </script>
    @endif
    <script>
        "use strict";
        var rtl = {{ $rtl }};
    </script>

    {{-- additional script --}}
    @yield('script')

    {{-- disqus script --}}
    @yield('disqus-script')
</body>

</html>
