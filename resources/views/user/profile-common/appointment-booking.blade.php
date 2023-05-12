@extends("user.$folder.layout")
@section('styles')

@endsection
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
    <section class=" @if ($userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8) page-content-section section-gap @endif vaughn-features pt-5"
        id="service">
        <div class="container mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <h4>{{ $keywords['Select_a_date'] ?? __('Select a date') }}</h4>
                    <form action="{{ route('customer.checkout', getParam()) }}" method="post">
                        @csrf
                        <input type="hidden" name="slotId" value="">
                        <input type="hidden" name="slot" value="">
                        <input type="hidden" name="date" value="{{ $today }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="calendar-container mt-4"></div>
                            </div>
                        </div>
                        <div class="box" style=""></div>
                        <h4 class="mt-5">{{ $keywords['Select_a_slot'] ?? __('Select a slot') }}</h4>
                        <div class="row timeslot-box pt-5">
                            @if (count($timeSlots) > 0)
                                @foreach ($timeSlots as $timeslot)
                                    <span class="single-timeslot mr-2 mb-2  p-2 rounded" dir="ltr" data-id="{{ $timeslot->id }}"
                                        data-slot="{{ $timeslot->start }} - {{ $timeslot->end }}">{{ $timeslot->start }}
                                        - {{ $timeslot->end }}</span>
                                @endforeach
                            @else
                                <div class="alert alert-warning col-md-12 text-center">
                                    <p>{{ $keywords['choose_a_date_to_see_slots'] ?? __('Please choose a date to see the available slots') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="request-loader">
                            <img class="frontend-loader" src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
                        </div>
                        <div class="form-group mt-5">
                            <button class="@if (
                                $userBs->theme == 3 ||
                                    $userBs->theme == 4 ||
                                    $userBs->theme == 5 ||
                                    $userBs->theme == 6 ||
                                    $userBs->theme == 7 ||
                                    $userBs->theme == 8) main-btn @else template-btn @endif"
                                type="submit">{{ $keywords['Next'] ?? 'Next' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Vaughn-Features section ======-->
@endsection


