<!-- Modal -->
<div class="modal fade" id="templateModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{__('Preview Image')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('register.user.template')}}" id="templateForm{{$user->id}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <input type="hidden" name="template" value="">
            <div class="form-group">
              <div class="col-md-12 showImage mb-3">
                <img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail">
              </div>
              <input type="file" name="preview_image" class="image" class="form-control image">
              <p id="errpreview_image{{$user->id}}" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{__('Serial Number')}} **</label>
              <input type="number" class="form-control ltr" name="serial_number" value="" placeholder="{{__('Enter Serial Number')}}">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
              <p class="text-warning"><small>{{__('The higher the serial number is, the later the template will be shown.')}}</small></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button form="templateForm{{$user->id}}" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
        </div>
      </div>
    </div>
  </div>