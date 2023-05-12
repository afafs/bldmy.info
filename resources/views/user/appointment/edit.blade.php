@extends('user.layout')
@php
  $selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id]])->first();
  $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id], ['is_default', 1]])->first();
  
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/front/css/pignose.calendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custompignos.css') }}">
    <style>
      form:not(.modal-form) input,
      form:not(.modal-form) textarea,
      form:not(.modal-form) select,
      select[name='userLanguage'] {
        direction: rtl;
      }

      form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
      }
    </style>

    <style>
      .single-timeslot {
        background: #FFAD46;
        color: #ffffff;
        cursor: pointer;
      }

      .timeslot-box .active {
        background: #ccc;
      }
    </style>
  @endsection
@endif
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/front/css/pignose.calendar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/custompignos.css') }}">


  <style>
    .single-timeslot {
      background: #FFAD46;
      color: #ffffff;
      cursor: pointer;
    }

    .timeslot-box .active {
      background: #ccc;
    }
  </style>
@endsection
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ $keywords['appointment_details'] ?? __('Appointment Details') }}</h4>
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
        <a
          href="{{ route('user.bookedAppointment', ['language' => request('language')]) }}">{{ $keywords['Appointments'] ?? __('Appointments') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['Edit'] ?? __('Edit') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12 card">
      <div class="card-header">
        <div class="row">
          <div class="col-lg-4">
            <div class="card-title d-inline-block">
              {{ $keywords['appointment_details'] ?? __('Appointment Details') }}
              {{ $keywords['Edit'] ?? __('Edit') }}</div>
          </div>
          <div class="col-lg-3">
          </div>
          <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0 text-right">
            <a class="btn btn-sm btn-primary"
              href="{{ route('user.bookedAppointment', ['language' => request('language')]) }}"> <i
                class="fa fa-backword"></i> {{ $keywords['Back'] ?? __('Back') }}</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="col-lg-12 text-center">
                  <div class="card-title d-inline-block">
                    {{ $keywords['appointment_details'] ?? __('Appointment Details') }}</div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <form id="ajaxForm" action="{{ route('user.appointment.update', $appointment->id) }}" method="post"
                      enctype="multipart/form-data">
                      <div class="row">

                        @if (!empty($appointment->category_id))
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label>{{ $keywords['Category'] ?? __('Category') }}</label>
                              <select
                                onchange="document.getElementById('total_amount').value=this.options[this.selectedIndex].getAttribute('price');document.getElementById('amount').value=this.options[this.selectedIndex].getAttribute('price');document.getElementById('due_amount').value=''"
                                name="category_id" class="form-control">
                                @foreach ($categories as $cat)
                                  <option price="{{ $cat->appointment_price }}"
                                    {{ $cat->id == $appointment->category_id ? 'selected' : '' }}
                                    value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        @endif
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Status'] ?? __('Status') }}</label>
                            <select
                              class="form-control form-control-sm {{ $appointment->status == 2 ? 'bg-success' : ($appointment->status == 3 ? 'bg-primary' : ($appointment->status == 4 ? 'bg-danger' : 'bg-info')) }}"
                              name="status">
                              <option value="1" {{ $appointment->status == 1 ? 'selected' : '' }}>
                                {{ $keywords['Pending'] ?? __('Pending') }}</option>
                              <option value="2" {{ $appointment->status == 2 ? 'selected' : '' }}>
                                {{ $keywords['Approved'] ?? __('Approved') }}
                              </option>
                              <option value="3" {{ $appointment->status == 3 ? 'selected' : '' }}>
                                {{ $keywords['Completed'] ?? __('Completed') }}
                              </option>
                              <option value="4" {{ $appointment->status == 4 ? 'selected' : '' }}>
                                {{ $keywords['Rejected'] ?? __('Rejected') }}
                              </option>
                            </select>
                            <p id="errstatus" class="mb-0 text-danger em"></p>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Payment_status'] ?? __('Payment status') }}</label>
                            <select
                              class="form-control form-control-sm {{ $appointment->payment_status == 2 ? 'bg-success' : ($appointment->payment_status == 3 ? 'bg-primary' : ($appointment->payment_status == 4 ? 'bg-danger' : 'bg-info')) }}"
                              name="payment_status">
                              <option value="1" {{ $appointment->payment_status == 1 ? 'selected' : '' }}>
                                {{ $keywords['Pending'] ?? __('Pending') }}
                              </option>
                              <option value="2" {{ $appointment->payment_status == 2 ? 'selected' : '' }}>
                                {{ $keywords['Paid'] ?? __('Paid') }}
                              </option>
                              <option value="3" {{ $appointment->payment_status == 3 ? 'selected' : '' }}>
                                {{ $keywords['Advanced'] ?? __('Advanced') }}
                              </option>
                              @if ($appointment->transaction_details == 'offline')
                                <option value="4" {{ $appointment->payment_status == 4 ? 'selected' : '' }}>
                                  {{ $keywords['Rejected'] ?? __('Rejected') }}
                                </option>
                              @endif
                            </select>
                            <p id="errpayment_status" class="mb-0 text-danger em"></p>
                          </div>

                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Name'] ?? __('Name') }}</label>
                            <input type="text" class="form-control" name="name" value="{{ $appointment->name }}">
                            <p id="errname" class="mb-0 text-danger em"></p>
                          </div>
                        </div>

                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Total_Fee'] ?? __('Total Fee') }}
                              ({{ $appointment->currency }})</label>
                            <input type="text" readonly class="form-control" id="total_amount" name="total_amount"
                              value="{{ $appointment->total_amount }}">
                            <p id="errtotal_amount" class="mb-0 text-danger em"></p>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Paid_Fee'] ?? __('Paid fee') }}
                              ({{ $appointment->currency }})</label>
                            <input type="text" class="form-control" id="amount" name="amount"
                              value="{{ $appointment->amount }}">
                            <p id="erramount" class="mb-0 text-danger em"></p>
                          </div>
                        </div>
                        @if (!empty($appointment->due_amount))
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label>{{ $keywords['Due_amount'] ?? __('Due Amount') }}
                                ({{ $appointment->currency }})</label>
                              <input type="text" class="form-control" id="due_amount" name="due_amount"
                                value="{{ $appointment->due_amount }}">
                              <p id="errdue_amount" class="mb-0 text-danger em"></p>
                            </div>
                          </div>
                        @endif
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Time_Slot'] ?? __('Time Slot') }} </label>
                            <div class="form-control">
                              {{ $appointment->time }}
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label>{{ $keywords['Booked_Date'] ?? __('Booked Date') }} </label>
                            <div class="form-control">
                              {{ $appointment->date }}
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6 offset-lg-3">
                          <div class="calendar-container mt-4"></div>
                          <p id="errdate" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="col-lg-6 offset-lg-3">
                          <div class="form-group">
                            <div class=" col-lg-12 timeslot-box pt-5">
                              @foreach ($timeSlots as $slot)
                                @php
                                  $rowslot = $slot->start . ' - ' . $slot->end;
                                @endphp
                                <span
                                  class="single-timeslot mr-2 mb-2 {{ $rowslot == $appointment->time ? 'active' : '' }} p-2 rounded "
                                  data-id="{{ $slot->id }}"
                                  data-slot="{{ $slot->start }} - {{ $slot->end }}">{{ $slot->start }}
                                  - {{ $slot->end }}</span>
                              @endforeach
                            </div>
                            <div class="">
                              <p id="bookedSlot" class="text-danger pt-5 d-none">
                                {{ $keywords['This_time_slot_is_booked_Please_try_another_slot'] ?? __('This time slot is booked! Please try another slot') }}
                              </p>
                            </div>
                            <div class="form-group text-center">
                              <p id="errslot" class="mb-0 text-danger em"></p>
                              <div class=" col-lg-12  pt-5">
                                <input type="hidden" name="slot" value="{{ $appointment->time }}">
                                <input type="hidden" name="date" value="{{ $appointment->date }}">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6 offset-lg-3">
                          <div class="form-group text-center">
                            <button type="submit" data-form="ajaxForm" id=""
                              class="submitBtn btn  btn-primary">
                              {{ $keywords['save_change'] ?? __('Save Change') }}
                            </button>
                          </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer"></div>
      </div>
    </div>
  @endsection



  @section('scripts')
    @php
      $user = Auth::guard('web')->user();
      $holidays = App\Models\User\UserHoliday::where('user_id', $user->id)
          ->pluck('date')
          ->toArray();
      $dats = [];
      foreach ($holidays as $value) {
          $dats[] = Carbon\Carbon::parse($value)->format('Y-m-d');
      }
      $holidays = $dats;
      
      $weekends = App\Models\User\UserDay::where('user_id', $user->id)
          ->where('weekend', 1)
          ->pluck('index')
          ->toArray();
    @endphp

    @foreach ($weekends as $wek)
      <style>
        .pignose-calendar .pignose-calendar-header div.pignose-calendar-week:nth-child({{ $wek + 1 }}) {
          color: #ff6060 !important;
          /* Set the color of the text in the weekend cells */
        }

        .pignose-calendar .pignose-calendar-body .pignose-calendar-row .pignose-calendar-unit-date:nth-child({{ $wek + 1 }}) a {
          color: #ff6060;
          /* Set the color of the text in the weekend cells */
        }
      </style>
    @endforeach

    <script>
      "use strict";
      var $holidays = '<?php echo json_encode($holidays); ?>'
      var $weekends = '<?php echo json_encode($weekends); ?>'
    </script>
    <script>
      "use strict";
      var timeSlotUrl = "{{ route('getTimeSlot', $user->username) }}";;
      var checkThisSlot = "{{ route('checkThisSlot', $user->username) }}";
    </script>
    <script src="{{ asset('assets/front/js/pignose.calendar.full.min.js') }}"></script>
  @endsection
