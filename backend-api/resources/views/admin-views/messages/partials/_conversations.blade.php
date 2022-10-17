<style>
    div.scroll-down {
        max-height: 300px;
        overflow-y: scroll;
    }

</style>
<div class="card mb-3 mb-lg-5">
    <!-- Header -->
    <div class="card-header p-md-3 p-0">
        <div class="avatar avatar-lg avatar-circle m-md-0 m-1">
            <img class="avatar-img" style="width: 54px;height: 54px"
                 src="{{asset('storage/app/public/profile/'.$user['image'])}}"
                 onerror="this.src='{{asset('public/assets/admin')}}/img/160x160/img1.jpg'"
                 alt="Image Description">
        </div>
        <h5 class="mb-0 mr-md-3 mr-1">{{$user['f_name'].' '.$user['l_name']}}</h5>
    </div>

    <div class="card-body">
        <div class="row scroll-down">
            @foreach($convs as $key=>$con)
                @if(($con->message!=null && $con->reply==null) || $con->is_reply == false)
                    <div class="col-12 pt1 pb-1">
                        <div class="w-85 w-md-50"
                             style="background:#fdffddd1;padding: 10px;border: 1px solid #80808057;border-radius: 10px;">
                            @if(isset($con->message))
                                <h6>{{$con->message}}</h6>
                            @endif
                            <?php try {?>
                            @if($con->image != null && $con->image != "null" && count(json_decode($con->image, true)) > 0)
                                @php($image_array = json_decode($con->image, true))
                                @foreach($image_array as $image)
                                    <img style="width:100%; border-radius: 5px"
                                         class="p-1"
                                         src="{{$image}}"
                                         onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'">
                                    <br/>
                                @endforeach
                            @endif
                            <?php }catch (\Exception $e) {} ?>
                        </div>
                    </div>
                @endif
                @if(($con->reply!=null && $con->message==null) || $con->is_reply == true)
                    <div class="col-12 pt-1 pb-1">
                        <div class="float-right w-85 w-md-50"
                             style="background:#89faff47;padding: 10px;border:1px solid #80808057;border-radius: 10px; width: 50%">
                            @if(isset($con->reply))
                                <h6>{{$con->reply}}</h6>
                            @endif
                            <?php try {?>
                            <div class="row">
                                @if($con->image != null && $con->image != "null" && count(json_decode($con->image, true)) > 0)
                                    @php($image_array = json_decode($con->image, true))
                                    @foreach($image_array as $key=>$image)
                                        @php($image_url = $image)
                                        <div
                                            class="col-12 @if(count(json_decode($con->image, true)) > 1) col-md-6 @endif">
                                            <img style="width:100%; border-radius: 5px"
                                                 class="p-1"
                                                 src="{{asset('storage/app/public/conversation').'/'.$image_url}}"
                                                 onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"><br/>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <?php }catch (\Exception $e) {} ?>
                        </div>
                    </div>
                @endif
            @endforeach
            <div id="scroll-here"></div>
        </div>
    </div>
    <!-- Body -->
</div>
<form action="javascript:" method="post" id="reply-form">
    @csrf
    <div class="card mb-3 mb-lg-5">
        <!-- Body -->
        <div class="card-body p-md-4 p-2">
            <label class="input-label">{{translate('reply')}}</label>
            <!-- Quill -->
            <div class="quill-custom_">
                <textarea class="form-control" name="reply"></textarea>
            </div>
            <!-- End Quill -->
        </div>
        <!-- Body -->

        <!-- Footer -->
        <div class="card-footer p-md-4 p-2">
            <div id="accordion" class="d-flex justify-content-end">
                <button class="btn btn-primary mr-2 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    {{translate('Upload')}} <i class="tio-upload"></i>
                </button>
                <button type="submit" onclick="replyConvs('{{route('admin.message.store',[$user->id])}}')"
                        class="btn btn-primary">{{translate('send')}} <i class="tio-send"></i>
                </button>
            </div>

            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                 data-parent="#accordion">
                <div class="card-body">
                    <div class="p-2 border border-dashed">
                        <div class="row" id="coba"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer -->
    </div>
</form>

<script>
    $(document).ready(function () {
        $('.scroll-down').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 0);
    });
</script>

{{-- Multi Image Picker --}}
<script>
    $('#collapseTwo').on('show.bs.collapse', function () {
        spartanMultiImagePicker();
    })

    $('#collapseTwo').on('hidden.bs.collapse', function () {
        document.querySelector("#coba").innerHTML = "";
    })


</script>
<script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>
<script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
<script>
    function spartanMultiImagePicker() {
        document.querySelector("#coba").innerHTML = "";

        $("#coba").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 4,
            rowHeight: '10%',
            groupClassName: 'col-3',
            maxFileSize: '',
            {{--placeholderImage: {--}}
                {{--    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',--}}
                {{--    width: '100%',--}}
                {{--},--}}
            dropFileLabel: "Drop Here",
            onAddRow: function (index, file) {

            },
            onRenderedPreview: function (index) {

            },
            onRemoveRow: function (index) {

            },
            onExtensionErr: function (index, file) {
                toastr.error('{{translate('Please only input png or jpg type file')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{translate('File size too big')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    }
</script>
