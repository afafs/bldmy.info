@extends("user.$folder.layout")
@section('tab-title')
{{ $keywords['Login'] ?? 'Login' }}
@endsection
@section('br-title')
{{ $keywords['Login'] ?? 'Login' }}
@endsection
@section('br-link')
{{ $keywords['Login'] ?? 'Login' }}
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

<!--====== SING IN PART START ======-->
<section class="dashboard-area sign-in-area mt-30">
    <div class="container">
        @if ($userBs->guest_checkout && !empty(request('redirected')))
        <div class="single-form text-center p-5">
            <a type="submit" href="{{ route('front.user.appointment', [getParam(), 'type' => 'guest']) }}" class=" @if ($userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8) main-btn @else template-btn main-btn arrow-btn @endif">{{ $keywords['Book_as_guest'] ?? __('Book as guest') }}</a>
        </div>
        <p class="text-center">{{ $keywords['OR'] ?? __('OR') }}</p>
        @endif
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="sing-in-form-area wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                    <div class="sing-in-form-wrapper">
                        <form action="{{ route('customer.login_submit', getParam()) }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="single-form mb-20">
                                <label>{{ $keywords['Email'] ?? __('Email') }} *</label>
                                <input type="email" placeholder="{{ $keywords['Enter_email_address'] ?? __('Enter email address') }}" class="form_control" name="email" value="{{ old('email') }}">
                                @error('email')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div> <!-- single-form -->
                            <div class="single-form mb-20">
                                <label>{{ $keywords['Password'] ?? __('Password') }} * </label>
                                <input type="password" class="form_control" name="password" value="{{ old('password') }}" placeholder="{{ $keywords['Enter_password'] ?? __('Enter password') }}">
                                @error('password')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div> <!-- single-form -->
                            <div class="single-form mt-35 d-sm-flex justify-content-between">
                                <div class="form-checkbox mt-10">
                                </div>
                                <div class="form-forget mt-10">
                                    <a href="{{ route('customer.forget_password', getParam()) }}" class="link">{{ $keywords['Lost_your_password'] ?? __('Lost your password') }}
                                        ?</a>
                                </div>
                            </div>
                            <!-- single-form -->
                            <div class="single-form ">
                                <button type="submit" class="@if ($userBs->theme == 1 || $userBs->theme == 2 ) template-btn @else  main-btn @endif">{{ $keywords['Login'] ?? __('Login') }}</button>
                            </div>
                            <!-- single-form -->
                        </form>
                        <div class="new-user text-center mt-5">
                            <p class="text">{{ $keywords['New_user'] ?? 'New user' }}?
                                <a href="{{ route('customer.signup', getParam()) }}">{{ $keywords['Donot_have_an_account'] ?? "Don't have an account" }}?</a>
                            </p>
                        </div>
                    </div> <!-- sing in form wrapper -->
                </div> <!-- sing in form areasing-in-form-area -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
</section>
<!--====== SING IN PART ENDS ======-->
@endsection
