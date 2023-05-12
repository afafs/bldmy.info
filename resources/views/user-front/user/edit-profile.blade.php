@extends("user.$folder.layout")
@section('tab-title')
    {{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}
@endsection
@section('br-title')
    {{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}
@endsection
@section('br-link')
    {{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}
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
    <!--====== Start User Edit-Profile Section  ======-->
    <section class="dashboard-area">
        <div class="container">
            <div class="row">
                @includeIf('user-front.user.side-navbar')
                <div class="col-lg-9">
                    <div class="profile-edit mt-30">
                        <div class="profile-sidebar-title">
                            <h4 class="title">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</h4>
                        </div>
                        <div class="profile-form">
                            <form action="{{ route('customer.update_profile', getParam()) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="single-form mb-3">
                                            <div class="col-md-12 showImage mb-3">
                                                <img data-src="{{ is_null($authUser->image) ? asset('assets/front/img/user/blank_propic.png') : asset('assets/user/img/users/' . $authUser->image) }}"
                                                    alt="user image" class="user-photo lazy">
                                            </div>
                                            <div class="custom-file mt-3">
                                                <input type="file" accept=".jpg, .jpeg, .png" name="image"
                                                    id="image" class="input-file">
                                                @error('image')
                                                    <p class="mb-3 text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        {{--

                                        <div class="single-form showImage">
                                            <img data-src="{{ is_null($authUser->image) ? asset('assets/front/img/user/blank_propic.png') : asset('assets/user/img/users/' . $authUser->image) }}"
                                                alt="user image" class="user-photo lazy p-5">

                                            <div class="custom-file">
                                                <input type="file" accept=".jpg, .jpeg, .png" name="image"
                                                    id="image" class="input-file">
                                                <label for="file" class="js-labelFile">
                                                    <span class="js-fileName">Choose a file</span>
                                                </label>
                                                @error('image')
                                                    <p class="mb-3 text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single-form">
                                            <label>{{ $keywords['First_Name'] ?? __('First Name') }}</label>
                                            <input type="text" class="form_control"
                                                placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                                                name="first_name" value="{{ $authUser->first_name }}">
                                            @error('first_name')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single-form">
                                            <label>{{ $keywords['Last_Name'] ?? __('Last Name') }}</label>
                                            <input type="text" class="form_control"
                                                placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                                                name="last_name" value="{{ $authUser->last_name }}">
                                            @error('last_name')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single-form">
                                            <label>{{ $keywords['Email'] ?? __('Email') }}</label>
                                            <input type="email" class="form_control"
                                                placeholder="{{ $keywords['Email'] ?? __('Email') }}"
                                                value="{{ $authUser->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single-form">
                                            <label>{{ $keywords['Phone'] ?? __('Phone') }}</label>
                                            <input type="text" class="form_control"
                                                placeholder="{{ $keywords['Phone'] ?? __('Phone') }}" name="contact_number"
                                                value="{{ $authUser->contact_number }}">
                                            @error('contact_number')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="single-form">
                                            <label>{{ $keywords['Address'] ?? __('Address') }}</label>
                                            <textarea class="form_control" placeholder="{{ $keywords['Address'] ?? __('Address') }}" name="address">{{ $authUser->address }}</textarea>
                                            @error('address')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="single-form">
                                            <label>{{ $keywords['City'] ?? __('City') }}</label>
                                            <input type="text" class="form_control"
                                                placeholder="{{ $keywords['City'] ?? __('City') }}" name="city"
                                                value="{{ $authUser->city }}">
                                            @error('city')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="single-form">
                                            <label>{{ $keywords['State'] ?? __('State') }}</label>
                                            <input type="text" class="form_control"
                                                placeholder="{{ $keywords['State'] ?? __('State') }}" name="state"
                                                value="{{ $authUser->state }}">
                                            @error('state')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="single-form">
                                            <label>{{ $keywords['Country'] ?? __('Country') }}</label>
                                            <input type="text" class="form_control"
                                                placeholder="{{ $keywords['Country'] ?? __('Country') }}" name="country"
                                                value="{{ $authUser->country }}">
                                            @error('country')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="single-form mt-2 mb-4">
                                            <button class="@if ($userBs->theme == 1 || $userBs->theme == 2 ) template-btn @else main-btn @endif">{{ $keywords['Update_profile'] ?? 'Update profile' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--======  End User Edit-Profile Section  ======-->
@endsection
@section('scripts')
    <script>
        //  image (id) preview js/
        $(document).on('change', '#image', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.showImage img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        })
        //  image (class) preview js/
        $(document).on('change', '.image', function(event) {
            let $this = $(this);
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $this.prev('.showImage').children('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });
    </script>
@endsection
