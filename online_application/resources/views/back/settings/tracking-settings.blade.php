@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp
<form method="post" action="{{route('settings.store')}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
    <div class="row">
        @csrf

        @include('back.layouts.core.forms.hidden-input', [
                    'name'          => 'group',
                    'value'         => 'tracking',
                    'class'         => '',
                    'required'      => '',
                    'attr'          => $disabled,
        ])

            <div id="accordion_application_action" role="tablist" aria-multiselectable="true" class="col-md-12 applicationActionDetails accordion">
                    @include('back.settings.tracking.google')
            </div>

            <div id="accordion_application_action" role="tablist" aria-multiselectable="true" class="col-md-12 applicationActionDetails accordion">
                    @include('back.settings.tracking.facebook')
            </div>


          {{--   <div class="col-md-12">

                @include('back.layouts.core.forms.html',

                [

                    'name'          => 'header_code',

                    'label'         => 'Header Code' ,

                    'class'     =>'' ,

                    'required'  => false,

                    'attr'      => '',

                    'value'     => isset($settings['tracking']['header_code']) ? $settings['tracking']['header_code'] : ''

                ])

            </div> --}}

           {{--  <div class="col-md-12">

                @include('back.layouts.core.forms.html',

                [

                    'name'          => 'body_code',

                    'label'         => 'Body Code' ,

                    'class'     =>'' ,

                    'required'  => false,

                    'attr'      => '',

                    'value'     => isset($settings['tracking']['body_code']) ? $settings['tracking']['body_code'] : ''

                ])

            </div> --}}








        <div class="col-md-12">
                <button data-name="settings_tracking_save_button" class="float-right btn btn-success">Save</button>

        </div>

    </div>

</form>
