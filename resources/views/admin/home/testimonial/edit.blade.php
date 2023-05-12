@extends('admin.layout')

@if (Session::has('admin_lang'))
    @php
        $admin_lang = Session::get('admin_lang');
        $cd = str_replace('admin_', '', $admin_lang);
        $default = \App\Models\Language::where('code', $cd)->first();
    @endphp
@else
    @php
        $default = \App\Models\Language::where('is_default', 1)->first();
    @endphp
@endif
@if (!empty($testimonial->language) && $testimonial->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            .nicEdit-main {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Testimonial') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') . '?language=' . $default->code }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Testimonial') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Testimonial') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('admin.testimonial.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">

                            <form id="ajaxEditForm" class="" action="{{ route('admin.testimonial.update') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong> {{ __('Image') }}</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ $testimonial->image ? asset('assets/front/img/testimonials/' . $testimonial->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail" width="170">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control image">
                                            <p id="eerrimage" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="testimonial_id" value="{{ $testimonial->id }}">
                                <div class="form-group">
                                    <label for="">{{ __('Comment') }} **</label>
                                    <textarea class="form-control" name="comment" rows="3" cols="80" placeholder="{{ __('Enter comment') }}">{{ $testimonial->comment }}</textarea>
                                    <p id="eerrcomment" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Name') }} **</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $testimonial->name }}" placeholder="{{ __('Enter name') }}">
                                    <p id="eerrname" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Rank') }} **</label>
                                    <input type="text" class="form-control" name="rank"
                                        value="{{ $testimonial->rank }}" placeholder="{{ __('Enter rank') }}">
                                    <p id="eerrrank" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Serial Number') }} **</label>
                                    <input type="number" class="form-control ltr" name="serial_number"
                                        value="{{ $testimonial->serial_number }}"
                                        placeholder="{{ __('Enter Serial Number') }}">
                                    <p id="eerrserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">
                                        <small>{{ __('The higher the serial number is, the later the testimonial will be shown') }}.</small>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit"  id="updateBtn" class="btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
