@if (Session::has('admin_lang'))
    @php
        $admin_lang = Session::get('admin_lang');
        $cd = str_replace('admin_', '', $admin_lang);
        $default = \App\Models\Language::where('code', $cd)->first();
    @endphp
@else
    @php
        $default = \App\Models\Language::where('is_default', 1)->first();
    @endphp
@endif
@php
    $admin = Auth::guard('admin')->user();
    if (!empty($admin->role)) {
        $permissions = $admin->role->permissions;
        $permissions = json_decode($permissions, true);
    }
@endphp

<div class="sidebar sidebar-style-2" @if (request()->cookie('admin-theme') == 'dark') data-background-color="dark2" @endif>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (!empty(Auth::guard('admin')->user()->image))
                        <img src="{{ asset('assets/admin/img/propics/' . Auth::guard('admin')->user()->image) }}"
                            alt="..." class="avatar-img rounded">
                    @else
                        <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                            class="avatar-img rounded">
                    @endif
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ Auth::guard('admin')->user()->first_name }}
                            <span class="user-level">{{ __('Admin') }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('admin.editProfile') }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.changePass') }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.logout') . '?language=' . $default->code }}">
                                    <span class="link-collapse">{{ __('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">

                <div class="row mb-2">
                    <div class="col-12">
                        <form action="">
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
                                    value="" placeholder="{{ __('Search Menu Here') }}...">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Dashboard --}}
                <li class="nav-item @if (request()->path() == 'admin/dashboard') active @endif">
                    <a href="{{ route('admin.dashboard') . '?language=' . $default->code }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                {{-- Package Management --}}
                @if (empty($admin->role) || (!empty($permissions) && in_array('Packages', $permissions)))
                    <li
                        class="nav-item
                    @if (request()->path() == 'admin/package/settings') active
                    @elseif(request()->path() == 'admin/packages') active
                    @elseif(request()->path() == 'admin/coupon') active
                    @elseif(request()->path() == 'admin/package/features') active
                    @elseif(request()->is('admin/package/*/edit')) active @endif">
                        <a data-toggle="collapse" href="#packageManagement">
                            <i class="fas fa-receipt"></i>
                            <p>{{ __('Package Management') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'admin/package/settings') show
                        @elseif(request()->path() == 'admin/packages') show
                        @elseif(request()->path() == 'admin/coupon') show
                        @elseif(request()->path() == 'admin/package/features') show
                        @elseif(request()->is('admin/package/*/edit')) show @endif"
                            id="packageManagement">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/package/settings') active @endif">
                                    <a href="{{ route('admin.package.settings') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->path() == 'admin/coupon') active
                            @elseif(request()->routeIs('admin.coupon.edit')) active @endif">
                                    <a href="{{ route('admin.coupon.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Coupons') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/package/features') active @endif">
                                    <a href="{{ route('admin.package.features') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Package Features') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->path() == 'admin/packages') active
                                @elseif(request()->is('admin/package/*/edit')) active @endif">
                                    <a href="{{ route('admin.package.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Packages') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Log', $permissions)))
                    <li class="nav-item
                        @if (request()->path() == 'admin/payment-log') active @endif">
                        <a href="{{ route('admin.payment-log.index') . '?language=' . $default->code }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <p>{{ __('Payment Log') }}</p>
                        </a>
                    </li>
                @endif

                @if (empty($admin->role) || (!empty($permissions) && in_array('Custom Domains', $permissions)))
                    <li
                        class="nav-item
                        @if (request()->path() == 'admin/domains') active
                        @elseif(request()->path() == 'admin/domain/texts') active @endif">
                        <a data-toggle="collapse" href="#customDomains">
                            <i class="fas fa-link"></i>
                            <p>{{ __('Custom Domains') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                            @if (request()->path() == 'admin/domains') show
                            @elseif(request()->path() == 'admin/domain/texts') show @endif"
                            id="customDomains">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/domain/texts') active @endif">
                                    <a href="{{ route('admin.custom-domain.texts') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Request Page Texts') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/domains' && empty(request()->input('type'))) active @endif">
                                    <a href="{{ route('admin.custom-domain.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('All Requests') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/domains' && request()->input('type') == 'pending') active @endif">
                                    <a href="{{ route('admin.custom-domain.index', ['type' => 'pending']) . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Pending Requests') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/domains' && request()->input('type') == 'connected') active @endif">
                                    <a href="{{ route('admin.custom-domain.index', ['type' => 'connected']) . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Connected Requests') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/domains' && request()->input('type') == 'rejected') active @endif">
                                    <a href="{{ route('admin.custom-domain.index', ['type' => 'rejected']) . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Rejected Requests') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (empty($admin->role) || (!empty($permissions) && in_array('Subdomains', $permissions)))
                    <li class="nav-item
                        @if (request()->path() == 'admin/subdomains') active @endif">
                        <a data-toggle="collapse" href="#subDomains">
                            <i class="fas fa-link"></i>
                            <p>{{ __('Subdomains') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                            @if (request()->path() == 'admin/subdomains') show @endif"
                            id="subDomains">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/subdomains' && empty(request()->input('type'))) active @endif">
                                    <a href="{{ route('admin.subdomain.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('All Subdomains') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/subdomains' && request()->input('type') == 'pending') active @endif">
                                    <a href="{{ route('admin.subdomain.index', ['type' => 'pending']) . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Pending Subdomains') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/subdomains' && request()->input('type') == 'connected') active @endif">
                                    <a href="{{ route('admin.subdomain.index', ['type' => 'connected']) . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Connected Subdomains') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                {{-- Registered Users --}}
                @if (empty($admin->role) || (!empty($permissions) && in_array('Registered Users', $permissions)))
                    <li
                        class="nav-item
         @if (request()->path() == 'admin/register/users') active
         @elseif(request()->is('admin/register/user/details/*')) active
         @elseif (request()->routeIs('register.user.changePass')) active @endif">
                        <a href="{{ route('admin.register.user') . '?language=' . $default->code }}">
                            <i class="la flaticon-users"></i>
                            <p>{{ __('Registered Users') }}</p>
                        </a>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Menu Builder', $permissions)))
                    {{-- Menu Builder --}}
                    <li class="nav-item
            @if (request()->path() == 'admin/menu-builder') active @endif">
                        <a href="{{ route('admin.menu_builder.index') . '?language=' . $default->code }}">
                            <i class="fas fa-bars"></i>
                            <p>{{ __('Menu Builder') }}</p>
                        </a>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Home Page', $permissions)))
                    {{-- Home Page --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/features') active
          @elseif(request()->path() == 'admin/introsection') active
          @elseif(request()->routeIs('admin.herosection.imgtext')) active
          @elseif(request()->is('admin/feature/*/edit')) active
          @elseif(request()->is('admin/process')) active
          @elseif(request()->is('admin/process/*/edit')) active
          @elseif(request()->path() == 'admin/testimonials') active
          @elseif(request()->is('admin/testimonial/*/edit')) active
          @elseif(request()->path() == 'admin/menu/section') active
          @elseif(request()->path() == 'admin/special/section') active
          @elseif(request()->path() == 'admin/herosection/video') active
          @elseif(request()->path() == 'admin/home-page-text-section') active
          @elseif(request()->path() == 'admin/partners') active
          @elseif(request()->is('admin/partner/*/edit')) active
          @elseif(request()->path() == 'admin/sections') active @endif">
                        <a data-toggle="collapse" href="#home">
                            <i class="la flaticon-home"></i>
                            <p>{{ __('Home Page') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/features') show
            @elseif(request()->path() == 'admin/introsection') show
            @elseif(request()->routeIs('admin.herosection.imgtext')) show
            @elseif(request()->is('admin/feature/*/edit')) show
            @elseif(request()->is('admin/process')) show
            @elseif(request()->is('admin/process/*/edit')) show
            @elseif(request()->path() == 'admin/testimonials') show
            @elseif(request()->is('admin/testimonial/*/edit')) show
            @elseif(request()->path() == 'admin/special/section') show
            @elseif(request()->path() == 'admin/home-page-text-section') show
            @elseif(request()->path() == 'admin/partners') show
            @elseif(request()->is('admin/partner/*/edit')) show
            @elseif(request()->path() == 'admin/sections') show @endif"
                            id="home">
                            <ul class="nav nav-collapse">

                                <li class="@if (request()->routeIs('admin.herosection.imgtext')) active @endif">
                                    <a
                                        href="{{ route('admin.herosection.imgtext') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Hero Section') }}</span>
                                    </a>
                                </li>

                                <li class="@if (request()->path() == 'admin/introsection') active @endif">
                                    <a href="{{ route('admin.introsection.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Intro Section') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="
                @if (request()->path() == 'admin/features') active
                @elseif(request()->is('admin/feature/*/edit')) active @endif">
                                    <a href="{{ route('admin.feature.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Features') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="
                @if (request()->path() == 'admin/process') active
                @elseif(request()->is('admin/process/*/edit')) active @endif">
                                    <a href="{{ route('admin.process.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Work Process') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="
                @if (request()->path() == 'admin/testimonials') active
                @elseif(request()->is('admin/testimonial/*/edit')) active @endif">
                                    <a href="{{ route('admin.testimonial.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Testimonials') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                @if (request()->path() == 'admin/partners') active
                @elseif(request()->is('admin/partner/*/edit')) active @endif">
                                    <a href="{{ route('admin.partner.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Partners') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/home-page-text-section') active @endif">
                                    <a
                                        href="{{ route('admin.home.page.text.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Section Titles') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                                @if (request()->path() == 'admin/sections') active @endif">
                                    <a href="{{ route('admin.sections.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Section Hide / Show') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Footer', $permissions)))
                    {{-- Footer --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/footers') active
          @elseif(request()->path() == 'admin/ulinks') active @endif">
                        <a data-toggle="collapse" href="#footer">
                            <i class="fas fa-shoe-prints"></i>
                            <p>{{ __('Footer') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/footers') show
            @elseif(request()->path() == 'admin/ulinks') show @endif"
                            id="footer">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/footers') active @endif">
                                    <a href="{{ route('admin.footer.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Image & Text') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/ulinks') active @endif">
                                    <a href="{{ route('admin.ulink.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Useful Links') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif



                @if (empty($admin->role) || (!empty($permissions) && in_array('Pages', $permissions)))
                    {{-- Dynamic Pages --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/page/create') active
          @elseif(request()->path() == 'admin/pages') active
          @elseif(request()->is('admin/page/*/edit')) active @endif">
                        <a data-toggle="collapse" href="#pages">
                            <i class="la flaticon-file"></i>
                            <p>{{ __('Pages') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/page/create') show
            @elseif(request()->path() == 'admin/pages') show
            @elseif(request()->is('admin/page/*/edit')) show @endif"
                            id="pages">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/page/create') active @endif">
                                    <a href="{{ route('admin.page.create') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Create Page') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                @if (request()->path() == 'admin/pages') active
                @elseif(request()->is('admin/page/*/edit')) active @endif">
                                    <a href="{{ route('admin.page.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Pages') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Blogs', $permissions)))
                    {{-- Blogs --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/bcategorys') active
          @elseif(request()->path() == 'admin/blogs') active
          @elseif(request()->is('admin/blog/*/edit')) active @endif">
                        <a data-toggle="collapse" href="#blog">
                            <i class="fas fa-blog"></i>
                            <p>{{ __('Blogs') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/bcategorys') show
            @elseif(request()->path() == 'admin/blogs') show
            @elseif(request()->is('admin/blog/*/edit')) show @endif"
                            id="blog">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/bcategorys') active @endif">
                                    <a href="{{ route('admin.bcategory.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Category') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                @if (request()->path() == 'admin/blogs') active
                @elseif(request()->is('admin/blog/*/edit')) active @endif">
                                    <a href="{{ route('admin.blog.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Blogs') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('FAQ Management', $permissions)))
                    {{-- FAQ Management --}}
                    <li class="nav-item
           @if (request()->path() == 'admin/faqs') active @endif">
                        <a href="{{ route('admin.faq.index') . '?language=' . $default->code }}">
                            <i class="la flaticon-round"></i>
                            <p>{{ __('FAQ Management') }}</p>
                        </a>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Contact Page', $permissions)))
                    {{-- Contact Page --}}
                    <li class="nav-item
          @if (request()->path() == 'admin/contact') active @endif">
                        <a href="{{ route('admin.contact.index') . '?language=' . $default->code }}">
                            <i class="la flaticon-whatsapp"></i>
                            <p>{{ __('Contact Page') }}</p>
                        </a>
                    </li>
                @endif


                {{-- Announcement Popup --}}
                @if (empty($admin->role) || (!empty($permissions) && in_array('Announcement Popup', $permissions)))
                    <li
                        class="nav-item
                    @if (request()->path() == 'admin/popup/create') active
                    @elseif(request()->path() == 'admin/popup/types') active
                    @elseif(request()->is('admin/popup/*/edit')) active
                    @elseif(request()->path() == 'admin/popups') active @endif">
                        <a data-toggle="collapse" href="#announcementPopup">
                            <i class="fas fa-bullhorn"></i>
                            <p>{{ __('Announcement Popup') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'admin/popup/create') show
                        @elseif(request()->path() == 'admin/popup/types') show
                        @elseif(request()->path() == 'admin/popups') show
                        @elseif(request()->is('admin/popup/*/edit')) show @endif"
                            id="announcementPopup">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->path() == 'admin/popup/types') active
                                @elseif(request()->path() == 'admin/popup/create') active @endif">
                                    <a href="{{ route('admin.popup.types') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Add Popup') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->path() == 'admin/popups') active
                                @elseif(request()->is('admin/popup/*/edit')) active @endif">
                                    <a href="{{ route('admin.popup.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Popups') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Push Notification', $permissions)))
                    {{-- Push Notification --}}
                    <li
                        class="nav-item
                  @if (request()->path() == 'admin/pushnotification/settings') active
                  @elseif(request()->path() == 'admin/pushnotification/send') active @endif">
                        <a data-toggle="collapse" href="#pushNotification">
                            <i class="far fa-bell"></i>
                            <p>{{ __('Push Notification') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                    @if (request()->path() == 'admin/pushnotification/settings') show
                    @elseif(request()->path() == 'admin/pushnotification/send') show @endif"
                            id="pushNotification">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/pushnotification/settings') active @endif">
                                    <a href="{{ route('admin.pushnotification.settings') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/pushnotification/send') active @endif">
                                    <a href="{{ route('admin.pushnotification.send') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Send Notification') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Subscribers', $permissions)))
                    {{-- Subscribers --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/subscribers') active
          @elseif(request()->path() == 'admin/mailsubscriber') active @endif">
                        <a data-toggle="collapse" href="#subscribers">
                            <i class="la flaticon-envelope"></i>
                            <p>{{ __('Subscribers') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/subscribers') show
            @elseif(request()->path() == 'admin/mailsubscriber') show @endif"
                            id="subscribers">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/subscribers') active @endif">
                                    <a href="{{ route('admin.subscriber.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Subscribers') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/mailsubscriber') active @endif">
                                    <a href="{{ route('admin.mailsubscriber') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Mail to Subscribers') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Gateways', $permissions)))
                    {{-- Payment Gateways --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/gateways') active
          @elseif(request()->path() == 'admin/offline/gateways') active @endif">
                        <a data-toggle="collapse" href="#gateways">
                            <i class="la flaticon-paypal"></i>
                            <p>{{ __('Payment Gateways') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/gateways') show
            @elseif(request()->path() == 'admin/offline/gateways') show @endif"
                            id="gateways">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/gateways') active @endif">
                                    <a href="{{ route('admin.gateway.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Online Gateways') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/offline/gateways') active @endif">
                                    <a href="{{ route('admin.gateway.offline') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Offline Gateways') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif



                @if (empty($admin->role) || (!empty($permissions) && in_array('Settings', $permissions)))
                    {{-- Basic Settings --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/favicon') active
          @elseif(request()->path() == 'admin/logo') active
          @elseif(request()->path() == 'admin/preloader') active
          @elseif(request()->path() == 'admin/basicinfo') active
          @elseif(request()->path() == 'admin/social') active
          @elseif(request()->is('admin/social/*')) active
          @elseif(request()->path() == 'admin/breadcrumb') active
          @elseif(request()->path() == 'admin/heading') active
          @elseif(request()->path() == 'admin/script') active
          @elseif(request()->path() == 'admin/seo') active
          @elseif(request()->path() == 'admin/maintainance') active
          @elseif(request()->path() == 'admin/cookie-alert') active
          @elseif(request()->path() == 'admin/mail-from-admin') active
          @elseif(request()->path() == 'admin/mail-to-admin') active
          @elseif(request()->routeIs('admin.product.tags')) active
          @elseif(request()->path() == 'admin/seo') active
          @elseif (request()->routeIs('admin.mail_templates')) active
          @elseif (request()->routeIs('admin.edit_mail_template')) active @endif">
                        <a data-toggle="collapse" href="#basic">
                            <i class="la flaticon-settings"></i>
                            <p>{{ __('Settings') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
            @if (request()->path() == 'admin/favicon') show
            @elseif(request()->path() == 'admin/logo') show
            @elseif(request()->path() == 'admin/preloader') show
            @elseif(request()->path() == 'admin/basicinfo') show
            @elseif(request()->path() == 'admin/social') show
            @elseif(request()->is('admin/social/*')) show
            @elseif(request()->path() == 'admin/breadcrumb') show
            @elseif(request()->path() == 'admin/heading') show
            @elseif(request()->path() == 'admin/script') show
            @elseif(request()->path() == 'admin/seo') show
            @elseif(request()->path() == 'admin/maintainance') show
            @elseif(request()->path() == 'admin/cookie-alert') show
            @elseif(request()->path() == 'admin/mail-from-admin') show
            @elseif(request()->path() == 'admin/mail-to-admin') show
            @elseif(request()->routeIs('admin.product.tags')) show
            @elseif (request()->routeIs('admin.mail_to_admin')) show
            @elseif(request()->path() == 'admin/seo') show
            @elseif (request()->routeIs('admin.mail_templates')) show
            @elseif (request()->routeIs('admin.edit_mail_template')) show @endif"
                            id="basic">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/favicon') active @endif">
                                    <a href="{{ route('admin.favicon') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Favicon') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/logo') active @endif">
                                    <a href="{{ route('admin.logo') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Logo') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/basicinfo') active @endif">
                                    <a href="{{ route('admin.basicinfo') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('General Settings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="submenu
                                @if (request()->routeIs('admin.mail_from_admin')) selected
                                @elseif (request()->routeIs('admin.mail_to_admin')) selected
                                @elseif (request()->routeIs('admin.mail_templates')) selected
                                @elseif (request()->routeIs('admin.edit_mail_template')) selected @endif">
                                    <a data-toggle="collapse" href="#emailset"
                                        aria-expanded="{{ request()->path() == 'admin/mail-from-admin' || request()->path() == 'admin/mail-to-admin' || request()->routeIs('admin.mail_templates') || request()->routeIs('admin.edit_mail_template') ? 'true' : 'false' }}">
                                        <span class="sub-item">{{ __('Email Settings') }}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse {{ request()->path() == 'admin/mail-from-admin' || request()->path() == 'admin/mail-to-admin' || request()->routeIs('admin.mail_templates') || request()->routeIs('admin.edit_mail_template') ? 'show' : '' }}"
                                        id="emailset">
                                        <ul class="nav nav-collapse subnav">
                                            <li class="@if (request()->path() == 'admin/mail-from-admin') active @endif">
                                                <a href="{{ route('admin.mailFromAdmin') . '?language=' . $default->code }}">
                                                    <span class="sub-item">{{ __('Mail from Admin') }}</span>
                                                </a>
                                            </li>
                                            <li class="@if (request()->path() == 'admin/mail-to-admin') active @endif">
                                                <a href="{{ route('admin.mailToAdmin') . '?language=' . $default->code }}">
                                                    <span class="sub-item">{{ __('Mail to Admin') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                                            @if (request()->routeIs('admin.mail_templates')) active
                                            @elseif (request()->routeIs('admin.edit_mail_template')) active @endif">
                                                <a href="{{ route('admin.mail_templates') . '?language=' . $default->code }}">
                                                    <span class="sub-item">{{ __('Mail Templates') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="@if (request()->path() == 'admin/preloader') active @endif">
                                    <a href="{{ route('admin.preloader') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Preloader') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/breadcrumb') active @endif">
                                    <a href="{{ route('admin.breadcrumb') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Breadcrumb') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="@if (request()->path() == 'admin/social') active
                                @elseif(request()->is('admin/social/*')) active @endif">
                                    <a href="{{ route('admin.social.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Social Links') }}</span>
                                    </a>
                                </li>

                                <li class="@if (request()->path() == 'admin/script') active @endif">
                                    <a href="{{ route('admin.script') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Plugins') }}</span>
                                    </a>
                                </li>

                                <li class="@if (request()->path() == 'admin/maintainance') active @endif">
                                    <a href="{{ route('admin.maintainance') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Maintainance Mode') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/cookie-alert') active @endif">
                                    <a href="{{ route('admin.cookie.alert') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Cookie Alert') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/seo') active @endif">
                                    <a href="{{ route('admin.seo', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ __('SEO Information') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (empty($admin->role) || (!empty($permissions) && in_array('Language Management', $permissions)))
                    {{-- Language Management Page --}}
                    <li
                        class="nav-item   @if (request()->path() == 'admin/languages') active  @elseif(request()->is('admin/language/*/edit')) active    @elseif(request()->is('admin/language/*/admin-keyword')) active   @elseif(request()->is('admin/language/*/edit/keyword')) active @endif">
                        <a href="{{ route('admin.language.index') . '?language=' . $default->code }}">
                            <i class="la flaticon-chat-8"></i>
                            <p>{{ __('Language Management') }}</p>
                        </a>
                    </li>
                @endif

                @if (empty($admin->role) || (!empty($permissions) && in_array('Language Management', $permissions)))
                    <li
                        class="nav-item  @if (request()->path() == 'admin/user-language/keywords') active
                        @elseif (request()->path() == 'admin/user-language/settings') active @endif ">
                        <a data-toggle="collapse" href="#ulangs">
                            <i class="la flaticon-chat-8"></i>
                            <p>{{ __('Tenant Default Language') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse  @if (request()->path() == 'admin/user-language/keywords') show
                            @elseif (request()->path() == 'admin/user-language/settings') show @endif"
                            id="ulangs">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'admin/user-language/settings') active @endif">
                                    <a href="{{ route('admin.userlanguage.settings') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'admin/user-language/keywords') active @endif">
                                    <a href="{{ route('admin.userlanguage.keywords') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ __('Keywords') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- Language Management Page --}}
                @if (empty($admin->role) || (!empty($permissions) && in_array('Role Management', $permissions)))
                    {{-- Role Management Page --}}
                    <li
                        class="nav-item
          @if (request()->path() == 'admin/roles') active
          @elseif(request()->is('admin/role/*/permissions/manage')) active @endif">
                        <a href="{{ route('admin.role.index') . '?language=' . $default->code }}">
                            <i class="la flaticon-multimedia-2"></i>
                            <p>{{ __('Role Management') }}</p>
                        </a>
                    </li>
                @endif



                @if (empty($admin->role) || (!empty($permissions) && in_array('Admins Management', $permissions)))
                    {{-- Admins Management Page --}}
                    <li
                        class="nav-item
           @if (request()->path() == 'admin/users') active
           @elseif(request()->is('admin/user/*/edit')) active @endif">
                        <a href="{{ route('admin.user.index') . '?language=' . $default->code }}">
                            <i class="la flaticon-user-5"></i>
                            <p>{{ __('Admins Management') }}</p>
                        </a>
                    </li>
                @endif

                @if (empty($admin->role) || (!empty($permissions) && in_array('Sitemap', $permissions)))
                    {{-- Sitemap --}}
                    <li class="nav-item
            @if (request()->path() == 'admin/sitemap') active @endif">
                        <a href="{{ route('admin.sitemap.index') . '?language=' . $default->code }}">
                            <i class="fa fa-sitemap"></i>
                            <p>{{ __('Sitemap') }}</p>
                        </a>
                    </li>
                @endif


                {{-- Cache Clear --}}
                <li class="nav-item">
                    <a href="{{ route('admin.cache.clear') . '?language=' . $default->code }}">
                        <i class="la flaticon-close"></i>
                        <p>{{ __('Clear Cache') }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
