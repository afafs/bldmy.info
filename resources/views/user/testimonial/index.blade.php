@extends('user.layout')

@php
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Testimonial'] ?? __('Testimonials') }}</h4>
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
                <a href="#">{{ $keywords['Testimonial'] ?? __('Testimonials') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Testimonial'] ?? __('Testimonials') }}
                            </div>
                        </div>
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Testimonial'] ?? __('Add Testimonial') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.testimonial.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                                {{ $keywords['Delete'] ?? __('Delete') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (is_null($userDefaultLang))
                                <h3 class="text-center">{{ $keywords['NO_LANGUAGE_FOUND'] ?? __('NO LANGUAGE FOUND') }}
                                </h3>
                            @else
                                @if (count($testimonials) == 0)
                                    <h3 class="text-center">
                                        {{ $keywords['NO_TESTIMONIAL_FOUND'] ?? __('NO TESTIMONIAL FOUND') }}</h3>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mt-3" id="basic-datatables">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <input type="checkbox" class="bulk-check" data-val="all">
                                                    </th>
                                                    <th scope="col">{{ $keywords['Image'] ?? __('Image') }}</th>
                                                    <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                    <th scope="col">
                                                        {{ $keywords['Publish_Date'] ?? __('Publish Date') }} </th>
                                                    <th scope="col">
                                                        {{ $keywords['Serial_Number'] ?? __('Serial Number') }} </th>
                                                    <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($testimonials as $key => $testimonial)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="bulk-check"
                                                                data-val="{{ $testimonial->id }}">
                                                        </td>
                                                        <td><img src="{{ asset('assets/front/img/user/testimonials/' . $testimonial->image) }}"
                                                                alt="" width="80"></td>
                                                        <td>{{ strlen($testimonial->name) > 30 ? mb_substr($testimonial->name, 0, 30, 'UTF-8') . '...' : $testimonial->name }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $date = \Carbon\Carbon::parse($testimonial->created_at);
                                                            @endphp
                                                            {{ $date->translatedFormat('jS F, Y') }}
                                                        </td>
                                                        <td>{{ $testimonial->serial_number }}</td>
                                                        <td>
                                                            <a class="btn btn-secondary btn-sm"
                                                                href="{{ route('user.testimonial.edit', $testimonial->id) . '?language=' . $testimonial->language->code }}">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-edit"></i>
                                                                </span>
                                                                {{ $keywords['Edit'] ?? __('Edit') }}
                                                            </a>
                                                            <form class="deleteform d-inline-block"
                                                                action="{{ route('user.testimonial.delete') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $testimonial->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm deletebtn">
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
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Create Blog Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ $keywords['Add_Testimonial'] ?? __('Add Testimonial') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                        action="{{ route('user.testimonial.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="col-12 mb-2">
                                        <label
                                            for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}*</strong></label>
                                    </div>
                                    <div class="col-md-12 showImage mb-3">
                                        <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                            class="img-thumbnail" width="170">
                                    </div>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <p id="errimage" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Language'] ?? __('Language') }} **</label>
                            <select name="user_language_id" class="form-control">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_language'] ?? __('Select a language') }}</option>
                                @foreach ($userLanguages as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <p id="erruser_language_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                            <input type="text" class="form-control" name="name"
                                placeholder="{{ $keywords['Enter_Name'] ?? __('Enter Name') }}" value="">
                            <p id="errname" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Occupation'] ?? __('Occupation') }}</label>
                            <input type="text" class="form-control" name="occupation"
                                placeholder="{{ $keywords['Enter_Occupation'] ?? __('Enter Occupation') }}"
                                value="">
                            <p id="erroccupation" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Feedback'] ?? __('Feedback') }} **</label>
                            <textarea class="form-control" name="content" rows="5"
                                placeholder="{{ $keywords['Enter_Feedback'] ?? __('Enter Feedback') }}"></textarea>
                            <p id="errcontent" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} **</label>
                            <input type="number" class="form-control ltr" name="serial_number" value=""
                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                            <p id="errserial_number" class="mb-0 text-danger em"></p>
                            <p class="text-warning mb-0">
                                <small>{{ $keywords['Testimonial_Serial_Number_msg'] ??
                                    __('The higher the serial number is, the later the testimonial will be
                                                                                              shown') }}.</small>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                    <button id="" data-form="ajaxForm" type="button"
                        class="submitBtn btn btn-primary">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
