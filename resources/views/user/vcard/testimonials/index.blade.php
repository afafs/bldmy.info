@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['vCard_Testimonials'] ?? __('vCard Testimonials') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user.vcard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['vCards'] ?? __('vCards') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $vcard->vcard_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Testimonials'] ?? __('Testimonials') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">
                                {{ $keywords['vCard_Testimonials'] ?? __('vCard Testimonials') }}</div>
                        </div>
                        <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
                            <a href="{{ route('user.vcard') }}" class="btn btn-secondary float-right btn-sm ml-2"><i
                                    class="fas fa-chevron-left"></i> {{ $keywords['Back'] ?? __('Back') }}</a>
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Testimonial'] ?? __('Add Testimonial') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.vcard.bulkTestimonialDelete') }}"><i
                                    class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
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
                                                <th scope="col">{{ $keywords['Client'] ?? __('Client') }}</th>
                                                <th scope="col">{{ $keywords['Rating'] ?? __('Rating') }}</th>
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
                                                        {{ $testimonial->rating }}
                                                    </td>
                                                    <td>
                                                        {{ $testimonial->serial_number }}
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-warning btn-sm editbtn"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-testimonial_id="{{ $testimonial->id }}"
                                                            data-name="{{ $testimonial->name }}"
                                                            data-rating="{{ $testimonial->rating }}"
                                                            data-comment="{{ $testimonial->comment }}"
                                                            data-serial_number="{{ $testimonial->serial_number }}"
                                                            data-image="{{ asset('assets/front/img/user/testimonials/' . $testimonial->image) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.vcard.testimonialDelete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="testimonial_id"
                                                                value="{{ $testimonial->id }}">
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
    @includeIf('user.vcard.testimonials.create')
    @includeIf('user.vcard.testimonials.edit')
@endsection
