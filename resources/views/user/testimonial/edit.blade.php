@extends('user.layout')

@if (!empty($testimonial->language) && $testimonial->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            form .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Testimonial'] ?? __('Edit Testimonial') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user.testimonials.index') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Testimonial_Page'] ?? __('Testimonial Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Testimonial'] ?? __('Edit Testimonial') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Testimonial'] ?? __('Edit Testimonial') }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.testimonials.index') . '?language=' . $testimonial->language->code }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" class="" action="{{ route('user.testimonial.update') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $testimonial->id }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}*</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ $testimonial->image ? asset('assets/front/img/user/testimonials/' . $testimonial->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail" width="170">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control">
                                            <p id="errimage" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Name'] ?? __('Name') }}*</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $testimonial->name }}"
                                        placeholder="{{ $keywords['Enter_Name'] ?? __('Enter Name') }}">
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Occupation'] ?? __('Occupation') }}</label>
                                    <input type="text" class="form-control" name="occupation"
                                        value="{{ $testimonial->occupation }}"
                                        placeholder="{{ $keywords['Enter_Occupation'] ?? __('Enter Occupation') }}">
                                    <p id="erroccupation" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Feedback'] ?? __('Feedback') }} **</label>
                                    <textarea class="form-control" name="content" rows="5"
                                        placeholder="{{ $keywords['Enter_Feedback'] ?? __('Enter Feedback') }}">{{ replaceBaseUrl($testimonial->content) }}</textarea>
                                    <p id="errcontent" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                        **</label>
                                    <input type="number" class="form-control ltr" name="serial_number"
                                        value="{{ $testimonial->serial_number }}" placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">
                                        <small>{{ $keywords['Testimonial_Serial_Number_msg'] ??
                                            __('The higher the serial number is, the later the testimonial will be shown') }}.</small>
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
                                <button type="submit" data-form="ajaxForm" id=""
                                    class="submitBtn btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
