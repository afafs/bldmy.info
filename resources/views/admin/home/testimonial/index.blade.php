@extends('admin.layout')

@if(!empty($abs->language) && $abs->language->rtl == 1)
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
    <h4 class="page-title">{{ __('Testimonials') }} </h4>
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
        <a href="#">{{ __('Home Page') }} </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Testimonials') }} </a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Testimonials') }} </div>
            </div>
            <div class="col-lg-4">
              
            </div>
            <div class="col-lg-4">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Testimonial') }} </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($testimonials) == 0)
                <h3 class="text-center">{{ __('NO TESTIMONIAL FOUND') }} </h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Image') }} </th>
                        <th scope="col">{{ __('Name') }} </th>
                        <th scope="col">{{ __('Rank') }} </th>
                        <th scope="col">{{ __('Serial Number') }} </th>
                        <th scope="col">{{ __('Actions') }} </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($testimonials as $key => $testimonial)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td><img src="{{asset('assets/front/img/testimonials/'.$testimonial->image)}}" alt="" width="40"></td>
                          <td>{{$testimonial->name}}</td>
                          <td>{{$testimonial->rank}}</td>
                          <td>{{$testimonial->serial_number}}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm" href="{{route('admin.testimonial.edit', $testimonial->id) . '?language=' . request()->input('language')}}">
                            <span class="btn-label">
                              <i class="fas fa-edit"></i>
                            </span>
                            {{ __('Edit') }} 
                            </a>
                            <form class="deleteform d-inline-block" action="{{route('admin.testimonial.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="testimonial_id" value="{{$testimonial->id}}">
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


  <!-- Create Testimonial Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Testimonial') }} </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="ajaxForm" class="modal-form" action="{{route('admin.testimonial.store')}}" method="POST">
            @csrf
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <div class="col-12 mb-2">
                    <label for="image"><strong>{{ __('Image') }} </strong></label>
                  </div>
                  <div class="col-md-12 showImage mb-3">
                    <img src="{{ asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail" width="170">
                  </div>
                  <input type="file" name="image" id="image" class="form-control image">
                  <p id="errimage" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>
            <div class="form-group">
                <label for="">{{ __('Language') }}  **</label>
                <select name="language_id" class="form-control">
                    <option value="" selected disabled>{{ __('Select a language') }} </option>
                    @foreach ($langs as $lang)
                        <option value="{{$lang->id}}">{{$lang->name}}</option>
                    @endforeach
                </select>
                <p id="errlanguage_id" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Comment') }}  **</label>
              <textarea class="form-control" name="comment" rows="3" cols="80" placeholder="{{ __('Enter comment') }} "></textarea>
              <p id="errcomment" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Name') }}  **</label>
              <input type="text" class="form-control" name="name" value="" placeholder="{{ __('Enter name') }} ">
              <p id="errname" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Rank') }}  **</label>
              <input type="text" class="form-control" name="rank" value="" placeholder="{{ __('Enter rank') }} ">
              <p id="errrank" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Serial Number') }}  **</label>
              <input type="number" class="form-control ltr" name="serial_number" value="" placeholder="{{ __('Enter Serial Number') }} ">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
              <p class="text-warning"><small>{{ __('The higher the serial number is, the later the testimonial will be shown') }} .</small></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Update') }} {{ __('Close') }}</button>
          <button id="" data-form="ajaxForm" type="button" class="submitBtn btn btn-primary">{{ __('Update') }} {{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
