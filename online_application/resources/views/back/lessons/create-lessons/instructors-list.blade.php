
@include('back.layouts.core.forms.select',
[
    'name'          => 'instructor',
    'label'         => 'Instructor',
    'class'         => 'ajax-form-field',
    'required'      => true,
    'attr'          => '' . (count($instructors) == 0) ? " disabled" : " ",
    'data'          => $instructors,
    'placeholder'   => 'Select an Instructor',
    'value'         => ''
])

<script>
    $(".select2").select2();
</script>
