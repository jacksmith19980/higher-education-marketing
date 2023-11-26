<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Tell the browser to be responsive to screen width -->

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="{{isset($settings['branding']['description']) && !empty($settings['branding']['description']) ? $settings['branding']['description'] : $school->name}}">

<meta name="author" content="">

<meta name="keywords" content="{{isset($settings['branding']['keywords']) && !empty($settings['branding']['keywords']) ? $settings['branding']['keywords'] : $school->name}}">

<meta name="_token" content="{!! csrf_token() !!}" />

<!-- Favicon icon -->

<link rel="icon" type="image/png" sizes="16x16" href="{{ SchoolHelper::renderFavIcon( optional( request()->tenant()) , $settings ) }}">



{{isset($settings['branding']['title']) && !empty($settings['branding']['title']) ?  $settings['branding']['title'] : $school->name}} @yield('page-title')


<link href="{{ asset('media/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet">
<link href="{{ asset('media/libs/jquery-steps/steps.css') }}" rel="stylesheet">

<!-- Drop Zone -->
<link href="{{ asset('media/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/select2/dist/css/select2.min.css') }}" rel="stylesheet">

<!-- Custom CSS -->
<link href="{{ asset('media/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('media/css/app.css') }}" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

<![endif]-->

<style type="text/css">

	.text-link , .text-link:hover , .text-link:active{
		color: {{(isset($settings['branding']['links_color'])) ? $settings['branding']['links_color'] : '#2c77a7'}}
	}

	.on-sidebar{
		color: {{(isset($settings['auth']['text_color'])) ? $settings['auth']['text_color'] : '#000'}} !important;
		background-color: {{(isset($settings['auth']['background_color'])) ? $settings['auth']['background_color'] : '#FFF'}} !important;
	}

</style>

<script type="text/javascript">window.ajaxRoute = "{{route('ajax')}}";</script>

<script type="text/javascript">window.uploaderUrl = "{{route('upload')}}";</script>
