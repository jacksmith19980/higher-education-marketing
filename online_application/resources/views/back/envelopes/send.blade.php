<div class="row">
    <div class="{{ count($submissions) ? 'col-6' : 'col-12' }}">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'envelope',
        'label' => 'Envelope' ,
        'class' => '' ,
        'required' => true,
        'placeholder' => __('Select Envelope'),
        'attr' => 'onchange=app.showEnvelopeActions(this)',
        'data' => $envelopes,
        'value' => ''
        ])
    </div>
    @if (count($submissions))
    <div class="col-6">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'submission',
        'label' => 'Submission' ,
        'class' => '' ,
        'required' => false,
        'placeholder' => __('Select student\'s application submission'),
        'attr' => 'onchange=app.showEnvelopeActions(this)',
        'data' => $submissions,
        'value' => '',
        'helper_text' => __('If you want to assign this envelope to a specific application submission')
        ])
    </div>
    @endif
    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'student_id',
    'label' => 'Student' ,
    'class' => '' ,
    'required' => true,
    'attr' => '',
    'value' => $student->id
    ])

    @include('back.layouts.core.forms.hidden-input',
    [
    'name' => 'redirect_url',
    'label' => 'Redirect URL' ,
    'class' => '' ,
    'required' => true,
    'attr' => '',
    'value' => '/students/' . $student->id
    ])
</div>

<div id="signersList" class="row"></div>

<div class="row"></div>

<div class="hidden text-right col-4 offset-8" id="envelopeActions">

    <a href="javascript:void(0)" onclick="app.reviewSendEnvelope(this , true)" class="mr-2 btn btn-default small-btn"
        data-modal-title="{{__('Review & Send Envelope')}}" data-toggle="tooltip" data-placement="top"
        title="{{__('Review & Send Envelope')}}">
        <i class="ti-pencil-alt"></i> {{__('Review & Send Envelope')}}
    </a>

</div>

</div>
