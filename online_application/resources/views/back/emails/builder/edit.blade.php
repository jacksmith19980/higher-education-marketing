@extends('back.layouts.default')

@section('styles')
<style>
    #remove-file {
        background-color: transparent;
        border: none;
        color: #f101016e;
        font-size: 20px;
        cursor: pointer;
    }
    #remove-file:hover {
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
                    <h4 class="card-title">{{__('Edit New E-Mail Template')}}</h4>
                    <hr>
                    <!-- <h6 class="card-subtitle">You can us the validation like what we did</h6> -->
                    <form method="POST" action="{{ route('email-templates.update', $emailTemplate) }}"
                        class="validation-wizard wizard-circle m-t-40"
                        aria-label="{{ __('Update E-Mail Template') }}"
                        data-add-button="{{__('Update E-Mail Template')}}"
                        enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <!-- Step 1 -->
                        <h6>{{__('Template Information')}}</h6>
                        <section style="margin-bottom: 20px; margin-top: 15px;">

                            <div class="row">
                                <div class="col-md-12">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'name',
                                    'label' => 'Template Name' ,
                                    'class' => '',
                                    'required' => true,
                                    'attr' => '',
                                    'value' => $emailTemplate->name ?? ''
                                    ])
                                </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  @include('back.layouts.core.forms.text-input',
                                  [
                                  'name' => 'subject',
                                  'label' => 'Subject' ,
                                  'class' => '',
                                  'required' => true,
                                  'attr' => '',
                                  'value' => $emailTemplate->subject ?? ''
                                  ])
                              </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    @include('back.layouts.core.forms.switch',
                                    [
                                        'name' => 'published',
                                        'label' => __("Publish"),
                                        'class' => 'switch ajax-form-field',
                                        'required' => true,
                                        'attr' => 'data-on-text=Yes data-off-text=No',
                                        'helper_text' => '',
                                        'value' => $emailTemplate->is_published == '1' ? true : false,
                                        'default' => true
                                    ])
                                </div>
                            </div>

                        </section>

                        <!-- Step 2 -->
                        <h6>{{__('E-Mail Information')}}</h6>
                        <section style="margin-bottom: 20px; margin-top: 15px;">

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'from_name',
                                    'label' => 'From Name' ,
                                    'class' => '',
                                    'required' => false,
                                    'attr' => '',
                                    'value' => $emailTemplate->from_name ?? '',
                                    'tooltip' => 'The name of the sender'
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'from_address',
                                    'label' => 'From Address' ,
                                    'class' => '',
                                    'required' => false,
                                    'attr' => '',
                                    'value' => $emailTemplate->from_address ?? '',
                                    'tooltip' => 'The email address of the sender'
                                    ])
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'reply_to_address',
                                    'label' => 'Reply To Address' ,
                                    'class' => '',
                                    'required' => false,
                                    'attr' => '',
                                    'value' => $emailTemplate->reply_to_address ?? '',
                                    'tooltip' => 'The email address who will recieve the response'
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('back.layouts.core.forms.text-input',
                                    [
                                    'name' => 'bbc_address',
                                    'label' => 'BBC Address' ,
                                    'class' => '',
                                    'required' => false,
                                    'attr' => '',
                                    'value' => $emailTemplate->bcc_address ?? '',
                                    'tooltip' => 'The email address that you want to include'
                                    ])
                                </div>
                            </div>

                            <div class="row">
                                @include('back.layouts.core.forms.campuses', [
                                    'class'     => '',
                                    'required'  => false,
                                    'attr'      => '',
                                    'value'     => '',
                                    'data'      => $campuses
                                ])
                                <div class="col-md-6">
                                    <div onmouseenter="app.showAddSingleItem(this);" ontouchstart="app.showAddSingleItem(this);" id="{{$uid}}">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'          => 'category',
                                            'label'         => 'Category' ,
                                            'class'         => 'new-single-item' ,
                                            'required'      => false,
                                            'attr'          => '',
                                            'value'         => '',
                                            'data'         => $educationalServiceCategories,
                                            'addNewRoute'   => route('educationalservicecategories.store'),
                                            'placeholder'   => 'Select a Category',
                                        ])
                                    </div>
                                </div>
                            </div>

                        </section>

                        <!-- Step 3 -->
                       <h6>{{__('Attached Files')}}</h6>
                       <section style="margin-bottom: 20px; margin-top: 15px;">

                        <div id="class_container">
                            @if ($attachedFile->count())
                                <table class="table">
                                    <tbody>
                                        @foreach ($attachedFile as $file)
                                            <tr file-row="{{$file->id}}">
                                                <td>
                                                    <h6><a href="{{ Storage::disk('s3')->temporaryUrl($file->file_path, , \Carbon\Carbon::now()->addMinutes(5)) }}" target="_blank">{{$file->original_name}}</a></h6>
                                                    <small class="text-muted">{{__('Uploaded')}}: {{$file->created_at->diffForHumans()}}</small>
                                                </td>
                                                <td>
                                                    <a href="{{ Storage::disk('s3')->temporaryUrl($file->file_path, \Carbon\Carbon::now()->addMinutes(5)) }}" target="_blank" class="btn">
                                                        <i style="font-size: 22px" class="icon-arrow-down-circle" data-toggle="tooltip" data-placement="top" title="Download This File"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <button id="remove-file" onclick="deleteTemplateAttachedFile('{{ $file->id }}'); return false; ">
                                                        <i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Delete This File"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <input id="attached_files"
                            class="filepond"
                            name="attached_files[]"
                            multiple
                            data-allow-reorder="true"
                            data-max-file-size="5MB"
                            data-max-files="5">

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
    const inputElement = document.querySelector('input[id="attached_files"]');
    const pond = FilePond.create(inputElement);
    FilePond.setOptions({
        server: {
            process: "{{route('email-templates.file.upload')}}",
            revert: "{{route('email-templates.file.destroy')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });
});

    deleteTemplateAttachedFile = function(fileId) {
        event.preventDefault();
        var i = 0;
        $('#confirm-delete').modal('show');
        $('#confirm-delete .btn-ok').click(function (e) {
            if (i == 0) {
                i++;
                var data = {
                    action: 'emailTemplate.deleteAttachedFile',
                    payload: {
                        attached_file_id: fileId
                    }
                };
                app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
                    toastr.success('Deleted successfully');
                    $('tr[file-row="' + fileId + '"]').fadeOut();
                });
                $('#confirm-delete').modal('hide');
            }
        });
    }
</script>
@endsection
