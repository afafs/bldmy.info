@extends("user.$folder.layout")
@section('tab-title')
{{ $keywords['Change_Password'] ?? __('Change Password') }}
@endsection
@section('br-title')
{{ $keywords['Change_Password'] ?? __('Change Password') }}
@endsection
@section('br-link')
{{ $keywords['Change_Password'] ?? __('Change Password') }}
@endsection

@section('content')
@if ($userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8)
<!--====== Breadcrumbs Start ======-->
<section class="breadcrumbs-section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-10">
                <div class="page-title">
                    <h1>{{ $keywords['Appointment'] ?? 'Appointment' }}</h1>
                    <ul class="breadcrumbs-link">
                        <li><a href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                        </li>
                        <li class="">{{ $keywords['Appointment'] ?? 'Appointment' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== Breadcrumbs End ======-->
@endif
<!--====== PROFILE PART START ======-->
<section class="dashboard-area">
    <div class="container">
        <div class="row">
            @includeIf('user-front.user.side-navbar')
            <div class="col-lg-9">
                <div class="profile-edit mt-30">
                    <div class="profile-sidebar-title">
                        <h4 class="title"> {{ $keywords['Change_Password'] ?? __('Change Password') }}</h4>
                    </div>
                    <div class="profile-form">
                        <form action="{{ route('customer.update_password', getParam()) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="single-form">
                                        <label>{{ $keywords['Current_Password'] ?? __('Current Password') }}</label>
                                        <input type="password" class="form_control" placeholder="{{ $keywords['Current_Password'] ?? __('Current Password') }}" name="current_password">
                                        @error('current_password')
                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-form">
                                        <label>{{ $keywords['New_Password'] ?? __('New Password') }}</label>
                                        <input type="password" class="form_control" placeholder="{{ $keywords['New_Password'] ?? __('New Password') }}" name="new_password">
                                        @error('new_password')
                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-form">
                                        <label>{{ $keywords['Confirm_New_Password'] ?? __('Confirm New Password') }}</label>
                                        <input type="password" class="form_control" placeholder="{{ $keywords['Confirm_New_Password'] ?? __('Confirm New Password') }}" name="new_password_confirmation">
                                        @error('new_password_confirmation')
                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="single-form mt-2 mb-4">
                                        <button class="@if ($userBs->theme == 1 || $userBs->theme == 2 ) template-btn @else main-btn @endif">{{ $keywords['Submit'] ?? __('Save Change') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== PROFILE PART ENDS ======-->
@endsection
