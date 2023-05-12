<!DOCTYPE html>
<html lang="en" @if ($userCurrentLang->rtl == 1) dir="rtl" @endif>

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta-description')">
    <meta name="keywords" content="@yield('meta-keywords')">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('og-meta')
    <!--====== Title ======-->

    <title>{{ isset($userBs) && $userBs->website_title ? $userBs->website_title : '' }} - @yield('tab-title')</title>


    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon"
        href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}"
        type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/pignose.calendar.min.css') }}">
    <!--====== PLUGIN css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.min.css') }}">
    <!--====== COMMON css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common.css') }}">
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/whatsapp.min.css') }}">
    <!--====== Slick Slider ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/slick.min.css') }}" />
    <!--====== Animated Headline ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/animated-headline.min.css') }}" />
    <!--====== Magnific Popup ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/magnific-popup.min.css') }}" />
    <!--====== Font Awesome ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/all.min.css') }}" />
    <!--====== Main Css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/theme6.css') }}" />
    @if ($userCurrentLang->rtl == 1)
        <!--====== Common RTL Style css ======-->
        <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common-rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme6-8/rtl-theme6.css') }}" />
    @endif
    {{-- @php
        if (!empty($userBs->base_color)) {
            $baseColor = $userBs->base_color;
        } else {
            $baseColor = 'F57236';
        }
        if (!empty($userBs->secondary_base_color)) {
            $secBaseColor = $userBs->secondary_base_color;
        } else {
            $secBaseColor = 'FEAF2F';
        }
    @endphp --}}


    @php
        // if (!empty($userBs->base_color)) {
        //     $baseColor = $userBs->base_color;
        // } else {
        //     $baseColor = '';
        // }
        
        $holidays = App\Models\User\UserHoliday::where('user_id', $user->id)
            ->pluck('date')
            ->toArray();
        $dats = [];
        foreach ($holidays as $value) {
            $dats[] = Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        $holidays = $dats;
        
        $weekends = App\Models\User\UserDay::where('user_id', $user->id)
            ->where('weekend', 1)
            ->pluck('index')
            ->toArray();
        
    @endphp
    {{-- <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common-base-color.php?color=' . $baseColor) }}"> --}}
    <style>
        :root {
            --color-primary: <?='#'. $userBs->base_color ?>
        }
    </style>
    @yield('styles')
</head>

<body>

    <!--====== Start Template Header ======-->
    <header class="template-header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-dark justify-content-between align-items-center">
                <a href="{{ route('front.user.detail.view', getParam()) }}" class="navbar-brand d-block">
                    <img class="lazy"
                        data-src="{{ isset($userBs->logo)
                            ? asset('assets/front/img/user/' . $userBs->logo)
                            : asset('assets/front/img/profile/lgoo.png') }}"
                        alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @if (is_array($userPermissions) &&
                        is_array($packagePermissions) &&
                        in_array('Appointment', $userPermissions) &&
                        in_array('Appointment', $packagePermissions))
                    <div class="language-dropdown order-lg-last">
                        @if (!Auth::guard('customer')->check())
                            <a href="{{ route('customer.login', getParam()) }}"
                                class="main-btn filled-btn">{{ $keywords['Login'] ?? 'Login' }}</a>
                        @else
                            <div class="dropdown show">
                                <a class="main-btn dropdown-toggle filled-btn" href="#" role="button"
                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ Auth::guard('customer')->user()->username }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item"
                                        href="{{ route('customer.dashboard', getParam()) }}">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a>
                                    <a class="dropdown-item"
                                        href="{{ route('customer.logout', getParam()) }}">{{ $keywords['Signout'] ?? __('Sign out') }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="language-dropdown order-lg-last">
                    @if (!empty($userLangs))
                        <div class="language-selector bordered-style  d-flex">
                            <form action="{{ route('changeUserLanguage', getParam()) }}" id="userLangForm">
                                <input type="hidden" name="username" value="{{ $user->username }}">
                                <select name="code" onchange="document.getElementById('userLangForm').submit();">
                                    @foreach ($userLangs as $userLang)
                                        <option value="{{ $userLang->code }}"
                                            {{ $userLang->id == $userCurrentLang->id ? 'selected' : '' }}>
                                            {{ $userLang->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item @if (request()->routeIs('front.user.detail.view')) active @endif">
                            <a class="nav-link"
                                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                        </li>
                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Service', $userPermissions) &&
                                in_array('Service', $packagePermissions))
                            <li class="nav-item @if (request()->routeIs('front.user.services') || request()->routeIs('front.user.service.detail')) active @endif">
                                <a class="nav-link"
                                    href="{{ route('front.user.services', getParam()) }}">{{ $keywords['Services'] ?? 'Services' }}</a>
                            </li>
                        @endif
                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Portfolio', $userPermissions) &&
                                in_array('Portfolio', $packagePermissions))
                            <li class="nav-item @if (request()->routeIs('front.user.portfolios') || request()->routeIs('front.user.portfolio.detail')) active @endif">
                                <a class="nav-link"
                                    href="{{ route('front.user.portfolios', getParam()) }}">{{ $keywords['Portfolios'] ?? 'Portfolios' }}</a>
                            </li>
                        @endif
                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Blog', $userPermissions) &&
                                in_array('Blog', $packagePermissions))
                            <li class="nav-item @if (request()->routeIs('front.user.blogs') || request()->routeIs('front.user.blog.detail')) active @endif">
                                <a href="{{ route('front.user.blogs', getParam()) }}" class="nav-link">
                                    {{ $keywords['Blogs'] ?? 'Blogs' }}
                                </a>
                            </li>
                        @endif
                        @if (is_array($userPermissions) && in_array('Contact', $userPermissions))
                            <li class="nav-item @if (request()->routeIs('front.user.contact')) active @endif">
                                <a href="{{ route('front.user.contact', getParam()) }}"
                                    class="nav-link">{{ $keywords['Contact'] ?? 'Contact' }}</a>
                            </li>
                        @endif
                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Appointment', $userPermissions) &&
                                in_array('Appointment', $packagePermissions))
                            <li class="nav-item @if (request()->routeIs('front.user.appointment') ||
                                    request()->routeIs('front.user.appointment.form') ||
                                    request()->routeIs('front.user.appointment.booking')) active @endif">
                                <a class="nav-link"
                                    href="{{ route('front.user.appointment', getParam()) }}">{{ $keywords['Appointment'] ?? 'Appointment' }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <!--====== End Template Header ======-->

    @yield('content')

    <!--====== Template Footer Start ======-->
    <footer class="template-footer bg-primary-color">
        <div class="overlay lazy" data-bg="{{ asset('assets/front/css/profile/theme6-8/footer-wave-lines.png') }}">
        </div>
        <div class="container">
            <div class="footer-content">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        @if (is_array($userPermissions) && in_array('Footer Mail', $userPermissions))
                            <p class="mail-title">{{ $keywords['Stay_Connected'] ?? 'Stay Connected' }}</p>
                            <a class="mail" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        @endif
                        <ul class="social-links">
                            @if (isset($social_medias))
                                @foreach ($social_medias as $social_media)
                                    <li><a href="{{ $social_media->url }}" target="_blank"><i
                                                class="{{ $social_media->icon }}"></i></a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <p>{{ $keywords['Copyright'] ?? __('Copyright') }} {{ date('Y') }}
                    {{ $keywords['All_Right_Reserved'] ?? __('All Right Reserved') }}</p>
            </div>
        </div>
    </footer>
    <!--====== Template Footer End ======-->
    <!--====== End Main Wrapper ======-->

    <!--====== Jquery ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/jquery-3.6.0.min.js') }}"></script>
    <!-- Bootstrap Datepicker -->
    <script src="{{ asset('assets/admin/js/plugin/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- jQuery Timepicker -->
    <script src="{{ asset('assets/front/js/jquery.timepicker.min.js') }}"></script>
    <!--====== plugin js ======-->
    <script src="{{ asset('assets/front/js/pignose.calendar.full.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/plugin.min.js') }}"></script>
    <!--====== Bootstrap ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/profile/whatsapp.min.js') }}"></script>
    <!--====== Slick slider ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/slick.min.js') }}"></script>
    <!--====== Images loaded ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/imagesloaded.pkgd.min.js') }}"></script>
    <!--====== Isotope ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/isotope.pkgd.min.js') }}"></script>
    <!--====== In-view ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/jquery.inview.min.js') }}"></script>
    <!--====== Easy Pie Chart ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/jquery-easypiechart.min.js') }}"></script>
    <!--====== Magnific Popup ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/magnific-popup.min.js') }}"></script>
    <!--====== Animated Headline ======-->
    <script src="{{ asset('assets/front/js/profile/theme6-8/animated-headline.min.js') }}"></script>

    <script>
        "use strict";
        var rtl = {{ $userCurrentLang->rtl }};
        var $holidays = '<?php echo json_encode($holidays); ?>'
        var $weekends = '<?php echo json_encode($weekends); ?>'
        var timeSlotUrl = "{{ route('getTimeSlot', getParam()) }}";;
        var checkThisSlot = "{{ route('checkThisSlot', getParam()) }}";
    </script>
    <!--====== Common js ======-->
    <script src="{{ asset('assets/front/js/profile/common.js') }}"></script>
    <!--====== Main js ======-->

    <script src="{{ asset('assets/front/js/profile/theme6-8/main.js') }}"></script>
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
    {{-- plugins --}}
    @includeif('user.profile1.partials.plugins')
    {{-- plugins end --}}
    @yield('scripts')
</body>

</html>
