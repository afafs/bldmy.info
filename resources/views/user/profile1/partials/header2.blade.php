    <!--====== Start Header ======-->
    <header class="template-header header-primary-color sticky-header">
        <div class="container">
            <div class="header-inner">
                <div class="header-left">
                    <div class="brand-logo">
                        <a href="{{ route('front.user.detail.view', getParam()) }}">
                            <img class="lazy"
                                data-src="{{ isset($userBs->logo)
                                    ? asset('assets/front/img/user/' . $userBs->logo)
                                    : asset('assets/front/img/profile/lgoo.png') }}"
                                alt="">
                        </a>
                    </div>
                </div>
                <div class="header-right">
                    <nav class="nav-menu d-none d-lg-block">
                        <ul class="primary-menu">
                            <li class="@if (request()->routeIs('front.user.detail.view')) active @endif">
                                <a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                            </li>

                            @if (is_array($userPermissions) &&
                                    is_array($packagePermissions) &&
                                    in_array('Service', $userPermissions) &&
                                    in_array('Service', $packagePermissions))
                                <li class="@if (request()->routeIs('front.user.services') || request()->routeIs('front.user.service.detail')) active @endif">
                                    <a
                                        href="{{ route('front.user.services', getParam()) }}">{{ $keywords['Services'] ?? 'Services' }}</a>
                                </li>
                            @endif

                            @if (is_array($userPermissions) &&
                                    is_array($packagePermissions) &&
                                    in_array('Portfolio', $userPermissions) &&
                                    in_array('Portfolio', $packagePermissions))
                                <li class="@if (request()->routeIs('front.user.portfolios') || request()->routeIs('front.user.portfolio.detail')) active @endif">
                                    <a
                                        href="{{ route('front.user.portfolios', getParam()) }}">{{ $keywords['Portfolios'] ?? 'Portfolios' }}</a>
                                </li>
                            @endif
                            @if (is_array($userPermissions) &&
                                    is_array($packagePermissions) &&
                                    in_array('Blog', $userPermissions) &&
                                    in_array('Blog', $packagePermissions))
                                <li class="@if (request()->routeIs('front.user.blogs') || request()->routeIs('front.user.blog.detail')) active @endif">
                                    <a
                                        href="{{ route('front.user.blogs', getParam()) }}">{{ $keywords['Blogs'] ?? 'Blogs' }}</a>
                                </li>
                            @endif
                            @if (is_array($userPermissions) &&
                                    is_array($packagePermissions) &&
                                    in_array('Appointment', $userPermissions) &&
                                    in_array('Appointment', $packagePermissions))
                                <li>
                                    <a class="nav-link @if (request()->routeIs('front.user.appointment') ||
                                            request()->routeIs('front.user.appointment.form') ||
                                            request()->routeIs('front.user.appointment.booking')) active @endif"
                                        href="{{ route('front.user.appointment', getParam()) }}">{{ $keywords['Appointment'] ?? 'Appointment' }}</a>
                                </li>
                            @endif
                            @if (is_array($userPermissions) && in_array('Contact', $userPermissions))
                                <li>
                                    <a
                                        @if (request()->routeIs('front.user.detail.view')) href="#contact"
                                @else
                                    href="{{ route('front.user.detail.view', getParam()) }}#contact" @endif>{{ $keywords['Contact'] ?? 'Contact' }}</a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                    @if (!empty($userLangs))
                        <div class="language-selector transparent-style d-flex">
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
                    @if (!Auth::guard('customer')->check())
                        <a href="{{ route('customer.login', getParam()) }}"
                            class="template-btn ml-2 ">{{ $keywords['Login'] ?? 'Login' }}</a>
                    @else
                        <div class="dropdown show">
                            <a class="template-btn dropdown-toggle ml-2" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::guard('customer')->user()->username }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item"
                                    href="{{ route('customer.dashboard', getParam()) }}">{{ $keywords['dashboard'] ?? __('Dashboard') }}</a>
                                <a class="dropdown-item"
                                    href="{{ route('customer.logout', getParam()) }}">{{ $keywords['Signout'] ?? __('Sign out') }}</a>
                            </div>
                        </div>
                        {{-- <a href="{{ route('customer.dashboard', getParam()) }}"
                            class=" ">{{ Auth::guard('customer')->user()->username }}</a> --}}
                    @endif
                    <div class="navbar-toggler d-lg-none">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Mobile Slide Menu -->
        <div class="mobile-slide-panel">
            <div class="panel-overlay"></div>
            <div class="panel-inner">
                <div class="mobile-logo">
                    <a href="{{ route('front.user.detail.view', getParam()) }}">
                        <img class="lazy"
                            data-src="{{ isset($userBs->logo)
                                ? asset('assets/front/img/user/' . $userBs->logo)
                                : asset('assets/front/img/profile/lgoo.png') }}"
                            alt="">
                    </a>
                </div>
                <nav class="mobile-menu">
                    <ul class="primary-menu">
                        <li class="@if (request()->routeIs('front.user.detail.view')) active @endif">
                            <a
                                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                        </li>

                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Service', $userPermissions) &&
                                in_array('Service', $packagePermissions))
                            <li class="@if (request()->routeIs('front.user.services') || request()->routeIs('front.user.service.detail')) active @endif">
                                <a
                                    href="{{ route('front.user.services', getParam()) }}">{{ $keywords['Services'] ?? 'Services' }}</a>
                            </li>
                        @endif

                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Portfolio', $userPermissions) &&
                                in_array('Portfolio', $packagePermissions))
                            <li class="@if (request()->routeIs('front.user.portfolios') || request()->routeIs('front.user.portfolio.detail')) active @endif">
                                <a
                                    href="{{ route('front.user.portfolios', getParam()) }}">{{ $keywords['Portfolios'] ?? 'Portfolios' }}</a>
                            </li>
                        @endif
                        @if (is_array($userPermissions) &&
                                is_array($packagePermissions) &&
                                in_array('Blog', $userPermissions) &&
                                in_array('Blog', $packagePermissions))
                            <li class="@if (request()->routeIs('front.user.blogs') || request()->routeIs('front.user.blog.detail')) active @endif">
                                <a
                                    href="{{ route('front.user.blogs', getParam()) }}">{{ $keywords['Blogs'] ?? 'Blogs' }}</a>
                            </li>
                        @endif
                        @if (is_array($userPermissions) && in_array('Contact', $userPermissions))
                            <li>
                                <a
                                    @if (request()->routeIs('front.user.detail.view')) href="#contact"
                            @else
                                href="{{ route('front.user.detail.view', getParam()) }}#contact" @endif>{{ $keywords['Contact'] ?? 'Contact' }}</a>
                            </li>
                        @endif
                    </ul>
                </nav>
                <a href="#" class="panel-close">
                    <i class="fal fa-times"></i>
                </a>
            </div>
        </div>
        <!-- End Mobile Slide Menu -->
    </header>
    <!--====== End Header ======-->
