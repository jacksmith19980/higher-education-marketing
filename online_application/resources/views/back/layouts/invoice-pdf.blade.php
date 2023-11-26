<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{isset($settings['branding']['description']) && !empty($settings['branding']['description']) ? $settings['branding']['description'] : $school->name}}">
    <meta name="author" content="">
    <meta name="keywords" content="{{isset($settings['branding']['keywords']) && !empty($settings['branding']['keywords']) ? $settings['branding']['keywords'] : $school->name}}">
    <title>{{isset($settings['branding']['title']) && !empty($settings['branding']['title']) ? $settings['branding']['title'] : $school->name}} @yield('page-title')</title>
    {{--  <link href="{{ asset('media/css/extra/pdf.css') }}" rel="stylesheet">  --}}

    @include('back.layouts._partials.invoice-style')

</head>



<body>

<div style="width:100%;margin:10px 30px;">


    @yield('content')

</div>
</body>

</html>
