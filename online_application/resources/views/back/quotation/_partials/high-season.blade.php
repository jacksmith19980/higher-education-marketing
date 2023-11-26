<div class="row">
    <div class="col-md-12">

        <div class="row repeated_fields">

            <div class="col-md-6">
                <div class="form-group">
                    @include('back.layouts.core.forms.date-input', [
                        'name'      => 'hiseason_start_dates',
                        'label'     => 'Start Date' ,
                        'class'     =>'' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset($settings['quotation']['hiseason_start_dates'])? $settings['quotation']['hiseason_start_dates'] : '',
                        'data'      => ''
                    ])
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    @include('back.layouts.core.forms.date-input', [
                        'name'      => 'hiseason_end_dates',
                        'label'     => 'End Date' ,
                        'class'     =>'' ,
                        'required'  => false,
                        'attr'      => '',
                        'value'     => isset($settings['quotation']['hiseason_end_dates'])? $settings['quotation']['hiseason_end_dates'] : '',
                        'data'      => ''
                    ])
                </div>
            </div>
        </div>

        <div class="repeated_fields_wrapper"></div>

    </div>

</div>