<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{isset($settings['branding']['description']) && !empty($settings['branding']['description']) ? $settings['branding']['description'] : $school->name}}">
<meta name="author" content="">
<meta name="keywords" content="{{isset($settings['branding']['keywords']) && !empty($settings['branding']['keywords']) ? $settings['branding']['keywords'] : $school->name}}">
<meta name="_token" content="{!! csrf_token() !!}" />

<!-- Favicon icon 2-->
@if(isset($settings))
<link rel="icon" type="image/png" sizes="16x16" href="{{ SchoolHelper::renderFavIcon( optional( request()->tenant()) , $settings ) }}">
@else
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('media/images/favicon.png') }}">
@endif


<title>{{isset($settings['branding']['title']) && !empty($settings['branding']['title']) ?  $settings['branding']['title'] : $school->name}} @yield('page-title')</title>


<link href="{{ asset('media/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/jquery-steps/steps.css') }}" rel="stylesheet">


<link href="{{ asset('media/libs/claviska/jquery-minicolors/jquery.minicolors.css') }}" rel="stylesheet">


<link href="{{ asset('media/libs/select2/dist/css/select2.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/extra-libs/css-chart/css-chart.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">

<!-- Data Table -->
<link href="{{ asset('media/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css" rel="stylesheet" />

<link href="{{ asset('media/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/daterangepicker/daterangepicker.css') }}" rel="stylesheet">

<link href="{{ asset('media/css/fileupload.css') }}" rel="stylesheet">

<!-- Custom CSS -->
<link href="{{ asset('media/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('media/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('media/css/update.css') }}" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

<![endif]-->

<script type="text/javascript">window.agentsAjaxRoute = "{{route('agents.ajax' , $school )}}";</script>

<script type="text/javascript">window.uploaderUrl = "{{route('upload')}}";</script>
