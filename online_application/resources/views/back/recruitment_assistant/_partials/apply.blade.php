<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info handle">
            <h4 class="m-b-0 text-white">{{__('Apply')}}</h4>
        </div>
        <div class="card-body row">
           
            <div class="col-md-12">
                @include('back.layouts.core.forms.text-input',
                [
                'name' => 'properties[apply_help_title]',
                'label' => 'Help title' ,
                'class' =>'' ,
                'required' => false,
                'attr' => '',
                'value' => (isset($assistantBuilder->properties['apply_help_title'])) ? $assistantBuilder->properties['apply_help_title'] : '',
                ])
            </div>
            
            <div class="col-md-12">
                @include('back.layouts.core.forms.html', [
                    'name'     => 'properties[apply_help_message]',
                    'label'    => 'Help text' ,
                    'class'    => '' ,
                    'required' => false,
                    'attr'     => '',
                    'value' => (isset($assistantBuilder->properties['apply_help_message'])) ? $assistantBuilder->properties['apply_help_message'] : '',
                ])
            </div>

        </div>
    </div>
</div>