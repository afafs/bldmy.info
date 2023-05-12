@extends('user-front.common.layout')

@section('pageHeading')
    Profile || {{ $author->username }}
@endsection
@section('meta-description', !empty($seo) ? $seo->meta_description_about : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_about : '')

@section('styles')
@endsection

@section('content')

    <!--====== PAGE BANNER PART START ======-->
    <section class="page-banner"  style="background: linear-gradient(to right, #{{$websiteInfo->breadcrumb_color1}} 0%, #{{$websiteInfo->breadcrumb_color2}} 100%);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h4 class="title">{{$keywords['Profile_of'] ?? 'Profile of'}} {{ $author->username }} </h4>
            </div>
        </div>
    </section>
    <!--====== PAGE BANNER PART ENDS ======-->
    <!--====== PROFILE PART START ======-->
    <section class="profile-area pt-70 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="profile-sidebar pt-20">
                        <div class="profile-card mt-30">
                            <div class="profile-sidebar-title">
                                <h4 class="title">{{ $author->username }} {{$keywords['s_profile'] ?? '\'s Profile'}}</h4>
                            </div>
                            <div class="profile-user text-center">
                                <div class="profile-author">

                                    <img class="lazy" data-src="{{ $author->image ? asset('assets/user/img/users/' . $author->image) : asset('assets/user/img/profile.jpg') }}"
                                        alt="">
                                </div>
                                <div class="profile-author-name">
                                    <h4 class="name"><a href="#">{{ $author->first_name }}
                                            {{ $author->last_name }}</a></h4>
                                    <span class="sub-title">{{ $author->city }} {{ $author->country }}</span>
                                </div>
                            </div>
                            <div class="profile-link">
                                <ul class="p-3">
                                    <li>{{$keywords['Contact_number'] ?? 'Contact number'}}: {{ $author->contact_number }}</li>
                                    <li>{{$keywords['Address'] ?? 'Address'}}: {{ $author->address }}</li>
                                    <li>{{$keywords['City'] ?? 'City'}}: {{ $author->city }}</li>
                                    <li>{{$keywords['State'] ?? 'State'}}: {{ $author->state }}</li>
                                    <li>{{$keywords['Country'] ?? 'Country'}}: {{ $author->country }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="profile-edit mt-50">
                        <div class="profile-sidebar-title">
                            <h4 class="title">{{ $author->username }} {{$keywords['s_ads'] ?? '\'s Ads'}}</h4>
                        </div>
                        <div class="profile-dashboard-wrapper">
                            <div class="profile-ads-table table-responsive mt-30">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="checkbox">
                                                <div class="check-box">
                                                    <input type="checkbox" name="checkbox1" id="checkbox1">
                                                    <label for="checkbox1"></label>
                                                </div>
                                            </th>
                                            <th class="items">{{ $keywords['Items'] ?? 'Items' }}</th>
                                            <th class="category">{{ $keywords['Category'] ?? 'Category' }}</th>
                                            <th class="price">{{ $keywords['Price'] ?? 'Price' }}</th>
                                            <th class="status">{{ $keywords['Status'] ?? 'Status' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ads as $post)
                                            <tr>
                                                <td class="checkbox">
                                                    <div class="check-box">
                                                        <input type="checkbox" name="checkbox"
                                                            id="checkbox{{ $post->id }}">
                                                        <label for="checkbox{{ $post->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="items">
                                                    <div class="table-items d-flex">
                                                        <div class="items-thumb">
                                                            <img class="lazy" data-src="{{ asset('assets/user/img/ads/' . $post->thumbnail_image) }}"
                                                                alt="ads">
                                                        </div>
                                                        <div class="items-content">
                                                            <h5 class="title"><a
                                                                    href="{{ route('front.user.ad_details', ['slug' => $post->slug, getParam()]) }}">{!! strlen($post->title) > 45 ? mb_substr($post->title, 0, 45, 'UTF-8') . '...' : $post->title !!}</a>
                                                            </h5>
                                                            <ul class="review">
                                                                <li><i class="fal fa-star"></i></li>
                                                                <li><i class="fal fa-star"></i></li>
                                                                <li><i class="fal fa-star"></i></li>
                                                                <li><i class="fal fa-star"></i></li>
                                                                <li><i class="fal fa-star"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="category">
                                                    <p class="table-category"> {{ $post->category }}</p>
                                                </td>
                                                <td class="price">
                                                    <p class="table-price"> {{ $post->price }}</p>
                                                </td>
                                                <td class="status">
                                                    <p
                                                        class="table-status {{ $post->status == 1 ? 'active' : ($post->status == 2 ? 'expired' : 'published') }} ">
                                                        {{ $post->status == 1 ? 'active' : ($post->status == 2 ? 'Rejected' : 'Pending') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== PROFILE PART ENDS ======-->


@endsection
