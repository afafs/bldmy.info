@extends('admin.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Request Page Texts') }}</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="{{route('admin.dashboard')}}">
        <i class="flaticon-home"></i>
      </a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Custom Domains') }}</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Request Page Texts') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">{{ __('Texts') }}</div>

      </div>
      <div class="card-body pt-5 pb-5">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <form id="textsForm" action="{{route('admin.custom-domain.texts')}}" method="POST">
              @csrf
                <div class="form-group">
                    <label>{{ __('Message After Domain Request') }} **</label>
                    <textarea name="success_message" rows="3" class="form-control">{{$abe->domain_request_success_message}}</textarea>
                    @if ($errors->has('success_message'))
                        <p class="text-danger mb-0">{{$errors->first('success_message')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('CNAME Record Section Title') }} **</label>
                    <input type="text" name="cname_record_section_title" class="form-control" value="{{$abe->cname_record_section_title}}">
                    @if ($errors->has('cname_record_section_title'))
                        <p class="text-danger mb-0">{{$errors->first('cname_record_section_title')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('CNAME Record Section Text') }} **</label>
                    <textarea class="summernote" name="cname_record_section_text" data-height="150" class="form-control">{!! replaceBaseUrl($abe->cname_record_section_text) !!}</textarea>
                    @if ($errors->has('cname_record_section_text'))
                        <p class="text-danger mb-0">{{$errors->first('cname_record_section_text')}}</p>
                    @endif
                </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="form">
          <div class="form-group from-show-notify row">
            <div class="col-12 text-center">
              <button type="submit" form="textsForm" class="btn btn-success">{{ __('Update') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
