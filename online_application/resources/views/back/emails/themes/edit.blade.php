@extends('back.layouts.default')

@section('styles')
<style>
    #remove-image {
        background-color: transparent;
        border: none;
        color: #f101016e;
        font-size: 20px;
        cursor: pointer;
    }
    #remove-image:hover {
        color: #ed1515c9;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Edit E-Mail Theme')}}</h4>
                    <hr>
                    <input id="email_theme" name="email_theme" type="hidden" value="{{$emailTheme->id}}">

                    <form method="POST" action="{{ route('email-themes.update', $emailTheme) }}"
                        class="validation-wizard wizard-circle m-t-40"
                        aria-label="{{ __('Update E-Mail Template') }}"
                        data-add-button="{{__('Update E-Mail Template')}}"
                        enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <!-- Step 1 -->
                        <h6>{{__('Theme Information')}}</h6>
                        <section style="margin-bottom: 20px; margin-top: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'name',
                                    'label' => 'Theme Name' ,
                                    'class' => '',
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $emailTheme->name ?? ''
                                    ])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="thumb-image-div">
                                    <label for="thumb_image">{{ __('Theme Thumb Image') }}</label>
                                    @if (isset($emailTheme->thumb_image_path))
                                        <div id="image-container" style="height: 250px; width: 250px; position: relative;">
                                            <img id="theme-thumb-image" src="{{ Storage::disk('s3')->temporaryUrl($emailTheme->thumb_image_path , \Carbon\Carbon::now()->addMinutes(5)) }}" alt="{{$emailTheme->thumb_image_name}}" style="width: 100%; height: 100%;">
                                            <button id="remove-image" style="position: absolute; top: 0; right: 0;">
                                                <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Delete This thumb Image"></i>
                                            </button>
                                        </div>
                                    @else
                                    <input id="thumb_image"
                                        class="filepond"
                                        name="thumb_image"
                                        data-max-file-size="5MB">
                                    @endif
                                </div>
                            </div>
                        </section>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const inputElement = document.querySelector('input[id="thumb_image"]');
    const pond = FilePond.create(inputElement);
    FilePond.setOptions({
        server: {
            process: "{{route('email-themes.thumb_image.upload')}}",
            revert: "{{route('email-themes.thumb_image.destroy')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });
    var removeButton = document.getElementById("remove-image");
    var emailThemeId = document.getElementById("email_theme");
    var i = 0;
    removeButton.addEventListener("click", function() {
        event.preventDefault();
        $('#confirm-delete').modal('show');
        $('#confirm-delete .btn-ok').click(function (e) {
            if (i == 0) {
                i++;
                console.log("remove thumb image "+emailThemeId.value);
                var data = {
                    action: 'emailThemes.deleteEmailThemeThumbImage',
                    payload: {
                        email_theme_id: emailThemeId.value
                    }
                };
                app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
                    toastr.success('Deleted successfully');
                    const thumbDiv = document.getElementById('thumb-image-div');
                    thumbDiv.innerHTML = '';
                    thumbDiv.innerHTML = '<input id="thumb_image" class="filepond" name="thumb_image" data-max-file-size="5MB">';
                    const inputElement = document.querySelector('input[id="thumb_image"]');
                    const pond = FilePond.create(inputElement);
                    FilePond.setOptions({
                        server: {
                            process: "{{route('email-themes.thumb_image.upload')}}",
                            revert: "{{route('email-themes.thumb_image.destroy')}}",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }
                    });
                });
                $('#confirm-delete').modal('hide');
            }
        });
    });
});
</script>
@endsection
