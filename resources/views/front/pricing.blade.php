@extends('front.layout')

@section('pagename')
    - {{ __('Pricing') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->pricing_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->pricing_meta_keywords : '')

@section('breadcrumb-title')
    {{ __('Pricing') }}
@endsection
@section('breadcrumb-link')
    {{ __('Pricing') }}
@endsection

@section('content')

    <!--====== Start saas-pricing section ======-->
    <section class="saas-pricing pricing-page pt-110 pb-120">
        <div class="container">

            @if (count($terms) > 1)
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="pricing-tabs text-center">
                            <ul class="nav nav-tabs">
                                @foreach ($terms as $term)
                                    <li class="nav-item mr-1">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab"
                                            href="#{{ strtolower($term) }}">{{ __("$term") }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="pricing-wrapper tab-content">
                @foreach ($terms as $term)
                    <div id="{{ strtolower($term) }}" class="tab-pane {{ $loop->first ? 'show active' : '' }} fade">
                        <div class="row no-gutters ">
                            @php
                                $packages = \App\Models\Package::where('status', '1')
                                    ->where('term', strtolower($term))
                                    ->get();
                            @endphp
                            @foreach ($packages as $package)
                                @php
                                    $pFeatures = json_decode($package->features);
                                @endphp
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="pricing-item">
                                        <div class="title">
                                            <h3>{{ $package->title }}</h3>
                                            <h2 class="price">
                                                {{ $package->price != 0 && $be->base_currency_symbol_position == 'left' ? $be->base_currency_symbol : '' }}{{ $package->price == 0 ? 'Free' : $package->price }}{{ $package->price != 0 && $be->base_currency_symbol_position == 'right' ? $be->base_currency_symbol : '' }}
                                                <span class="month">/ {{ __("$package->term") }}</span>
                                            </h2>
                                        </div>
                                        <div class="pricing-body">
                                            <ul class="list">
                                                @foreach ($allPfeatures as $feature)
                                                    <li
                                                        class="{{ is_array($pFeatures) && in_array($feature, $pFeatures) ? 'checked' : 'unchecked' }}">
                                                        @if ($feature == 'vCard' && is_array($pFeatures) && in_array($feature, $pFeatures))
                                                            @if ($package->number_of_vcards == 999999)
                                                                {{ __('Unlimited') }} {{ __('vCards') }}
                                                            @elseif(empty($package->number_of_vcards))
                                                                0 {{ __('vCard') }}
                                                            @else
                                                                {{ $package->number_of_vcards }}
                                                                {{ $package->number_of_vcards > 1 ? __('vCards') : __('vCard') }}
                                                            @endif
                                                            @continue
                                                        @elseif($feature == 'vCard' && (is_array($pFeatures) && !in_array($feature, $pFeatures)))
                                                            {{ __('vCards') }}
                                                            @continue
                                                        @endif
                                                        {{ __("$feature") }}
                                                        @if ($feature == 'Plugins')
                                                            ({{ __('Google Analytics, Disqus, WhatsApp, Facebook Pixel, Tawk.to') }})
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="pricing-button">
                                            @if ($package->is_trial === '1' && $package->price != 0)
                                                <a href="{{ route('front.register.view', ['status' => 'trial', 'id' => $package->id]) }}"
                                                    class="main-btn">{{ __('Trial') }}</a>
                                            @endif
                                            @if ($package->price == 0)
                                                <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $package->id]) }}"
                                                    class="main-btn">{{ __('Signup') }}</a>
                                            @else
                                                <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $package->id]) }}"
                                                    class="main-btn">{{ __('Purchase') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </section>
    <!--====== End saas-pricing section ======-->
@endsection
