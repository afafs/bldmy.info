@extends('user.layout')

@if (!empty($blog->language) && $blog->language->rtl == 1)
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
        <h4 class="page-title">{{ $keywords['Edit_Blog'] ?? __('Edit Blog') }}</h4>
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
                <a href="#">{{ $keywords['Blog_Page'] ?? __('Blog Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Blog'] ?? __('Edit Blog') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Blog'] ?? __('Edit Blog') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.blog.index') . '?language=' . $blog->language->code }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxEditForm" class="" action="{{ route('user.blog.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ $blog->image ? asset('assets/front/img/user/blogs/' . $blog->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail" width="170">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control">
                                            <p id="eerrimage" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Title'] ?? __('Title') }} **</label>
                                    <input type="text" class="form-control" name="title" value="{{ $blog->title }}"
                                        placeholder="{{ $keywords['Enter_title'] ?? __('Enter title') }}">
                                    <p id="eerrtitle" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Category'] ?? __('Category') }} **</label>
                                    <select class="form-control" name="category">
                                        <option value="" selected disabled>
                                            {{ $keywords['Select_a_category'] ?? __('Select a category') }}</option>
                                        @foreach ($bcats as $key => $bcat)
                                            <option value="{{ $bcat->id }}"
                                                {{ $bcat->id == $blog->bcategory->id ? 'selected' : '' }}>
                                                {{ $bcat->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="eerrcategory" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Content'] ?? __('Content') }} **</label>
                                    <textarea class="form-control summernote" name="content" data-height="300" placeholder="{{ $keywords['Enter_content'] ?? __('Enter content') }}">{{ replaceBaseUrl($blog->content) }}</textarea>
                                    <p id="eerrcontent" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                        **</label>
                                    <input type="number" class="form-control ltr" name="serial_number"
                                        value="{{ $blog->serial_number }}" placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    <p id="eerrserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">
                                        <small>{{ $keywords['bolg_Serial_Number_msg'] ?? __('The higher the serial number is, the later the blog will be shown') }}.</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Meta_Keywords'] ?? __('Meta Keywords') }}</label>
                                    <input type="text" class="form-control" name="meta_keywords"
                                        value="{{ $blog->meta_keywords }}" data-role="tagsinput">
                                    <p id="eerrmeta_keywords" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="">{{ $keywords['Meta_Description'] ?? __('Meta Description') }}</label>
                                    <textarea type="text" class="form-control" name="meta_description" rows="5">{{ $blog->meta_description }}</textarea>
                                    <p id="eerrmeta_description" class="mb-0 text-danger em"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="updateBtn"  class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
