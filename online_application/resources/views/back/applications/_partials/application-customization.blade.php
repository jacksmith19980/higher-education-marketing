@foreach ($customization as $key=>$item)
	<div class="col-md-6">

		@include('back.layouts.core.forms.'.$item['type'],
                        [
                            'name'      => "properties[$key]",

                            'label'     => $item['title'] ,

                            'class'     => (isset($item['class'])) ? $item['class'] : "",

                            'value'     => (isset($applicationCustomization[$key])) ? $applicationCustomization[$key] : $item['default'],

                            'required'  => (isset($item['required'])) ? $item['required'] : "",

                            'default'   => (isset($applicationCustomization[$key])) ? $applicationCustomization[$key] : $item['default'],

                            'helper_text'=>(isset($item['helper_text'])) ? $item['helper_text'] : false,

                            'attr'=> (isset($item['attr'])) ? $item['attr'] : "",

                            'data'=> (isset($item['data'])) ? $item['data'] : "",
						])
	</div>
<script>
//app.colorPicker();
</script>
@endforeach

<div class="col-md-12">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-area',
        [
        'name' => "properties[custom_css]",
        'label' => 'Custom CSS' ,
        'class' => 'css-editor' ,
        'value' => (isset($application->properties['custom_css'])) ? $application->properties['custom_css'] : '',
        'required' => false,
        'attr' => ''
        ])

        <script>
            CodeMirror.fromTextArea( document.getElementById("properties[custom_css]") , {
                autofocus: true,
                lineNumbers: true,
                theme: 'material',
                smartIndent: true
            });
        </script>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-12">
        @include('back.layouts.core.forms.text-area',
        [
        'name' => "properties[custom_js]",
        'label' => 'Custom javascript' ,
        'class' => 'css-editor' ,
        'value' => (isset($application->properties['custom_js'])) ? $application->properties['custom_js'] : '',
        'required' => false,
        'helper'  => 'You can use jQuery',
        'attr' => ''
        ])

        <script>
            CodeMirror.fromTextArea( document.getElementById("properties[custom_js]") , {
                autofocus: true,
                lineNumbers: true,
                theme: 'material',
                smartIndent: true
            });
        </script>
    </div>
</div>
