@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ $keywords['Preloader'] ?? __('Preloader') }} </h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('user-dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['Basic_Settings'] ?? __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['Preloader'] ?? __('Preloader') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ $keywords['Update_Preloader'] ?? __('Update Preloader') }}</div>
        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form  enctype="multipart/form-data" action="{{route('user.preloader.update')}}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2">
                        <label for="image"><strong> {{ $keywords['Preloader'] ?? __('Preloader') }} **</strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3">
                        <img src="{{isset($basic_setting->preloader) ? asset('assets/front/img/user/'.$basic_setting->preloader) :  asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail" width="170">
                      </div>
                      <input type="file" name="file" id="image" class="form-control">
                      <p class="text-warning">{{ $keywords['img_validation_msg'] ?? __('Only JPG, JPEG, PNG images are allowed') }}</p>
                      @if ($errors->has('file'))
                      <p id="errfile" class="mb-0 text-danger em">{{$errors->first('file')}}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <div class="form">
                    <div class="form-group from-show-notify row">
                      <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
