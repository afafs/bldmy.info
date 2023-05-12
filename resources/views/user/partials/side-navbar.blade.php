@php
    $user = Auth::guard('web')->user();
    
    $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
    if (!empty($user)) {
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
        $permissions = json_decode($permissions, true);
    }
@endphp

@if (Session::has('currentLangCode'))
    @php
        $default = \App\Models\User\Language::where('code', Session::get('currentLangCode'))
            ->where('user_id', $user->id)
            ->first();
    @endphp
@else
    @php
        $default = \App\Models\User\Language::where('is_default', 1)
            ->where('user_id', $user->id)
            ->first();
    @endphp
@endif


<div class="sidebar sidebar-style-2" @if (request()->cookie('user-theme') == 'dark') data-background-color="dark2" @endif>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (!empty(Auth::user()->photo))
                        <img src="{{ asset('assets/front/img/user/' . Auth::user()->photo) }}" alt="..."
                            class="avatar-img rounded">
                    @else
                        <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                            class="avatar-img rounded">
                    @endif
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                            <span class="user-level">{{ auth()->user()->username }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            @if (!is_null($package))
                                <li>
                                    <a href="{{ route('user-profile-update', ['language' => $default->code]) }}">
                                        <span
                                            class="link-collapse">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('user.changePass', ['language' => $default->code]) }}">
                                    <span
                                        class="link-collapse">{{ $keywords['Change_Password'] ?? __('Change Password') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user-logout', ['language' => $default->code]) }}">
                                    <span class="link-collapse">{{ $keywords['Logout'] ?? __('Logout') }}</span>
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
                                    value=""
                                    placeholder="{{ $keywords['Search_Menu_Here'] ?? __('Search Menu Here') }} ...">
                            </div>
                        </form>
                    </div>
                </div>
                <li class="nav-item
                @if (request()->path() == 'user/dashboard') active @endif">
                    <a href="{{ route('user-dashboard', ['language' => $default->code]) }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ $keywords['Dashboard'] ?? __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item
                @if (request()->path() == 'user/profile') active @endif">
                    <a href="{{ route('user-profile', ['language' => $default->code]) }}">
                        <i class="far fa-user-circle"></i>
                        <p>{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }} </p>
                    </a>
                </li>
                @if (!is_null($package))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/domains') active
                    @elseif(request()->path() == 'user/subdomain') active @endif">
                        <a data-toggle="collapse" href="#domains">
                            <i class="fas fa-link"></i>
                            <p>{{ $keywords['Domains_and_URLs'] ?? __('Domains & URLs') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/domains') show
                        @elseif(request()->path() == 'user/subdomain') show @endif"
                            id="domains">
                            <ul class="nav nav-collapse">
                                @if (!empty($permissions) && in_array('Custom Domain', $permissions))
                                    <li
                                        class="
                                    @if (request()->path() == 'user/domains') active @endif">
                                        <a href="{{ route('user-domains', ['language' => $default->code]) }}">
                                            <span
                                                class="sub-item">{{ $keywords['Custom_Domain'] ?? __('Custom Domain') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (!empty($permissions) && in_array('Subdomain', $permissions))
                                    <li
                                        class="
                                    @if (request()->path() == 'user/subdomain') active @endif">
                                        <a href="{{ route('user-subdomain', ['language' => $default->code]) }}">
                                            <span
                                                class="sub-item">{{ $keywords['Subdomain_and_Path_URL'] ?? __('Subdomain & Path URL') }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li
                                        class="
                                    @if (request()->path() == 'user/subdomain') active @endif">
                                        <a href="{{ route('user-subdomain', ['language' => $default->code]) }}">
                                            <span
                                                class="sub-item">{{ $keywords['Path_Based_URL'] ?? __('Path Based URL') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- start Register User --}}
                <li
                    class="nav-item  @if (request()->path() == 'user/register-user') active 
                    @elseif (request()->routeIs('register.customer.view')) active 
                    @elseif (request()->routeIs('register.customer.changePass')) active @endif">
                    <a href="{{ route('user.register-user', ['language' => $default->code]) }}">
                        <i class="fas fa-users"></i>
                        <p>{{ $keywords['Registered_User'] ?? __('Registered User') }}</p>
                    </a>
                </li>
                {{-- End Register User --}}
                @if (!is_null($package))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/favicon') active
                    @elseif(request()->path() == 'user/theme/version') active
                    @elseif(request()->path() == 'user/logo') active
                    @elseif(request()->path() == 'user/preloader') active
                    @elseif(request()->path() == 'user/color') active
                    @elseif (request()->routeIs('user.basic_settings.general-settings')) active
                    @elseif(request()->path() == 'user/social') active
                    @elseif(request()->is('user/social/*')) active
                    @elseif(request()->routeIs('user.basic_settings.mail_templates')) active
                    @elseif(request()->routeIs('user.basic_settings.edit_mail_template')) active
                    @elseif(request()->routeIs('user.mail.information')) active
                    @elseif(request()->routeIs('user.plugins')) active
                    @elseif(request()->path() == 'user/basic_settings/seo') active @endif">
                        <a data-toggle="collapse" href="#basic">
                            <i class="la flaticon-settings"></i>
                            <p>{{ $keywords['Settings'] ?? __('Settings') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/favicon') show
                        @elseif(request()->path() == 'user/theme/version') show
                        @elseif(request()->path() == 'user/logo') show
                        @elseif(request()->path() == 'user/preloader') show
                        @elseif (request()->routeIs('user.basic_settings.general-settings')) show
                        @elseif(request()->path() == 'user/color') show
                        @elseif(request()->path() == 'user/social') show
                        @elseif(request()->is('user/social/*')) show
                        @elseif(request()->routeIs('user.basic_settings.mail_templates')) show
                        @elseif(request()->routeIs('user.basic_settings.edit_mail_template')) show
                        @elseif(request()->routeIs('user.mail.information')) show
                        @elseif(request()->routeIs('user.plugins')) show
                        @elseif(request()->path() == 'user/basic_settings/seo') show @endif"
                            id="basic">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'user/theme/version') active @endif">
                                    <a href="{{ route('user.theme.version', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Themes'] ?? __('Themes') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'user/favicon') active @endif">
                                    <a href="{{ route('user.favicon', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Favicon'] ?? __('Favicon') }}</span>
                                    </a>
                                </li>
                                @if ($userBs->theme != 5)
                                    <li class="@if (request()->path() == 'user/logo') active @endif">
                                        <a href="{{ route('user.logo', ['language' => $default->code]) }}">
                                            <span class="sub-item">{{ $keywords['Logo'] ?? __('Logo') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if ($userBs->theme != 8 && $userBs->theme != 7 && $userBs->theme != 6)
                                    <li class="@if (request()->path() == 'user/preloader') active @endif">
                                        <a href="{{ route('user.preloader', ['language' => $default->code]) }}">
                                            <span
                                                class="sub-item">{{ $keywords['Preloader'] ?? __('Preloader') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="@if (request()->path() == 'user/color') active @endif">
                                    <a href="{{ route('user.color.index', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Color_Settings'] ?? __('Color Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('user.basic_settings.general-settings') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.general-settings', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['General_Settings'] ?? __('General Settings') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->path() == 'user/social') active
                                @elseif(request()->is('user/social/*')) active @endif">
                                    <a href="{{ route('user.social.index', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Social_Links'] ?? __('Social Links') }}</span>
                                    </a>
                                </li>
                                @php
                                    $plugins = ['Google Analytics', 'Disqus', 'WhatsApp', 'Facebook Pixel', 'Tawk.to'];
                                @endphp

                                @if (!empty($permissions) && array_intersect($permissions, $plugins))
                                    <li class="{{ request()->routeIs('user.plugins') ? 'active' : '' }}">
                                        <a href="{{ route('user.plugins', ['language' => $default->code]) }}">
                                            <span class="sub-item">{{ $keywords['Plugins'] ?? __('Plugins') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="@if (request()->path() == 'user/basic_settings/seo') active @endif">
                                    <a href="{{ route('admin.basic_settings.seo', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['SEO_Information'] ?? __('SEO Information') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="submenu
                                @if (request()->routeIs('user.basic_settings.mail_templates')) selected
                                @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) selected
                                @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) selected
                                @elseif (request()->path() == 'user/mail/information/subscriber') selected @endif">
                                    <a data-toggle="collapse" href="#emailset"
                                        aria-expanded="{{ request()->path() == 'user/mail/information/subscriber' || request()->routeIs('user.basic_settings.mail_templates') || request()->routeIs('user.basic_settings.edit_mail_template') ? 'true' : 'false' }}">
                                        <span
                                            class="sub-item">{{ $keywords['Email_Settings'] ?? __('Email Settings') }}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('user.basic_settings.mail_templates') || request()->routeIs('user.basic_settings.edit_mail_template') || request()->path() == 'user/mail/information/subscriber' ? 'show' : '' }}"
                                        id="emailset">
                                        <ul class="nav nav-collapse subnav">
                                            <li
                                                class="
                                            @if (request()->routeIs('user.basic_settings.mail_templates')) active
                                            @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) active @endif">
                                                <a
                                                    href="{{ route('user.basic_settings.mail_templates', ['language' => $default->code]) }}">
                                                    <span
                                                        class="sub-item">{{ $keywords['Mail_Templates'] ?? __('Mail Templates') }}</span>
                                                </a>
                                            </li>
                                            <li
                                                class="
                                            @if (request()->path() == 'user/mail/information/subscriber') active @endif">
                                                <a
                                                    href="{{ route('user.mail.information', ['language' => $default->code]) }}">
                                                    <span
                                                        class="sub-item">{{ $keywords['Mail_Information'] ?? __('Mail Information') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (!is_null($package))
                    <li class="nav-item @if (request()->path() == 'user/home-page-text/edit') active @endif">
                        <a href="{{ route('user.home.page.text.edit', ['language' => $default->code]) }}">
                            <i class="fas fa-home"></i>
                            <p>{{ $keywords['Home_Sections'] ?? __('Home Sections') }}</p>
                        </a>
                    </li>
                    <li class="nav-item
                    @if (request()->path() == 'user/preference') active @endif">
                        <a href="{{ route('user.preference.index', ['language' => $default->code]) }}">
                            <i class="fas fa-toggle-on"></i>
                            <p>{{ $keywords['Preference'] ?? __('Preference') }}</p>
                        </a>
                    </li>
                @endif
                @if ($userBs->theme != 3)
                    @if (!empty($permissions) && in_array('Skill', $permissions))
                        <li
                            class="nav-item
                        @if (request()->path() == 'user/skills') active
                        @elseif(request()->is('user/skill/*/edit')) active @endif">
                            <a href="{{ route('user.skill.index') . '?language=' . $default->code }}">
                                <i class="fas fa-pencil-ruler"></i>
                                <p>{{ $keywords['Skills'] ?? __('Skills') }}</p>
                            </a>
                        </li>
                    @endif
                @endif
                @if (!empty($permissions) && in_array('Service', $permissions))
                    <li
                        class="nav-item  @if (request()->path() == 'user/services') active 
                        @elseif (request()->is('user/service/*/edit')) active @endif">
                        <a href="{{ route('user.services.index') . '?language=' . $default->code }}">
                            <i class="fas fa-hands"></i>
                            <p>{{ $keywords['Services'] ?? __('Services') }}</p>
                        </a>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('Experience', $permissions))
                    @if ($userBs->theme != 6 && $userBs->theme != 7)
                        <li
                            class="nav-item
                    @if (request()->path() == 'user/experiences') active
                    @elseif(request()->is('user/experience/*/edit')) active
                    @elseif(request()->path() == 'user/job-experiences') active
                    @elseif(request()->is('user/job-experience/*/edit')) active @endif">
                            <a data-toggle="collapse" href="#experience">
                                <i class="fas fa-user-cog"></i>
                                <p>{{ $keywords['Experiences'] ?? __('Experiences') }}</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse
                        @if (request()->path() == 'user/experiences') show
                        @elseif(request()->is('user/experience/*/edit')) show
                        @elseif(request()->path() == 'user/job-experiences') show
                        @elseif(request()->is('user/job-experience/*/edit')) show @endif"
                                id="experience">
                                <ul class="nav nav-collapse">
                                    <li
                                        class="
                                @if (request()->path() == 'user/job-experiences') active
                                @elseif(request()->is('user/job-experience/*/edit')) active @endif">
                                        <a
                                            href="{{ route('user.job.experiences.index') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Job_Experiences'] ?? __('Job Experiences') }}</span>
                                        </a>
                                    </li>
                                    <li
                                        class="@if (request()->path() == 'user/experiences') active
                                @elseif(request()->is('user/experience/*/edit')) active @endif">
                                        <a
                                            href="{{ route('user.experience.index') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Educations'] ?? __('Educations') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                @endif
                @if (!empty($permissions) && in_array('Achievements', $permissions))
                    <li
                        class="nav-item  @if (request()->path() == 'user/achievements') active 
                        @elseif(request()->is('user/achievement/*/edit')) active @endif">
                        <a href="{{ route('user.achievement.index', ['language' => $default->code]) }}">
                            <i class="fas fa-trophy"></i>
                            <p>{{ $keywords['Achievements'] ?? __('Achievements') }}</p>
                        </a>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('Portfolio', $permissions))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/portfolio-categories') active
                    @elseif(request()->path() == 'user/portfolios') active
                    @elseif(request()->is('user/portfolio/*/edit')) active @endif">
                        <a data-toggle="collapse" href="#portfolio">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <p>{{ $keywords['Portfolios'] ?? __('Portfolios') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/portfolio-categories') show
                        @elseif(request()->path() == 'user/portfolios') show
                        @elseif(request()->is('user/portfolio/*/edit')) show @endif"
                            id="portfolio">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'user/portfolio-categories') active @endif">
                                    <a
                                        href="{{ route('user.portfolio.category.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Category'] ?? __('Category') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                                @if (request()->path() == 'user/portfolios') active
                                @elseif(request()->is('user/portfolio/*/edit')) active @endif">
                                    <a href="{{ route('user.portfolio.index') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Portfolios'] ?? __('Portfolios') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('Testimonial', $permissions))
                    <li
                        class="nav-item  @if (request()->path() == 'user/testimonials') active
                        @elseif(request()->is('user/testimonial/*/edit')) active @endif">
                        <a href="{{ route('user.testimonials.index') . '?language=' . $default->code }}">
                            <i class="far fa-comment"></i>
                            <p>{{ $keywords['Testimonial'] ?? __('Testimonial') }}</p>
                        </a>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('Blog', $permissions))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/blog-categories') active
                    @elseif(request()->path() == 'user/blogs') active
                    @elseif(request()->is('user/blog/*/edit')) active @endif">
                        <a data-toggle="collapse" href="#blog">
                            <i class="fas fa-blog"></i>
                            <p>{{ $keywords['Blogs'] ?? __('Blogs') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/blog-categories') show
                        @elseif(request()->path() == 'user/blogs') show
                        @elseif(request()->is('user/blog/*/edit')) show @endif"
                            id="blog">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'user/blog-categories') active @endif">
                                    <a href="{{ route('user.blog.category.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Category'] ?? __('Category') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                                @if (request()->path() == 'user/blogs') active
                                @elseif(request()->is('user/blog/*/edit')) active @endif">
                                    <a href="{{ route('user.blog.index') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Blogs'] ?? __('Blogs') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!empty($permissions) && in_array('Appointment', $permissions))
                    <li
                        class="nav-item
                    @if (request()->routeIs('user.appointment.setting')) active
                    @elseif(request()->routeIs('user.appointment.category')) active 
                    @elseif(request()->routeIs('user.timeslot.management')) active 
                    @elseif(request()->routeIs('user.appointment.timeslot')) active 
                    @elseif(request()->routeIs('user.holidays')) active 
                    @elseif(request()->routeIs('user.forminput')) active 
                    @elseif (request()->routeIs('user.form.inputEdit')) active @endif">
                        <a data-toggle="collapse" href="#appointment">
                            <i class="fas fa-tools"></i>
                            <p>{{ $keywords['Appointment_Settings'] ?? __('Appointment Settings') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->routeIs('user.appointment.setting')) show
                        @elseif(request()->routeIs('user.appointment.category')) show 
                        @elseif(request()->routeIs('user.timeslot.management')) show 
                        @elseif(request()->routeIs('user.appointment.timeslot')) show 
                        @elseif(request()->routeIs('user.holidays')) show 
                        @elseif(request()->routeIs('user.forminput')) show 
                        @elseif(request()->routeIs('user.form.inputEdit')) show @endif"
                            id="appointment">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('user.appointment.setting')) active @endif">
                                    <a href="{{ route('user.appointment.setting', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Settings'] ?? __('Settings') }}</span>
                                    </a>
                                </li>
                                @if ($userBs->appointment_category == 1)
                                    <li
                                        class="@if (request()->routeIs('user.appointment.category')) active 
                                @elseif (request()->routeIs('user.form.inputEdit')) active 
                                @elseif (request()->routeIs('user.forminput')) active @endif">
                                        <a
                                            href="{{ route('user.appointment.category') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Categories'] ?? __('Categories') }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="@if (request()->routeIs('user.forminput')) active @endif">
                                        <a href="{{ route('user.forminput') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Form_builder'] ?? __('Form builder') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li
                                    class="@if (request()->routeIs('user.appointment.timeslot')) active 
                                 @elseif (request()->routeIs('user.timeslot.management')) active @endif">
                                    <a
                                        href="{{ route('user.appointment.timeslot', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Time_Slots'] ?? __('Time Slots') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->routeIs('user.holidays')) active 
                                 @elseif (request()->routeIs('user.holidays')) active @endif">
                                    <a href="{{ route('user.holidays', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Holidays'] ?? __('Holidays') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li
                        class="nav-item
                    @if (request()->routeIs('user.bookedAppointment')) active
                    @elseif(request()->routeIs('user.pendingAppointment')) active 
                    @elseif(request()->routeIs('user.approvedAppointment')) active 
                    @elseif(request()->routeIs('user.completedAppointment')) active 
                    @elseif(request()->routeIs('user.appointment.view')) active 
                    @elseif(request()->routeIs('user.appointment.edit')) active 
                    @elseif(request()->routeIs('user.rejectedAppointment')) active @endif">
                        <a data-toggle="collapse" href="#appointments">
                            <i class="fas fa-calendar"></i>
                            <p>{{ $keywords['Appointments'] ?? __('Appointments') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->routeIs('user.bookedAppointment')) show
                        @elseif(request()->routeIs('user.pendingAppointment')) show
                        @elseif(request()->routeIs('user.approvedAppointment')) show
                        @elseif(request()->routeIs('user.appointment.view')) show
                        @elseif(request()->routeIs('user.appointment.edit')) show
                        @elseif(request()->routeIs('user.completedAppointment')) show
                        @elseif(request()->routeIs('user.rejectedAppointment')) show @endif"
                            id="appointments">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('user.bookedAppointment') ||
                                        request()->routeIs('user.appointment.view') ||
                                        request()->routeIs('user.appointment.edit')) active @endif">
                                    <a href="{{ route('user.bookedAppointment', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['ALL'] ?? __('ALL') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('user.pendingAppointment')) active @endif">
                                    <a href="{{ route('user.pendingAppointment', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Pending'] ?? __('Pending') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('user.approvedAppointment')) active @endif">
                                    <a href="{{ route('user.approvedAppointment', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Approved'] ?? __('Approved') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('user.completedAppointment')) active @endif">
                                    <a
                                        href="{{ route('user.completedAppointment', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Completed'] ?? __('Completed') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('user.rejectedAppointment')) active @endif">
                                    <a href="{{ route('user.rejectedAppointment', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Rejected'] ?? __('Rejected') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (!empty($permissions) && in_array('vCard', $permissions))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/vcard') active
                    @elseif(request()->path() == 'user/vcard/create') active
                    @elseif(request()->is('user/vcard/*/edit')) active
                    @elseif(request()->routeIs('user.vcard.services')) active
                    @elseif(request()->routeIs('user.vcard.projects')) active
                    @elseif(request()->routeIs('user.vcard.testimonials')) active
                    @elseif(request()->routeIs('user.vcard.about')) active
                    @elseif(request()->routeIs('user.vcard.preferences')) active
                    @elseif(request()->routeIs('user.vcard.color')) active
                    @elseif(request()->routeIs('user.vcard.keywords')) active @endif">
                        <a data-toggle="collapse" href="#vcard">
                            <i class="far fa-address-card"></i>
                            <p>{{ $keywords['vCards_Management'] ?? __('vCards Management') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/vcard') show
                        @elseif(request()->path() == 'user/vcard/create') show
                        @elseif(request()->is('user/vcard/*/edit')) show
                        @elseif(request()->routeIs('user.vcard.services')) show
                        @elseif(request()->routeIs('user.vcard.projects')) show
                        @elseif(request()->routeIs('user.vcard.testimonials')) show
                        @elseif(request()->routeIs('user.vcard.about')) show
                        @elseif(request()->routeIs('user.vcard.preferences')) show
                        @elseif(request()->routeIs('user.vcard.color')) show
                        @elseif(request()->routeIs('user.vcard.keywords')) show @endif"
                            id="vcard">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->path() == 'user/vcard') active
                            @elseif(request()->is('user/vcard/*/edit')) active 
                            @elseif(request()->routeIs('user.vcard.services')) active
                            @elseif(request()->routeIs('user.vcard.projects')) active
                            @elseif(request()->routeIs('user.vcard.testimonials')) active
                            @elseif(request()->routeIs('user.vcard.about')) active
                            @elseif(request()->routeIs('user.vcard.preferences')) active
                            @elseif(request()->routeIs('user.vcard.color')) active
                            @elseif(request()->routeIs('user.vcard.keywords')) active @endif">
                                    <a href="{{ route('user.vcard', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['vCards'] ?? __('vCards') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'user/vcard/create') active @endif">
                                    <a href="{{ route('user.vcard.create', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Add_vCard'] ?? __('Add vCard') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('Online CV & Export', $permissions))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/cv') active
                    @elseif(request()->routeIs('user.cv.edit')) active
                    @elseif(request()->routeIs('user.cv.info')) active
                    @elseif(request()->routeIs('user.cv.section.index')) active
                    @elseif(request()->routeIs('user.cv.section.edit')) active
                    @elseif(request()->routeIs('user.cv.section.content')) active @endif">
                        <a href="{{ route('user.cv', ['language' => $default->code]) }}">
                            <i class="far fa-file"></i>
                            <p>{{ $keywords['CV_Management'] ?? __('CV Management') }}</p>
                        </a>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('QR Builder', $permissions))
                    <li
                        class="nav-item
                    @if (request()->routeIs('user.qrcode')) active
                    @elseif(request()->routeIs('user.qrcode.index')) active @endif">
                        <a data-toggle="collapse" href="#qrcode">
                            <i class="fas fa-qrcode"></i>
                            <p>{{ $keywords['QR_Codes'] ?? __('QR Codes') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->routeIs('user.qrcode')) show
                        @elseif(request()->routeIs('user.qrcode.index')) show @endif"
                            id="qrcode">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('user.qrcode')) active @endif">
                                    <a href="{{ route('user.qrcode', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Generate_QR_Code'] ?? __('Generate QR Code') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('user.qrcode.index')) active @endif">
                                    <a href="{{ route('user.qrcode.index', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Saved_QR_Codes'] ?? __('Saved QR Codes') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (!empty($permissions) && in_array('Follow/Unfollow', $permissions))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/follower-list') active
                    @elseif(request()->path() == 'user/following-list') active @endif">
                        <a data-toggle="collapse" href="#follow">
                            <i class="fas fa-user-friends"></i>
                            <p>{{ $keywords['Follower_Following'] ?? __('Follower/Following') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/follower-list') show
                        @elseif(request()->path() == 'user/following-list') show @endif"
                            id="follow">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'user/follower-list') active @endif">
                                    <a href="{{ route('user.follower.list', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Followers'] ?? __('Followers') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                                @if (request()->path() == 'user/following-list') active
                                @elseif(request()->is('user/following-list')) active @endif">
                                    <a href="{{ route('user.following.list', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Following'] ?? __('Following') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                <li class="nav-item
                @if (request()->path() == 'user/payment-log') active @endif">
                    <a href="{{ route('user.payment-log.index', ['language' => $default->code]) }}">
                        <i class="fas fa-list-ol"></i>
                        <p>{{ $keywords['Payment_Logs'] ?? __('Payment Logs') }}</p>
                    </a>
                </li>

                @if (!is_null($package))
                    {{-- Language Management Page --}}
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/languages') active
                    @elseif(request()->is('user/language/*/edit')) active
                    @elseif(request()->is('user/language/*/edit/keyword')) active @endif">
                        <a href="{{ route('user.language.index', ['language' => $default->code]) }}">
                            <i class="fas fa-language"></i>
                            <p>{{ $keywords['Language_Management'] ?? __('Language Management') }}</p>
                        </a>
                    </li>
                @endif

                <li
                    class="nav-item
                    @if (request()->path() == 'user/package-list') active
                    @elseif(request()->is('user/package/checkout/*')) active @endif">
                    <a href="{{ route('user.plan.extend.index', ['language' => $default->code]) }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>{{ $keywords['Buy_Plan'] ?? __('Buy Plan') }}</p>
                    </a>
                </li>
                {{-- Start Payment getway --}}
                <li
                    class="nav-item  @if (request()->path() == 'user/gateways') active   @elseif(request()->path() == 'user/offline/gateways') active @endif">
                    <a data-toggle="collapse" href="#gateways">
                        <i class="la flaticon-paypal"></i>
                        <p>{{ $keywords['Payment_Gateways'] ?? __('Payment Gateways') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse  @if (request()->path() == 'user/gateways') show   @elseif(request()->path() == 'user/offline/gateways') show @endif"
                        id="gateways">
                        <ul class="nav nav-collapse">
                            <li class="@if (request()->path() == 'user/gateways') active @endif">
                                <a href="{{ route('user.gateway.index', ['language' => $default->code]) }}">
                                    <span
                                        class="sub-item">{{ $keywords['Online_Gateways'] ?? __('Online Gateways') }}</span>
                                </a>
                            </li>
                            <li class="@if (request()->path() == 'user/offline/gateways') active @endif">
                                <a href="{{ route('user.gateway.offline') . '?language=' . $default->code }}">
                                    <span
                                        class="sub-item">{{ $keywords['Offline_Gateways'] ?? __('Offline Gateways') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- End Payment getway --}}

                @if (!is_null($package))
                    <li class="nav-item
                    @if (request()->path() == 'user/cv-upload') active @endif">
                        <a href="{{ route('user.cv.upload', ['language' => $default->code]) }}">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>{{ $keywords['Upload_CV'] ?? __('Upload CV') }}</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item
                    @if (request()->path() == 'user/change-password') active @endif">
                    <a href="{{ route('user.changePass', ['language' => $default->code]) }}">
                        <i class="fas fa-key"></i>
                        <p>{{ $keywords['Change_Password'] ?? __('Change Password') }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
