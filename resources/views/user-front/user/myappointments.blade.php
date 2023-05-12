@extends("user.$folder.layout")
@section('tab-title')
    {{ $keywords['My_Appointments'] ?? 'My Appointments' }}
@endsection
@section('br-title')
    {{ $keywords['My_Appointments'] ?? 'My Appointments' }}
@endsection
@section('br-link')
    {{ $keywords['My_Appointments'] ?? 'My Appointments' }}
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
    <!--====== PROFILE PART START ======-->
    <section class="dashboard-area">
        <div class="container">
            <div class="row">
                @includeIf('user-front.user.side-navbar')
                <div class="col-lg-9">
                    <div class="profile-dashboard mt-30">
                        <div class="profile-sidebar-title">
                            <h4 class="title">{{ $keywords['My_Appointments'] ?? 'My Appointments' }}</h4>
                        </div>
                        <div class="profile-dashboard-wrapper">
                            <div class="row">
                                <div class="col-md-12 dashboard-col">
                                    <div class="user-profile-details">
                                        <div class="account-info mb-3">
                                            <div class="main-info">
                                                @if (count($appointments) == 0)
                                                    <h3 class="text-center">
                                                        {{ $keywords['No_Appointment_Found'] ?? __('No Appointment Found') }}
                                                        !</h3>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table table-striped mt-3" id="basic-datatables">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">
                                                                        {{ $keywords['SL_No'] ?? __('SL. No') }} </th>
                                                                    <th scope="col">
                                                                        {{ $keywords['Booking_Date'] ?? __('Booking date') }}
                                                                    </th>
                                                                    <th scope="col">
                                                                        {{ $keywords['Time_Slots'] ?? __('Time slots') }}
                                                                    </th>

                                                                    <th scope="col">
                                                                        {{ $keywords['Status'] ?? __('Status') }}
                                                                    </th>
                                                                    <th scope="col">
                                                                        {{ $keywords['Payment_status'] ?? __('Payment status') }}
                                                                    </th>
                                                                    <th scope="col">
                                                                        {{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($appointments as $appointment)
                                                                    <tr>
                                                                        <td>{{ $appointment->serial_number }}</td>
                                                                        <td>{{ $appointment->date }}</td>
                                                                        <td>{{ $appointment->time }}</td>
                                                                        <td>
                                                                            @if ($appointment->status == 1)
                                                                                <span
                                                                                    class="badge badge-info">{{ $keywords['Pending'] ?? __('Pending') }}</span>
                                                                            @elseif($appointment->status == 2)
                                                                                <span
                                                                                    class="badge badge-success">{{ $keywords['Approved'] ?? __('Approved') }}</span>
                                                                            @elseif($appointment->status == 3)
                                                                                <span
                                                                                    class="badge badge-primary">{{ $keywords['Completed'] ?? __('Completed') }}</span>
                                                                            @elseif($appointment->status == 4)
                                                                                <span
                                                                                    class="badge badge-danger">{{ $keywords['Rejected'] ?? __('Rejected') }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($appointment->payment_status == 1)
                                                                                <span
                                                                                    class="badge badge-info">{{ $keywords['Pending'] ?? __('Pending') }}</span>
                                                                            @elseif($appointment->payment_status == 2)
                                                                                <span
                                                                                    class="badge badge-success">{{ $keywords['Paid'] ?? __('Paid') }}</span>
                                                                            @elseif($appointment->payment_status == 3)
                                                                                <span
                                                                                    class="badge badge-primary">{{ $keywords['Advanced'] ?? __('Advanced') }}</span>
                                                                            @elseif($appointment->payment_status == 4)
                                                                                <span
                                                                                    class="badge badge-danger">{{ $keywords['Rejected'] ?? __('Rejected') }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('customer.appointments.details', [getParam(), 'appointment' => $appointment->id]) }}"
                                                                                class="btn btn-info btn-sm "> <span
                                                                                    class="btn-label"> <i
                                                                                        class="fas fa-eye"></i> </span>
                                                                                {{ $keywords['Details'] ?? __('Details') }}
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== PROFILE PART ENDS ======-->
@endsection
