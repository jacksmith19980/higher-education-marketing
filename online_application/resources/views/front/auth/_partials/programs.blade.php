@if(isset($settings['auth']['show_program']) && $settings['auth']['show_program'] == 'Yes')

    @include('back.layouts.core.forms.select',
            [
            'name'          => 'program',
            'label'         => '',
            'placeholder'   => '--Program of interest--',
            'class'         => '' ,
            'required'      => true,
            'attr'          => (!$campus)? 'disabled' : '',
            'data'          => ApplicationHelpers::getProgramsList(null,$campus),
            'value'         => old('program'),
            ])
@endif
