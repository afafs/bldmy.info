@extends('user-front.common.layout')

@section('pageHeading')
    {{ $currentLanguageInfo->pageHeading->contact_title ?? 'Contact Us' }}
@endsection
@section('meta-description', !empty($seo) ? $seo->meta_description_contact : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_contact : '')

@section('styles')
@endsection

@section('content')

    <!--====== PAGE BANNER PART START ======-->

    <section class="page-banner"
        style="background: linear-gradient(to right, #{{ $websiteInfo->breadcrumb_color1 }} 0%, #{{ $websiteInfo->breadcrumb_color2 }} 100%);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h4 class="title">{{ $currentLanguageInfo->pageHeading->contact_title ?? 'Contact Us' }}</h4>
                <p>{{ $currentLanguageInfo->pageHeading->blog_details_title ?? 'Contact Us' }}</p>
            </div>
        </div>
    </section>

    <!--====== PAGE BANNER PART ENDS ======-->
    <!--====== CONTACT PART START ======-->

    <div class="contact-map-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if (!empty($contact->latitude) && !empty($contact->longitude))
                        <div class="container-fluid container-1600 mt-120 mb-40">
                            <div class="contact-map">
                                <iframe
                                    src="//www.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $contact->latitude ?? 36.7783 }},%20{{ $contact->longitude ?? 119.4179 }}+(My%20Business%20Name)&amp;t=&amp;z={{ $contact->map_zoom ?? 12 }}&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                                    class="border-0" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                            </div>
                        </div>
                    @endif
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </div>

    <!--====== CONTACT PART ENDS ======-->

    <!--====== CONTACT FORM PART START ======-->

    <section class="contact-area pt-30 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="contact-form mt-45">
                        <h4 class="contact-title">{{ $contact->form_title }}</h4>
                        <form action="{{ route('front.user.contact', getParam()) }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="single-form form-group">
                                        <label>Name :</label>
                                        <input type="text" name="fullname" value="{{ old('fullname') }}"
                                            placeholder="{{ $keywords['Name'] ?? 'Name' }}" required="required">
                                        <div class="help-block with-errors"></div>
                                        @error('fullname')
                                            <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-form form-group">
                                        <label>Email Address :</label>
                                        <input type="email"
                                            placeholder="{{ $keywords['Email_Address'] ?? 'Email Address' }}"
                                            name="email" value="{{ old('email') }}" required="required">
                                        <div class="help-block with-errors"></div>
                                        @error('email')
                                            <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="single-form form-group">
                                        <label>Subject :</label>
                                        <input type="text" placeholder="{{ $keywords['Subject'] ?? 'Subject' }}"
                                            name="subject" value="{{ old('subject') }}" required="required">
                                        <div class="help-block with-errors"></div>
                                        @error('subject')
                                            <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="single-form form-group">
                                        <label>Write a Message :</label>
                                        <textarea placeholder="{{ $keywords['Message'] ?? 'Message' }}" name="message"
                                            required>{{ old('message') }}</textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <p class="form-message"></p>
                                <div class="col-md-12">
                                    <div class="single-form form-group">
                                        <button class="main-btn"
                                            type="submit">{{ $keywords['Send_Message'] ?? 'Send Message' }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="contact-info pt-45">
                        <h4 class="contact-title">{{ $contact->contact_title }}</h4>
                        <p class="pt-30">{{ $contact->contact_text }}</p>
                        <ul class="pt-15">
                            @if ($contact->addresses)
                                <li>
                                    <div class="single-info d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fal fa-map-marker-alt"></i>
                                        </div>
                                        <div class="content pl-15">
                                            <p>{!! nl2br($contact->addresses) !!}</p>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if ($contact->mails)
                                <li>
                                    <div class="single-info d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fal fa-envelope-open-text"></i>
                                        </div>
                                        <div class="content pl-15">
                                            <p>{{ $contact->mails }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if ($contact->numbers)
                                <li>
                                    <div class="single-info d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fal fa-phone"></i>
                                        </div>
                                        <div class="content pl-15">
                                            <p>{{ $contact->numbers }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>

    <!--====== CONTACT FORM PART ENDS ======-->


@endsection
