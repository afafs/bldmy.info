@extends("user.$folder.layout")
@section('tab-title')
    {{ $keywords['reset_password'] ?? 'Reset Password' }}
@endsection
@section('br-title')
    {{ $keywords['reset_password'] ?? 'Reset Password' }}
@endsection
@section('br-link')
    {{ $keywords['reset_password'] ?? 'Reset Password' }}
@endsection
@section('content')
    @if ($userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8)
        <!--====== Breadcrumbs Start ======-->
        <section class="breadcrumbs-section">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-10">
                        <div class="page-title">
                            <h1>{{ $keywords['reset_password'] ?? 'Reset Password' }}</h1>
                            <ul class="breadcrumbs-link">
                                <li><a
                                        href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                                </li>
                                <li class="">{{ $keywords['reset_password'] ?? 'Reset Password' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Breadcrumbs End ======-->
    @endif

    <!--======FORGET PASSWORD PART START ======-->
    <section class="dashboard-area sign-in-area mt-30">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="sing-in-form-area  wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                        <div class="sing-in-form-wrapper">
                            <form action="{{ route('customer.reset_password_submit', getParam()) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="single-form ">
                                    <label>{{ $keywords['New_Password'] ?? __('New Password') }}*</label>
                                    <input type="password"
                                        placeholder="{{ $keywords['New_Password'] ?? __('New Password') }}"
                                        class="form_control" name="new_password">
                                    @error('new_password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div> <!-- single-form -->
                                <div class="single-form ">
                                    <label>{{ $keywords['Confirm_New_Password'] ?? __('Confirm New Password') }}*</label>
                                    <input type="password"
                                        placeholder="{{ $keywords['Confirm_New_Password'] ?? __('Confirm New Password') }}"
                                        class="form_control" name="new_password_confirmation">
                                    @error('new_password_confirmation')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div> <!-- single-form -->
                                <div class="single-form mt-5">
                                    <button type="submit"
                                        class="@if ($userBs->theme == 1 || $userBs->theme == 2 ) template-btn @else main-btn @endif">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                                </div> <!-- single-form -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== FORGET PASSWORD PART ENDS ======-->
@endsection
