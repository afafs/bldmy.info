@extends('user.layout')

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
                    <a href="{{ route('user.holidays', ['language' => request('language')]) }}">{{ $keywords['Holidays'] ?? __('Holidays') }}</a>
                </li>
            </ul>
        </h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addTF float-right btn-sm mr-1 "
                        data-day="monday">{{ $keywords['Add'] ?? __('Add') }}</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($holidays) == 0)
                                <h3 class="text-center">{{ $keywords['NO_HOLIDAYS_FOUND'] ?? __('NO HOLIDAYS FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ $keywords['Date'] ?? __('Date') }} </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }} </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($holidays as $holiday)
                                                <tr>
                                                    <td>{{ $holiday->date }}</td>
                                                    <td>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.holiday.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="holiday_id"
                                                                value="{{ $holiday->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ $keywords['Delete'] ?? __('Delete') }}
                                                            </button>
                                                        </form>
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
    @includeIf('user.appointment.modals.holiday_create')
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".addTF").on('click', function(e) {
                e.preventDefault();
                $("#createModal").modal('show');
                $("input[name='day']").val($(this).data('day'));
            })
        });
    </script>
@endsection
