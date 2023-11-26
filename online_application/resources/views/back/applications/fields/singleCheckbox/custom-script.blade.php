<div class="col-md-12">
    <div class="col-md-12">
        @include('back.layouts.core.forms.script',
        [
        'name' => "properties[custom_javascript]",
        'label' => 'Custom javascript' ,
        'class' => 'css-editor ajax-form-field' ,
        'value' => isset( optional($field)->properties['custom_javascript']) ? $field->properties['custom_javascript'] : '',
        'required' => false,
        'helper'  => 'You can use jQuery',
        'attr' => ''
        ])

        <script>
            CodeMirror.fromTextArea( document.getElementById("properties[custom_javascript]") , {
                autofocus: true,
                lineNumbers: true,
                theme: 'material',
                smartIndent: true
            });
        </script>
    </div>
</div>
