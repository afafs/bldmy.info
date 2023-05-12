@extends("user.$folder.layout")
@section('tab-title')
  {{ $keywords['appointment_details'] ?? __('Appointment Details') }}
@endsection
@section('br-title')
  {{ $keywords['appointment_details'] ?? __('Appointment Details') }}
@endsection
@section('br-link')
  {{ $keywords['appointment_details'] ?? __('Appointment Details') }}
@endsection
@section('content')
  @if ($userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8)
    <!--====== Breadcrumbs Start ======-->
    <section class="breadcrumbs-section">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-10">
            <div class="page-title">
              <h1>{{ $keywords['appointment_details'] ?? __('Appointment Details') }}</h1>
              <ul class="breadcrumbs-link">
                <li><a href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'Home' }}</a>
                </li>
                <li class="">{{ $keywords['appointment_details'] ?? __('Appointment Details') }}</li>
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
              <h4 class="title">{{ $keywords['appointment_details'] ?? __('Appointment Details') }}</h4>
            </div>
            <div class="profile-dashboard-wrapper">
              <div class="row">
                <div class="col-md-12 dashboard-col">

                  <div class="col-md-12 ">
                    <div class="card-body px-0">
                      <div class="row">
                        <div class="col-lg-5">
                          <div class="card">
                            <div class="card-header">
                              <div class="col-lg-12 text-center">
                                <div class="card-title m-0 d-inline-block font-weight-bold">
                                  {{ $keywords['Appointment'] ?? __('Appointment') }}
                                </div>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="col-lg-12">
                                <div class="table-responsive ">
                                  <table class="table table-striped table-borderless table-condensed">
                                    <thead>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['SL_No'] ?? __('SL.No') }}
                                        </th>
                                        <td>{{ $appointment->serial_number }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['Booking_Date'] ?? __('Booking Date') }}
                                        </th>
                                        <td>{{ $appointment->date }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['Booking_Day'] ?? __('Booking Day') }}
                                        </th>
                                        <td>
                                          @php
                                            $date = Carbon\Carbon::parse($appointment->date);
                                          @endphp
                                          {{ $date->format('l') }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['Booking_Time'] ?? __('Booking Time') }}
                                        </th>
                                        <td>{{ $appointment->time }}
                                        </td>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <div class="card">
                            <div class="card-header">
                              <div class="col-lg-12 text-center">
                                <div class="card-title m-0 d-inline-block font-weight-bold">
                                  {{ $keywords['Customer_Details'] ?? __('Customer Details') }}
                                </div>
                              </div>
                            </div>

                            <div class="card-body">
                              <div class="col-lg-12">
                                <div class="table-responsive">
                                  <table class="table table-striped table-borderless table-condensed">
                                    <thead>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['Name'] ?? __('Name') }}
                                        </th>
                                        <td>{{ $appointment->name }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['Email'] ?? __('Email') }}
                                        </th>
                                        <td>{{ $appointment->email }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                        
                                          {{ $keywords['Phone'] ?? __('Phone') }}
                                        </th>
                                        <td>{{ $appointment->customer->contact_number ?? '-' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['City'] ?? __('City') }}
                                        </th>
                                        <td>{{ $appointment->customer->city ?? '-' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['State'] ?? __('State') }}
                                        </th>
                                        <td>{{ $appointment->customer->state ?? '-' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th scope="col">
                                          {{ $keywords['Address'] ?? __('Address') }}
                                        </th>
                                        <td>{{ $appointment->customer->address ?? '-' }}
                                        </td>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row mt-4">
                        <div class="col-lg-12">
                          <div class="card">
                            <div class="card-header">
                              <div class="col-lg-12 text-center">
                                <div class="card-title m-0 d-inline-block font-weight-bold">
                                  {{ $keywords['appointment_details'] ?? __('Appointment Details') }}
                                </div>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="col-lg-12">
                                <div class="table-responsive">
                                  @php
                                    $dtls = json_decode($appointment->details);
                                  @endphp
                                  <table class="table table-striped  table-borderless table-condensed">
                                    <thead>
                                      <tr>
                                        <th>{{ $keywords['Category'] ?? __('Category') }}
                                        </th>
                                        <td>{{ $appointment->category->name ?? '-' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th>{{ $keywords['Status'] ?? __('Status') }}
                                        </th>
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
                                      </tr>
                                      <tr>
                                        <th>{{ $keywords['Payment_status'] ?? __('Payment status') }}
                                        </th>
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
                                      </tr>
                                      <tr>
                                        <th>{{ $keywords['Total_Fee'] ?? __('Total Fee') }}
                                        </th>
                                        <td>
                                          {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                          {{ $appointment->total_amount }}
                                          {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <th>{{ $keywords['Paid_Fee'] ?? __('Paid Fee') }}
                                        </th>
                                        <td>
                                          {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                          {{ $appointment->amount }}
                                          {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                          ({{ $keywords['Payment_Method'] ?? __('Payment method') }}
                                          :
                                          {{ $appointment->payment_method }})
                                        </td>
                                      </tr>
                                      @if (!empty($appointment->due_amount))
                                        <tr>
                                          <th>{{ $keywords['Due_amount'] ?? __('Due amount') }}
                                          </th>
                                          <td>
                                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                            {{ $appointment->due_amount }}
                                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}

                                          </td>
                                        </tr>
                                      @endif

                                      @foreach ($dtls as $key => $details)
                                        <tr>
                                          <th scope="col">
                                            {{ str_replace('_', ' ', ucwords($key)) }}
                                          </th>
                                          <td>
                                            @if (!empty($details->type))
                                              @if ($details->type == 5)
                                                <a class="badge badge-primary"
                                                  href="{{ asset('assets/front/files/appointment/' . $details->value) }}"
                                                  download=""><i class="fa fa-download"></i>
                                                  {{ $keywords['Download'] ?? __('Download') }}
                                                  {{ str_replace('_', ' ', $key) }}</a>
                                              @elseif($details->type == 4)
                                                {{ Str::limit($details->value, 80, $end = '.....') }}
                                                @if (strlen($details->value) > 80)
                                                  <i class="fa fa-eye text-info" title="see more" data-toggle="modal"
                                                    data-target="#exampleModal"></i>
                                                  <!-- Modal -->
                                                  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLabel">
                                                            {{ str_replace('_', ' ', $key) }}
                                                          </h5>
                                                          <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body">
                                                          {{ $details->value }}
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-sm btn-secondary"
                                                            data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                @endif
                                              @elseif($details->type == 3)
                                                @foreach ($details->value as $v)
                                                  {{ ucwords($v) }}
                                                  @if (!$loop->last)
                                                    ,
                                                  @endif
                                                @endforeach
                                              @else
                                                {{ $details->value }}
                                              @endif
                                            @endif
                                          </td>
                                        </tr>
                                      @endforeach
                                    </thead>
                                  </table>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== PROFILE PART ENDS ======-->
@endsection
