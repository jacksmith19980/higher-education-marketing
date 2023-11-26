@php
$disabled = (!$programs || !count($programs)) ? "disabled" : "";
@endphp
@include('back.layouts.core.forms.select',
    [
        'name'          => 'program',
        'label'         => 'Program' ,
        'class'         => 'ajax-form-field  program-field',
        'required'      => true,
        'attr'          => "onchange=app.getMultipleLessons(this,'groups',$lesson) " . $disabled,
        'data'          => $programs,
        'placeholder'   => 'Select a Program',
        'value'         => ''
])

<script>
    $(".select2").select2();
</script>
