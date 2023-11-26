@extends('back.layouts.default')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body wizard-content">
                    <h4 class="card-title">{{__('Clone E-Mail Template')}}</h4>
                    <hr>
            
                    <form method="POST" action="{{ route('email-templates.store') }}"
                        class="validation-wizard wizard-circle m-t-40" 
                        aria-label="{{ __('Create E-Mail Template') }}"
                        data-add-button="{{__('Create E-Mail Template')}}" 
                        enctype="multipart/form-data">
                        @csrf

                        <input name="cloned_template" type="hidden" value="{{$emailTemplate}}">
                        
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
                                    'value' => ''
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
                                  'value' => ''
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
                                        'value' => true,
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
                                    'value' => '',
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
                                    'value' => '',
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
                                    'value' => '',
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
                                    'value' => '',
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

                        <input id="attached_files"
                            class="filepond"
                            name="attached_files[]" 
                            multiple 
                            data-allow-reorder="true"
                            data-max-file-size="5MB"
                            data-max-files="10">

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
</script>
@endsection