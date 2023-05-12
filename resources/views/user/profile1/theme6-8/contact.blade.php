@extends('user.profile1.' . $layout . '.layout')

@section('tab-title')
    {{ $keywords['Contact'] ?? 'Contact' }}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->blogs_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->blogs_meta_keywords : '')

@section('content')
    <!--====== Breadcrumbs Start ======-->
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10">
                    <div class="page-title">
                        <h1>{{ $keywords['Contact'] ?? 'Contact' }}</h1>
                        <ul class="breadcrumbs-link">
                            <li><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                            </li>
                            <li class="">{{ $keywords['Contact'] ?? 'Contact' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== Breadcrumbs End ======-->

    <!--====== Start Main Wrapper ======-->
    <section class="page-content-section section-gap contact-area pt-150 pb-160">
        <div class="container">
            <div class="contact-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <form class="contact-form" action="{{ route('front.contact.message', [getParam()]) }}"
                            enctype="multipart/form-data" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div class="form_group mb-20">
                                        <label>{{ $keywords['Name'] ?? 'Name' }}</label>
                                        <input type="text" class="form_control" name="fullname"
                                            placeholder="{{ $keywords['Enter_Name'] ?? 'Enter Name' }}" required>
                                        @if ($errors->has('fullname'))
                                            <p class="text-danger mb-0">{{ $errors->first('fullname') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div class="form_group mb-20">
                                        <label>{{ $keywords['Email_Address'] ?? 'Email Address' }}</label>
                                        <input type="text" class="form_control" name="email"
                                            placeholder="{{ $keywords['Enter_Email_Address'] ?? 'Enter Email Address' }}"
                                            required>
                                        @if ($errors->has('email'))
                                            <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div class="form_group mb-20">
                                        <label>{{ $keywords['Subject'] ?? 'Subject' }}</label>
                                        <input type="text" class="form_control" name="subject"
                                            placeholder="{{ $keywords['Enter_Subject'] ?? 'Enter Subject' }}" required>
                                        @if ($errors->has('subject'))
                                            <p class="text-danger mb-0">{{ $errors->first('subject') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form_group mb-20">
                                        <label>{{ $keywords['Message'] ?? 'Message' }}</label>
                                        <textarea class="form_control" name="message" placeholder="{{ $keywords['Enter_Message'] ?? 'Enter Message' }}"></textarea>
                                        @if ($errors->has('message'))
                                            <p class="text-danger mb-0">{{ $errors->first('message') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 pt-20">
                                    <div class="button text-center">
                                        <button class="main-btn">{{ $keywords['Send_Message'] ?? 'Send Message' }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
