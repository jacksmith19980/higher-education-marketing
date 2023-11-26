<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Tell the browser to be responsive to screen width -->

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="{{isset($settings['branding']['description']) && !empty($settings['branding']['description']) ? $settings['branding']['description'] : $school->name}}">

<meta name="author" content="">

<meta name="keywords" content="{{isset($settings['branding']['keywords']) && !empty($settings['branding']['keywords']) ? $settings['branding']['keywords'] : $school->name}}">

<!-- Favicon icon -->

<link rel="icon" type="image/png" sizes="16x16" href="{{ SchoolHelper::renderFavIcon( optional( request()->tenant()) , $settings ) }}">

<title>{{isset($settings['branding']['title']) && !empty($settings['branding']['title']) ?  $settings['branding']['title']." | " : $school->name}} {{$application->title}} | {{$school->name}}</title>


<!-- file Upload -->
<link href="{{ asset('media/css/fileupload.css') }}" rel="stylesheet">

<!-- Select 2 -->
<link href="{{ asset('media/libs/select2/dist/css/select2.min.css') }}" rel="stylesheet">

<!-- Stepped Form -->
<link href="{{ asset('media/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet">


<!-- Date Time Picker -->
{{-- <link href="{{ asset('media/libs/pickadate/lib/themes/default.css') }}" rel="stylesheet">
<link href="{{ asset('media/libs/pickadate/lib/themes/default.date.css') }}" rel="stylesheet">
<link href="{{ asset('media/libs/pickadate/lib/themes/default.time.css') }}" rel="stylesheet">
 --}}

<link href="{{ asset('media/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">


@include('front.layouts._partials.rounded.custom-css' , ['properties' => $application->properties])


<link href="{{ asset('media/extra-libs/x-editable/css/x-editable.css') }}" rel="stylesheet">



<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

<![endif]-->
