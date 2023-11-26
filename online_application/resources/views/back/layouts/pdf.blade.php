<!DOCTYPE html>

<html dir="ltr" lang="en">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{isset($settings['branding']['description']) && !empty($settings['branding']['description']) ? $settings['branding']['description'] : ((isset($school->name)) ? $school->name : '') }}">
<meta name="author" content="">
<meta name="keywords" content="{{isset($settings['branding']['keywords']) && !empty($settings['branding']['keywords']) ? $settings['branding']['keywords'] : ((isset($school->name)) ? $school->name : '')}}">
<title>{{isset($settings['branding']['title']) && !empty($settings['branding']['title']) ?  $settings['branding']['title']: ((isset($school->name)) ? $school->name : '')}} @yield('page-title')</title>

<style type="text/css">
*{
/*font-size: 70%;*/
font-family: Open Sans , sans;
}
.card {
 position:relative;
 display:block;
 word-wrap:break-word;
 background-clip:border-box;
 border:0 solid transparent;
 border-radius:0 0 5px 5px;
 width: 100%;
 margin-bottom: 15px;
}
.card-header{
    background-color: #2962FF !important;
    color: #FFF;
    border-radius: 5px 5px 0 0;
}
.card-header h4{
    padding:10px;
    margin:0;
}

.card-body{
    padding:10px;
    background-color:#ECECEC;
    font-size: 70%
}
.half{
    width: 49%;
    font-size: 70%;
}
.right{
    float: right;
}
.left{
    float: left;
}
.clear{
    clear: both;
}
.logo{
    margin-bottom:20px;
}
.logo img{
    max-height: 60px;
    max-width: 100px;
}

</style>

</head>



<body>

    <div class="main-wrapper">


                @yield('content')

    </div>
</body>

</html>
