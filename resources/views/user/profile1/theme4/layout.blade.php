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
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.timepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/plugin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/pignose.calendar.min.css') }}">
  <!--====== Common css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/whatsapp.min.css') }}">
  <!--====== Default css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme4/default.css') }}">
  <!--====== Style css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme4/style.css') }}">
  <!--====== Responsive css ======-->
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme4/responsive.css') }}">
  @if ($userCurrentLang->rtl == 1)
    <!--====== Common RTL Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/common-rtl.css') }}">
    <!--====== RTL Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme4/rtl.css') }}">
    <!--====== RTL Responsive css ======-->
    <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme4/rtl-responsive.css') }}">
  @endif
  @php
    if (!empty($userBs->base_color)) {
        $baseColor = $userBs->base_color;
    } else {
        $baseColor = 'F57236';
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
  <link rel="stylesheet" href="{{ asset('assets/front/css/profile/theme4/base-color.php?color=' . $baseColor) }}">

  @yield('styles')
</head>

<body data-spy="scroll" data-target=".main-menu" data-offset="0">
  @if (!empty($userBs->preloader))
    <!--====== Start Preloader ======-->
    <div class="preloader">
      <div class="lds-ellipsis">
        <img src="{{ asset('assets/front/img/user/' . $userBs->preloader) }}" alt="">
      </div>
    </div>
    <!--====== End Preloader ======-->
  @endif

  <!--====== Start Header Section ======-->
  <header class="header-area transparent-header">
    <div class="container">
      <div class="header-navigation">
        <div class="row align-items-center">
          <div class="col-lg-2 col-3">
            <div class="brand-logo">
              <a href="{{ route('front.user.detail.view', getParam()) }}"><img class="lazy"
                  data-src="{{ isset($userBs->logo)
                      ? asset('assets/front/img/user/' . $userBs->logo)
                      : asset('assets/front/img/profile1/theme4/logo.png') }}"
                  alt="Logo"></a>
            </div>
          </div>
          <div class="col-lg-8 col-2">
            <div class="nav-menu">
              <div class="navbar-close"><i class="fal fa-times"></i></div>
              <nav class="main-menu">
                <ul>
                  <li>
                    <a href="{{ route('front.user.detail.view', getParam()) }}"
                      class="nav-link @if (request()->routeIs('front.user.detail.view')) active @endif">{{ $keywords['Home'] ?? 'Home' }}</a>
                  </li>

                  @if (is_array($userPermissions) &&
                          is_array($packagePermissions) &&
                          in_array('Service', $userPermissions) &&
                          in_array('Service', $packagePermissions))
                    <li>
                      <a href="{{ route('front.user.services', getParam()) }}"
                        class="nav-link @if (request()->routeIs('front.user.services') || request()->routeIs('front.user.service.detail')) active @endif">{{ $keywords['Services'] ?? 'Services' }}</a>
                    </li>
                  @endif

                  @if (is_array($userPermissions) &&
                          is_array($packagePermissions) &&
                          in_array('Portfolio', $userPermissions) &&
                          in_array('Portfolio', $packagePermissions))
                    <li>
                      <a href="{{ route('front.user.portfolios', getParam()) }}"
                        class="nav-link @if (request()->routeIs('front.user.portfolios') || request()->routeIs('front.user.portfolio.detail')) active @endif">{{ $keywords['Portfolios'] ?? 'Portfolios' }}</a>
                    </li>
                  @endif
                  @if (is_array($userPermissions) &&
                          is_array($packagePermissions) &&
                          in_array('Blog', $userPermissions) &&
                          in_array('Blog', $packagePermissions))
                    <li>
                      <a href="{{ route('front.user.blogs', getParam()) }}"
                        class="nav-link @if (request()->routeIs('front.user.blogs') || request()->routeIs('front.user.blog.detail')) active @endif">{{ $keywords['Blogs'] ?? 'Blogs' }}</a>
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
                      <a @if (request()->routeIs('front.user.detail.view')) href="#contact"
                                            @else
                                                href="{{ route('front.user.detail.view', getParam()) }}#contact" @endif
                        class="nav-link">{{ $keywords['Contact'] ?? 'Contact' }}</a>
                    </li>
                  @endif
                </ul>
              </nav>
            </div>
          </div>
          <div class="col-lg-2 col-7">
            <div class="header-right-nav d-flex gap-15 align-items-center">
              @if (!empty($userLangs))
                <div class="lang-dropdown">
                  <div class="lang"><img src="{{ asset('assets/front/img/profile1/theme3/lang.png') }}"
                      alt="lang"></div>
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
              @if (is_array($userPermissions) &&
                      is_array($packagePermissions) &&
                      in_array('Appointment', $userPermissions) &&
                      in_array('Appointment', $packagePermissions))
                @if (!Auth::guard('customer')->check())
                  <a href="{{ route('customer.login', getParam()) }}"
                    class="main-btn">{{ $keywords['Login'] ?? 'Login' }}</a>
                @else
                  <div class="dropdown">
                    <a class="main-btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
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
              <div class="navbar-toggler">
                <span></span><span></span><span></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!--====== End Header Section ======-->

  @if (!request()->routeIs('front.user.detail.view'))
    <!--====== Hero Area Start ======-->
    <section class="breadcrumbs-area">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-10">
            <div class="page-title">
              <h1>@yield('br-title')</h1>
              <ul class="breadcrumbs-link">
                <li><a href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                </li>
                <li class="">@yield('br-link')</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--====== Hero Area End ======-->
  @endif

  @yield('content')

  <!--====== Start Footer ======-->
  <footer class="footer-area">
    <div class="objects">
      <img data-src="{{ asset('assets/front/img/profile1/theme4/objects/footer-1.png') }}"
        class="lazy object-shape img-1" alt="objects">
      <img data-src="{{ asset('assets/front/img/profile1/theme4/objects/footer-2.png') }}"
        class="lazy object-shape img-2" alt="objects">
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="footer-content text-center">
            @if (is_array($userPermissions) && in_array('Footer Mail', $userPermissions))
              <span class="sub-title">{{ $keywords['Stay_Connected'] ?? 'Stay Connected' }}</span>
              <h5><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></h5>
            @endif
            <ul class="social-link">
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
    </div>
  </footer>
  <!--====== End Footer ======-->
  <!--====== back-to-top ======-->
  <a href="#" class="back-to-top"><i class="fas fa-angle-up"></i></a>

  <!--====== Jquery ======-->
  <script src="{{ asset('assets/front/js/vendor/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ asset('assets/front/js/profile/whatsapp.min.js') }}"></script>
  <!-- Bootstrap Datepicker -->
  <script src="{{ asset('assets/admin/js/plugin/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- jQuery Timepicker -->
  <script src="{{ asset('assets/front/js/jquery.timepicker.min.js') }}"></script>
  <script src="{{ asset('assets/front/js/pignose.calendar.full.min.js') }}"></script>
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
  <script src="{{ asset('assets/front/js/profile/theme4/main.js') }}"></script>
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
