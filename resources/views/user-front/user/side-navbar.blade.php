<div class="col-lg-3">
    <div class="profile-sidebar">
        <div class="profile-card mt-30">
            <div class="profile-sidebar-title">
                <h4 class="title">{{ $keywords['My_Profile'] ?? __('My Profile') }}</h4>
            </div>
            <div class="profile-user text-center">
                <div class="profile-author">
                    <img class="lazy"
                        data-src="{{ $authUser->image ? asset('assets/user/img/users/' . $authUser->image) : asset('assets/front/img/user/blank_propic.png') }}"
                        alt="customer-photo">
                </div>
                <div class="profile-author-name">
                    <h4 class="name"><a
                            href="{{ route('customer.dashboard', getParam()) }}">{{ $authUser->username }}</a></h4>
                    <span class="sub-title">{{ $authUser->address }}</span>
                </div>
            </div>
            <div class="profile-link">
                <ul>
                    <li><a @if (request()->routeIs('customer.dashboard')) class="active" @endif
                            href="{{ route('customer.dashboard', getParam()) }}"><i class="fal fa-tachometer-alt"></i>
                            {{ $keywords['Dashboard'] ?? __('Dashboard') }}</a></li>
                    <li><a @if (request()->routeIs('customer.appointments') || request()->routeIs('customer.appointments.details')) class="active" @endif
                            href="{{ route('customer.appointments', getParam()) }}"><i class="fas fa-file"></i>
                            {{ $keywords['My_Appointments'] ?? __('My Appointments') }}</a></li>
                    <li><a @if (request()->routeIs('customer.edit_profile')) class="active" @endif
                            href="{{ route('customer.edit_profile', getParam()) }}"><i class="fal fa-user"></i>
                            {{ $keywords['Profile'] ?? __('Profile') }}</a></li>
                    <li><a @if (request()->routeIs('customer.change_password')) class="active" @endif
                            href="{{ route('customer.change_password', getParam()) }}"><i
                                class="fal fa-unlock-alt"></i>
                            {{ $keywords['Change_Password'] ?? __('Change Password') }}</a>
                    </li>
                    <li><a href="{{ route('customer.logout', getParam()) }}"><i class="fal fa-sign-out"></i>
                            {{ $keywords['Signout'] ?? __('Sign Out') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
