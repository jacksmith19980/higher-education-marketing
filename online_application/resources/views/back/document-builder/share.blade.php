<div class="row">
    <div class="col-12">
        @include('back.layouts.core.forms.select',
        [
        'name' => 'document',
        'label' => 'Document' ,
        'class' => '' ,
        'required' => true,
        'placeholder' => __('Select Document'),
        'attr' => '',
        'data' => $documents,
        'value' => ''
        ])
    </div>
</div>
<input type="hidden" id="student" name="student" value="{{ $student->id }}">