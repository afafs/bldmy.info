@extends('user.layout')
@php
$selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id]])->first();
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id], ['is_default', 1]])->first();

$userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
@endphp
@section('content')
    <div class="page-header">
        <h4 class="page-title">
            {{ $keywords['Appointment_Settings'] ?? __('Appointment Settings') }}
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('user-dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.appointment.timeslot', ['language' => request('language')]) }}"> {{ $keywords['Time_Slot_Management'] ?? __('Time Slot Management') }}</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">
                        {{ $keywords['Days'] ?? __('Days') }}
                    </a>
                </li>
            </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h3> {{ $keywords['Time_Slot_Management'] ?? __('Time Slot Management') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped mt-3">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ $keywords['Day'] ?? __('Day') }}</th>
                                            <th scope="col">{{ $keywords['Time_slots'] ?? __('Time slots') }} </th>
                                            <th scope="col">{{ $keywords['Weekend'] ?? __('Weekend') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($days as $day)
                                            <tr>
                                                <td>{{ $day->day }}</td>
                                                <td>
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('user.timeslot.management', ['day' => $day->day, 'language' => request('language')]) }}">{{ $keywords['Manage'] ?? __('Manage') }}</a>
                                                </td>
                                                <td>
                                                    <form id="daysWeekend{{ $day->id }}" class="d-inline-block"
                                                        action="{{ route('user.makeWeekend') }}" method="post">
                                                        @csrf
                                                        <select
                                                            class="form-control form-control-sm {{ $day->weekend == 1 ? 'bg-success' : 'bg-danger' }}"
                                                            name="status"
                                                            onchange="document.getElementById('daysWeekend{{ $day->id }}').submit();">
                                                            <option value="0"
                                                                {{ $day->weekend == 0 ? 'selected' : '' }}>
                                                                {{ $keywords['No'] ?? __('No') }}</option>
                                                            <option value="1"
                                                                {{ $day->weekend == 1 ? 'selected' : '' }}>
                                                                {{ $keywords['Yes'] ?? __('Yes') }}</option>
                                                        </select>
                                                        <input type="hidden" name="day_id" value="{{ $day->id }}">
                                                    </form>
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
    </div>
@endsection
