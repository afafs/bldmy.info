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
@php
$selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='language'] {
                direction: rtl;
            }

            form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Faqs') }}</h4>
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
                <a href="#">{{ __('Faqs') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Faqs') }}</div>
                        </div>
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Faq') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.faq.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                                {{ __('Delete') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($faqs) == 0)
                                <h3 class="text-center">{{ __('NO FAQ FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Question') }}</th>
                                                <th scope="col">{{ __('Serial Number') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($faqs as $key => $faq)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $faq->id }}">
                                                    </td>
                                                    <td>{{ strlen($faq->question) > 50 ? mb_substr($faq->question, 0, 50, 'UTF-8') . '...' : $faq->question }}
                                                    </td>
                                                    <td>{{ $faq->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm editbtn" href="#editModal"
                                                            data-toggle="modal" data-faq_id="{{ $faq->id }}"
                                                            data-question="{{ $faq->question }}"
                                                            data-answer="{{ $faq->answer }}"
                                                            data-serial_number="{{ $faq->serial_number }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('admin.faq.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="faq_id"
                                                                value="{{ $faq->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
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


    <!-- Create Faq Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Faq') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" class="modal-form create" action="{{ route('admin.faq.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ __('Language') }} **</label>
                            <select name="language_id" class="form-control">
                                <option value="" selected disabled>{{ __('Select a Language') }}</option>
                                @foreach ($langs as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <p id="errlanguage_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Question') }} **</label>
                            <input type="text" class="form-control" name="question" value=""
                                placeholder="{{ __('Enter question') }}">
                            <p id="errquestion" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Answer') }} **</label>
                            <textarea class="form-control" name="answer" rows="5" cols="80"
                                placeholder="{{ __('Enter answer') }}"></textarea>
                            <p id="erranswer" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Serial Number') }} **</label>
                            <input type="number" class="form-control ltr" name="serial_number" value=""
                                placeholder="{{ __('Enter Serial Number') }}">
                            <p id="errserial_number" class="mb-0 text-danger em"></p>
                            <p class="text-warning">
                                <small>{{ __('The higher the serial number is, the later the FAQ will be shown') }}.</small>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button  id="" data-form="ajaxForm" type="button" class="submitBtn btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Faq Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Faq') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxEditForm" class="" action="{{ route('admin.faq.update') }}" method="POST">
                        @csrf
                        <input id="infaq_id" type="hidden" name="faq_id" value="">
                        <div class="form-group">
                            <label for="">{{ __('Question') }} **</label>
                            <input id="inquestion" type="text" class="form-control" name="question" value=""
                                placeholder="{{ __('Enter question') }}">
                            <p id="eerrquestion" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Answer') }} **</label>
                            <textarea id="inanswer" class="form-control" name="answer" rows="5" cols="80"
                                placeholder="{{ __('Enter answer') }}"></textarea>
                            <p id="eerranswer" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Serial Number') }} **</label>
                            <input id="inserial_number" type="number" class="form-control ltr" name="serial_number"
                                value="" placeholder="{{ __('Enter Serial Number') }}">
                            <p id="eerrserial_number" class="mb-0 text-danger em"></p>
                            <p class="text-warning"><small>{{ __('The higher the serial number is, the later the FAQ will be shown') }}.</small></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Save Changes') }} </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            "use strict";

            // make input fields RTL
            $("select[name='language_id']").on('change', function() {
                $(".request-loader").addClass("show");
                let url = "{{ url('/') }}/admin/rtlcheck/" + $(this).val();
                $.get(url, function(data) {
                    $(".request-loader").removeClass("show");
                    if (data == 1) {
                        $("form.create input").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form.create select").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form.create textarea").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form.create .nicEdit-main").each(function() {
                            $(this).addClass('rtl text-right');
                        });

                    } else {
                        $("form.create input, form.create select, form.create textarea")
                            .removeClass('rtl');
                        $("form.create .nicEdit-main").removeClass('rtl text-right');
                    }
                })
            });

        });
    </script>
@endsection
