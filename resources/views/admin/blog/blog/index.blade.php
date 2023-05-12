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
        <h4 class="page-title">{{ __('Blogs') }}</h4>
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
                <a href="#">{{ __('Blog Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Blogs') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Blogs') }}</div>
                        </div>

                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Blog') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.blog.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                                {{ __('Delete') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($blogs) == 0)
                                <h3 class="text-center">{{ __('NO BLOG FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Image') }}</th>
                                                <th scope="col">{{ __('Category') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Publish Date') }} </th>
                                                <th scope="col">{{ __('Serial Number') }} </th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($blogs as $key => $blog)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $blog->id }}">
                                                    </td>
                                                    <td><img src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}"
                                                            alt="" width="80"></td>
                                                    <td>{{ $blog->bcategory->name }}</td>
                                                    <td>{{ strlen($blog->title) > 30 ? mb_substr($blog->title, 0, 30, 'UTF-8') . '...' : $blog->title }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $date = \Carbon\Carbon::parse($blog->created_at);
                                                        @endphp
                                                        {{ $date->translatedFormat('jS F, Y') }}
                                                    </td>
                                                    <td>{{ $blog->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('admin.blog.edit', $blog->id) . '?language=' . request()->input('language') }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('admin.blog.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="blog_id"
                                                                value="{{ $blog->id }}">
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
    <!-- Create Blog Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Blog') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                        action="{{ route('admin.blog.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-12 mb-2">
                                        <label for="image"><strong>{{ __('Image') }}</strong></label>
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
                            <label for="">{{ __('Language') }} **</label>
                            <select id="language" name="language_id" class="form-control">
                                <option value="" selected disabled>{{ __('Select a Language') }}</option>
                                @foreach ($langs as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <p id="errlanguage_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Title') }} **</label>
                            <input type="text" class="form-control" name="title"
                                placeholder="{{ __('Enter title') }}" value="">
                            <p id="errtitle" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Category') }} **</label>
                            <select id="bcategory" class="form-control" name="category" disabled>
                                <option value="" selected disabled>{{ __('Select a category') }}</option>
                            </select>
                            <p id="errcategory" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Content') }} **</label>
                            <textarea class="form-control summernote" name="content" rows="8" cols="80"
                                placeholder="{{ __('Enter content') }}"></textarea>
                            <p id="errcontent" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('Serial Number') }} **</label>
                            <input type="number" class="form-control ltr" name="serial_number" value=""
                                placeholder="{{ __('Enter Serial Number') }}">
                            <p id="errserial_number" class="mb-0 text-danger em"></p>
                            <p class="text-warning mb-0">
                                <small>{{ __('The higher the serial number is, the later the blog will be shown') }}.</small>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Meta Keywords') }}</label>
                            <input type="text" class="form-control" name="meta_keywords" value=""
                                data-role="tagsinput">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Meta Description') }}</label>
                            <textarea type="text" class="form-control" name="meta_description" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button  data-form="ajaxForm" type="button" class="submitBtn btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
