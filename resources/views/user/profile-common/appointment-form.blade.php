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
                    <form action="{{ route('front.user.appointment.booking.step1', getParam()) }}"
                        enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $cat ?? null }}">
                        <div class="form-group">
                            <label for="">{{ $keywords['Name'] ?? __('Name') }}<span>**</span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                        value="{{ Auth::guard('customer')->user()->username ?? '' }}"
                                        placeholder="{{ $keywords['Name'] ?? __('Name') }}">
                                </div>
                            </div>
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Email'] ?? __('Email') }}<span>**</span></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                        value="{{ Auth::guard('customer')->user()->email ?? '' }}"
                                        placeholder="{{ $keywords['Email'] ?? __('Email') }}">
                                </div>
                            </div>
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (count($inputs) > 0)
                            <div id="sortable">
                                @foreach ($inputs as $key => $input)
                                    {{-- input type text --}}
                                    @if ($input->type == 1)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input class="form-control" type="text" name="{{ $input->name }}"
                                                        value="{{old("$input->name")}}" placeholder="{{ $input->placeholder }}">
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @elseif ($input->type == 2)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <select class="form-control" name="{{ $input->name }}">
                                                        <option value="" selected disabled>
                                                            {{ $input->placeholder }}
                                                        </option>
                                                        @foreach ($input->form_input_options as $key => $option)
                                                            <option value="{{ $option->name }}" {{old("$input->name") == $option->name ? 'selected' : ''}}>{{ $option->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @elseif ($input->type == 3)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex gap-20 flex-wrap">
                                                        @foreach ($input->form_input_options as $key => $option)
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" id="customRadio{{ $option->id }}"
                                                                    name="{{ $input->name }}[]" value="{{$option->name}}" {{is_array(old("$input->name")) && in_array($option->name, old("$input->name")) ? 'checked' : ''}} id="option{{$option->id}}"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="customRadio{{ $option->id }}">{{ $option->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @elseif ($input->type == 4)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <textarea class="form-control" name="{{ $input->name }}" rows="5" cols="80"
                                                        placeholder="{{ $input->placeholder }}">{{old("$input->name")}}</textarea>
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @elseif ($input->type == 6)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control datepicker"
                                                        name="{{ $input->name }}" autocomplete="off"
                                                         value="{{old("$input->name")}}"
                                                        placeholder="{{ $input->placeholder }}">
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @elseif ($input->type == 7)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control timepicker"
                                                        name="{{ $input->name }}" autocomplete="off"
                                                        value="{{old("$input->name")}}"
                                                        placeholder="{{ $input->placeholder }}">
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @elseif ($input->type == 5)
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ __('Optional') }})
                                                @endif
                                                ({{ __('Allowed extensions:') }} {{ $input->file_extensions }})
                                            </label>
                                            <div class="row">
                                                <input type="hidden" name="file_extensions"
                                                    value="{{ $input->file_extensions }}" name="allowed_extensions">
                                                <div class="col-md-12">
                                                    <input name="{{ $input->name }}" type="file">
                                                </div>
                                            </div>
                                            @error($input->name)
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group pt-3">
                            <button class="@if ($userBs->theme == 3 || $userBs->theme == 4 || $userBs->theme == 5 || $userBs->theme == 6 || $userBs->theme == 7 || $userBs->theme == 8) main-btn @else template-btn @endif "
                                type="submit">{{ $keywords['Next'] ?? 'Next' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Vaughn-Features section ======-->
@endsection
@section('scripts')

@endsection
