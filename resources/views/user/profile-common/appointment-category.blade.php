@extends("user.$folder.layout")

@section('tab-title')
    {{ $keywords['Appointment'] ?? 'Appointment' }}
@endsection

@section('meta-description', !empty($userSeo) ? $userSeo->services_meta_description : '')
@section('meta-keywords', !empty($userSeo) ? $userSeo->services_meta_keywords : '')

@section('br-title')
    {{ $keywords['Appointment'] ?? 'Appointment' }}
@endsection
@section('br-link')
    {{ $keywords['Appointment'] ?? 'Appointment' }}
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
                                <li><a
                                        href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
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
    <!--====== Start Vaughn-Features section ======-->
    <section class="pt-5 mt-5 mb-5 @if ($userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8) page-content-section @endif vaughn-features"
        id="service">
        <div class="container">
            @if (count($categories))
                <div class="row justify-content-center">
                    @foreach ($categories as $category)
                        <div class="col-lg-3 col-md-6">
                            <a class="features-img mb-1 d-block"
                                href="{{ route('front.user.appointment.form', ['cat' => $category->id, getParam()]) }}">
                                <div class="features-box border bg-white p-3 mb-50 text-center">
                                    <img data-src="{{ asset('assets/user/img/category') . '/' . $category->image }}"
                                        class="img-fluid lazy" alt="img">
                                    <div>
                                        ({{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $category->appointment_price }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }})
                                    </div>
                                    <h5>{{ $category->name }} </h5>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <!--====== End Vaughn-Features section ======-->
@endsection
