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
    href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.timepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/pignose.calendar.min.css') }}">
  <!--====== Common css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common.css') }}">
  <!--====== Default css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme3/default.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/whatsapp.min.css') }}">
  <!--====== Style css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme3/style.css') }}">
  <!--====== Responsive css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme3/responsive.css') }}">
  @if ($userCurrentLang->rtl == 1)
    <!--====== Common RTL Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common-rtl.css') }}">
    <!--====== RTL Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme3/rtl.css') }}">
    <!--====== RTL Responsive css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme3/rtl-responsive.css') }}">
  @endif
  @php
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
  @endphp
  @php
    
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
  @foreach ($weekends as $wek)
    <style>
      .pignose-calendar .pignose-calendar-header div.pignose-calendar-week:nth-child({{ $wek + 1 }}) {
        color: #ff6060 !important;
        /* Set the color of the text in the weekend cells */
      }

      .pignose-calendar .pignose-calendar-body .pignose-calendar-row .pignose-calendar-unit-date:nth-child({{ $wek + 1 }}) a {
        color: #ff6060;
        /* Set the color of the text in the weekend cells */
      }
    </style>
  @endforeach
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common-base-color.php?color=' . $baseColor) }}">
  <link rel="stylesheet"
    href="{{ asset('assets/front/css/profile/theme3/base-color.php?color=' . $baseColor . '&color2=' . $secBaseColor) }}">

  @yield('styles')
</head>

<body>


  @if (!empty($userBs->preloader))
    <!--====== Start Preloader ======-->
    <div class="preloader">
      <div class="loader">
        <img src="{{ asset('assets/front/img/user/' . $userBs->preloader) }}" alt="loader">
      </div>
    </div>
    <!--====== End Preloader ======-->
  @endif

  <!--====== Start Header ======-->
  <header class="header-area transparent-header">
    <div class="nav-overlay"></div>
    <div class="container-fluid">
      <div class="row justify-content-between">
        <div class="col-6">
          <div class="navbar-toggler">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
        <div class="col-6">
          <div class="nav-right">
            @if (is_array($userPermissions) &&
                    is_array($packagePermissions) &&
                    in_array('Appointment', $userPermissions) &&
                    in_array('Appointment', $packagePermissions))
              @if (!Auth::guard('customer')->check())
                <a href="{{ route('customer.login', getParam()) }}"
                  class="template-btn">{{ $keywords['Login'] ?? 'Login' }}</a>
              @else
                <div class="dropdown show">
                  <a class="template-btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            @endif

            @if (!empty($userLangs))
              <div class="lang-dropdown">
                <div class="lang"><img src="{{ asset('assets/front/img/profile1/theme3/lang.png') }}" alt="lang">
                </div>
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
        </div>
      </div>
    </div>
  </header>
  <!--====== End Header ======-->
  <!--====== Start Navigation Wrapper ======-->
  <div class="navigation-wrapper">
    <div class="logo text-center">
      <a href="{{ route('front.user.detail.view', getParam()) }}"><img
          src="{{ isset($userBs->logo)
              ? asset('assets/front/img/user/' . $userBs->logo)
              : asset('assets/front/img/profile1/theme3/logo.png') }}"
          alt="Brand Logo"></a>
    </div>
    <div class="primary-menu">
      <nav class="main-menu">
        <ul>
          <li>
            <a href="{{ route('front.user.detail.view', getParam()) }}"
              class="
                            @if (request()->routeIs('front.user.detail.view')) active @endif    
                            "><i
                class="fas fa-home-alt"></i></a>
            <span>{{ $keywords['Home'] ?? 'Home' }}</span>
          </li>

          <li>
            <a href="{{ route('front.user.about', getParam()) }}"
              class="
                            @if (request()->routeIs('front.user.about')) active @endif">
              <i class="fas fa-user"></i>
            </a>
            <span>{{ $keywords['About'] ?? 'About' }}</span>
          </li>

          @if (is_array($userPermissions) &&
                  is_array($packagePermissions) &&
                  in_array('Service', $userPermissions) &&
                  in_array('Service', $packagePermissions))
            <li>
              <a href="{{ route('front.user.services', getParam()) }}"
                class="
                                @if (request()->routeIs('front.user.services') || request()->routeIs('front.user.service.detail')) active @endif"><i
                  class="fas fa-cog"></i></a>
              <span>{{ $keywords['Services'] ?? 'Services' }}</span>
            </li>
          @endif

          @if (is_array($userPermissions) && in_array('Experience', $userPermissions))
            <li>
              <a href="{{ route('front.user.experience', getParam()) }}"
                class="
                                @if (request()->routeIs('front.user.experience')) active @endif"><i
                  class="fas fa-portrait"></i></a>
              <span>{{ $keywords['Experience'] ?? 'Experience' }}</span>
            </li>
          @endif

          @if (is_array($userPermissions) &&
                  is_array($packagePermissions) &&
                  in_array('Portfolio', $userPermissions) &&
                  in_array('Portfolio', $packagePermissions))
            <li>
              <a href="{{ route('front.user.portfolios', getParam()) }}"
                class="
                                @if (request()->routeIs('front.user.portfolios') || request()->routeIs('front.user.portfolio.detail')) active @endif"><i
                  class="fas fa-folder-open"></i></a>
              <span>{{ $keywords['Portfolios'] ?? 'Portfolios' }}</span>
            </li>
          @endif

          @if (is_array($userPermissions) && in_array('Testimonial', $userPermissions))
            <li>
              <a href="{{ route('front.user.testimonial', getParam()) }}"
                class="
                                @if (request()->routeIs('front.user.testimonial')) active @endif"><i
                  class="fas fa-thumbs-up"></i></a>
              <span>{{ $keywords['Testimonial'] ?? 'Testimonial' }}</span>
            </li>
          @endif

          @if (is_array($userPermissions) &&
                  is_array($packagePermissions) &&
                  in_array('Blog', $userPermissions) &&
                  in_array('Blog', $packagePermissions))
            <li>
              <a href="{{ route('front.user.blogs', getParam()) }}"
                class="
                                @if (request()->routeIs('front.user.blogs') || request()->routeIs('front.user.blog.detail')) active @endif"><i
                  class="fas fa-newspaper"></i></a>
              <span>{{ $keywords['Blogs'] ?? 'Blogs' }}</span>
            </li>
          @endif

          @if (is_array($userPermissions) &&
                  is_array($packagePermissions) &&
                  in_array('Appointment', $userPermissions) &&
                  in_array('Appointment', $packagePermissions))
            <li>
              <a href="{{ route('front.user.appointment', getParam()) }}"
                class="
                        @if (request()->routeIs('front.user.appointment') ||
                                request()->routeIs('front.user.appointment.form') ||
                                request()->routeIs('front.user.appointment.booking')) active @endif"><i
                  class="fas fa-file"></i></a>
              <span>{{ $keywords['Appointment'] ?? 'Appointment' }}</span>
            </li>
          @endif

          @if (is_array($userPermissions) && in_array('Contact', $userPermissions))
            <li>
              <a href="{{ route('front.user.contact', getParam()) }}"
                class="
                                @if (request()->routeIs('front.user.contact')) active @endif"><i
                  class="fas fa-comments"></i></a>
              <span>{{ $keywords['Contact'] ?? 'Contact' }}</span>
            </li>
          @endif
        </ul>
      </nav>
    </div>
    <div class="nav-button">
      @if (isset($userBs->cv))
        <a href="{{ asset('assets/front/img/user/cv/' . $userBs->cv) }}" download="{{ $user->username }}.pdf">
          {{ $keywords['Download_CV'] ?? 'Download CV' }}</a>
      @endif
    </div>
  </div>
  <!--====== End Navigation Wrapper ======-->

  <!--====== Start Main Wrapper ======-->
  @if (request()->routeIs('front.user.service.detail') ||
          request()->routeIs('front.user.blog.detail') ||
          request()->routeIs('front.user.portfolio.detail'))
    <div class="pt-120 pb-70">
  @endif
  @yield('content')
  @if (request()->routeIs('front.user.service.detail') ||
          request()->routeIs('front.user.blog.detail') ||
          request()->routeIs('front.user.portfolio.detail'))
    </div>
  @endif
  <!--====== End Main Wrapper ======-->


  <!--====== Jquery ======-->
  <script src="{{ asset('assets/front/js/vendor/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ asset('assets/front/js/profile/whatsapp.min.js') }}"></script>
  <!-- Bootstrap Datepicker -->
  <script src="{{ asset('assets/admin/js/plugin/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- jQuery Timepicker -->
  <script src="{{ asset('assets/front/js/pignose.calendar.full.min.js') }}"></script>
  <script src="{{ asset('assets/front/js/jquery.timepicker.min.js') }}"></script>
  <!--====== plugin js ======-->
  <script src="{{ asset('assets/front/js/plugin.min.js') }}"></script>
  <script>
    "use strict";
    var rtl = {{ $userCurrentLang->rtl }};
  </script>
  <!--====== Common js ======-->
  <script>
    "use strict";
    var rtl = {{ $userCurrentLang->rtl }};
    var $holidays = '<?php echo json_encode($holidays); ?>'
    var $weekends = '<?php echo json_encode($weekends); ?>'
    var timeSlotUrl = "{{ route('getTimeSlot', getParam()) }}";;
    var checkThisSlot = "{{ route('checkThisSlot', getParam()) }}";
  </script>

  <script src="{{ asset('assets/front/js/profile/common.js') }}"></script>
  <!--====== Main js ======-->
  <script src="{{ asset('assets/front/js/profile/theme3/main.js') }}"></script>
  @if (session()->has('success'))
    <script>
      "use strict";
      toastr['success']("{{ __(session('success')) }}");
    </script>
  @endif

  {{-- plugins --}}
  @includeif('user.profile1.partials.plugins')
  {{-- plugins end --}}
  @yield('scripts')
</body>

</html>
