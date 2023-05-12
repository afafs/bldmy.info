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

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Admin') }}</h4>
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
                <a href="#">{{ __('Admins Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Admin') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Admin') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.user.index') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">

                            <form id="ajaxForm" class="" action="{{ route('admin.user.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="image"><strong>{{ __('Featured Image') }} **</strong></label>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ $user->image ? asset('assets/admin/img/propics/' . $user->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail" width="170">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control image">
                                            <p id="errimage" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Username') }} **</label>
                                            <input type="text" class="form-control" name="username"
                                                placeholder="{{ __('Enter username') }}" value="{{ $user->username }}">
                                            <p id="errusername" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Email') }} **</label>
                                            <input type="text" class="form-control" name="email"
                                                placeholder="{{ __('Enter email') }}" value="{{ $user->email }}">
                                            <p id="erremail" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('First Name') }} **</label>
                                            <input type="text" class="form-control" name="first_name"
                                                placeholder="{{ __('Enter first name') }}"
                                                value="{{ $user->first_name }}">
                                            <p id="errfirst_name" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Last Name') }} **</label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="{{ __('Enter last name') }}" value="{{ $user->last_name }}">
                                            <p id="errlast_name" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Status') }} **</label>
                                            <select class="form-control" name="status">
                                                <option value="" selected disabled>{{ __('Select a status') }}
                                                </option>
                                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
                                                    {{ __('Active') }}
                                                </option>
                                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactive') }}
                                                </option>
                                            </select>
                                            <p id="errstatus" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Role') }} **</label>
                                            <select class="form-control" name="role_id">
                                                <option value="" selected disabled>{{ __('Select a Role') }}
                                                </option>
                                                @foreach ($roles as $key => $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="errrole_id" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
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
                                    class="submitBtn btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
