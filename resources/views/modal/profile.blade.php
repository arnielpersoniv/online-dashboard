<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Profile Photo</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('upload')}}" id="form_upload" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-12 editimage-upload">
                            <label for="file-input-edit">
                                @if(Auth::user()->profile != null)
                                <img src="{{url('storage/profiles')}}/{{Auth::user()->profile}}" alt="profile" id="editimgshow" class="editimg_profile tip-right" data-original-title="Click to Choose File" />
                                @else
                                <img src="{{url('themes/images/faces/avatar.png')}}" alt="profile" id="editimgshow" class="editimg_profile tip-right" data-original-title="Click to Choose File" />
                                @endif
                            </label>
                            <input type="file" name="file_name" class="editimgload" id="file-input-edit" accept="image/*" required />
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_upload" class="btn btn-success tip-top" data-original-title="Submit Photo">Upload</button>
                <button type="button" class="btn btn-default tip-top" data-dismiss="modal" data-original-title="Close Modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>