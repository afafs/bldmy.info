@extends('user-front.common.layout')

@section('pageHeading')
    @if (!empty($currentLanguageInfo->pageHeading))
        {{ $currentLanguageInfo->pageHeading->pricing_title }}
    @endif
@endsection
@section('meta-description', !empty($seo) ? $seo->meta_description_pricing : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_pricing : '')

@section('styles')
@endsection

@section('content')

    <!--====== PAGE BANNER PART START ======-->
    <section class="page-banner"
        style="background: linear-gradient(to right, #{{ $websiteInfo->breadcrumb_color1 }} 0%, #{{ $websiteInfo->breadcrumb_color2 }} 100%);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h4 class="title">{{ $currentLanguageInfo->pageHeading->pricing_title ?? 'Pricing' }}</h4>
                <p>{{ $currentLanguageInfo->pageHeading->pricing_subtitle ?? 'Pricing' }}</p>
            </div>
        </div>
    </section>
    <!--====== PAGE BANNER PART ENDS ======-->

    <!--====== PRICING PART START ======-->

    <section class="pricing-area pt-120 pb-120">


        <div class="container">
            @if (Session::has('warning'))
                <div class="col-lg-12 alert alert-warning">
                    <p>{{ Session::get('warning') }}</p>
                </div>
            @endif
            @if (isset($membershipPackage['membership']) && $membershipPackage['membership']->status == 0)
                <div class="col-lg-12 alert alert-warning">
                    <p>{{ $keywords['You_Have_currently_Pending_member_request'] ?? 'You Have currently a Pending member request' }}
                        <br>
                        <strong>{{ $keywords['Package_name'] ?? 'Package name' }}:</strong>
                        {{ $membershipPackage['package']->title }} <br>
                        <strong>{{ $keywords['Expire_date'] ?? 'Expire_date' }}:</strong>
                        {{ \Carbon\Carbon::parse($membershipPackage['membership']->expire_date)->format('d M Y') }} <br>
                        {{ $keywords['You_can_not_purchase_any_package_untill_that_requst_would_be_approve_or_reject.'] ??'You can not purchase any package untill that requst would be approve or reject.' }}
                    </p>
                </div>
            @endif
            <div class="row">
                @php
                    // $sum = 0;
                    // $tpackages = App\Models\User\UserPackage::where('user_id', $user->id)
                    //     ->where('status', 1)
                    //     ->get();
                    // foreach ($tpackages as $tpackage) {
                    //     $bookedPAckages = App\Models\User\UserMembership::where('status', '<>', '2')
                    //         ->where('package_id', $tpackage->id)
                    //         ->count();
                    //     $tpackageLimit = $tpackage->number_of_ads;
                    //     $bookedAds = $bookedPAckages * $tpackageLimit;
                    //     $sum = $sum + $bookedAds;
                    //     dump($tpackage->id . ' ' . $bookedPAckages . ' ' . $tpackageLimit . ' ' . $bookedAds);
                    // }
                    // $tnntadslimit = App\Http\Helpers\LimitCheckerHelper::adsLimit($user->id);
                    // $availableAds = $tnntadslimit - $sum;
                    
                    //checking
                    $count = App\Http\Helpers\LimitCheckerHelper::currentAdsCount($user->id); //count of current package
                    $tenantAdsLimit = App\Http\Helpers\LimitCheckerHelper::adsLimit($user->id);
                    $tanentpostedAds = App\Http\Helpers\LimitCheckerHelper::tanentPostedAds($user->id);
                    $totalBookedAds = App\Http\Helpers\TanentUserHelper::totalBookedAds($user->id);
                @endphp


                @foreach ($packages as $package)
                    @php
                        $booked = App\Models\User\UserMembership::where('status', '<>', '2')
                            ->where('package_id', $package->id)
                            ->count();
                    @endphp
                    <div class="col-lg-3 col-sm-6">
                        <div class="single-pricing text-center mt-30">
                            <div class="pricing-header">
                                <h4 class="title">{{ $package->title }}</h4>
                                <span class="price"><span>{{ $package->price }}</span>$</span>
                                {{-- <p>*VAT included</p> --}}
                            </div>
                            <div class="pricing-body">
                                <ul class="pricing-list">
                                    <li>{{ $package->number_of_ads === 999999 ? $keywords['unlimited'] ?? 'unlimited' : $package->number_of_ads }}
                                        {{ $keywords['Regular_Ads'] ?? 'Regular Ads' }} </li>
                                    <li>{{ $package->number_of_featured_ads === 999999? $keywords['unlimited'] ?? 'unlimited': $package->number_of_featured_ads }}
                                        {{ $keywords['Featured_Ads'] ?? 'Featured Ads' }} </li>
                                    <li>{{ $package->number_of_days === 999999 ? $keywords['unlimited'] ?? 'unlimited' : $package->number_of_days }}
                                        {{ $keywords['Days'] ?? 'Days' }} </li>
                                    {{-- <li>1 Ad will be bumped up</li> --}}
                                    {{-- <li>Basic Support</li> --}}
                                </ul>

                                {{-- @dump("tenant limit: $tnntadslimit")
                                @dump("Booked Count: $booked")
                                @dump("Package Limit: $package->number_of_ads") --}}
                                {{-- @dump("Available Ads: $availableAds") --}}
                                @if ($tenantAdsLimit <= $totalBookedAds + $tanentpostedAds)
                                    <a href="#"
                                        class="main-btn">{{ $keywords['Out_of_stock'] ?? 'Out of stock' }}</a>
                                @else
                                    @auth('customer')
                                        @if ((isset($membershipPackage['membership']) && $membershipPackage['membership']->status == 1) || empty($membershipPackage['membership']))
                                            <a href="{{ route('front.user.checkout', ['id' => $package->id, getParam()]) }}"
                                                class="main-btn">{{ $keywords['Purchase'] ?? 'Purchase' }}</a>
                                        @endif
                                    @endauth
                                    @guest('customer')
                                        <a href="{{ route('front.user.checkout', ['id' => $package->id, getParam()]) }}"
                                            class="main-btn">{{ $keywords['Purchase'] ?? 'Purchase' }}</a>
                                    @endguest
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--====== PRICING PART ENDS ======-->
@endsection
