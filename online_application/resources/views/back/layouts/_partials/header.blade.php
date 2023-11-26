<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{isset($settings['branding']['description']) && !empty($settings['branding']['description']) ? $settings['branding']['description'] : (isset($school->name) ? $school->name : 'HEM SP') }}">
<meta name="author" content="">
<meta name="keywords" content="{{isset($settings['branding']['keywords']) && !empty($settings['branding']['keywords']) ? $settings['branding']['keywords'] : (isset($school->name) ? $school->name : 'HEM SP') }}">
<meta name="_token" content="{!! csrf_token() !!}" />

<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2868298,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- Favicon icon 2-->
@if(isset($settings))
<link rel="icon" type="image/png" sizes="16x16" href="{{ SchoolHelper::renderFavIcon( optional( request()->tenant()) , $settings ) }}">
@else
    <link rel="icon" href="https://www.higher-education-marketing.com/wp-content/uploads/2021/08/favicon-120x120.png" sizes="32x32" />
@endif

@if(Route::currentRouteName() != 'home' && Route::currentRouteName() != 'schools.index')
<title>{{isset($settings['branding']['title']) && !empty($settings['branding']['title']) ?  $settings['branding']['title']." | HEM-SP" : (isset($school->name) ? $school->name." | HEM-SP" : 'HEM SP')}} @yield('page-title')</title>
@else
<title>HEM-SP</title>
@endif

<link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,600&display=swap"
        rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,600&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&display=swap"
        rel="stylesheet">

<style>
    :root {
        --color-white:#fff;
        --color-gray:#f4f5f7;
        --color-gray2:#e7ecf0;
        --color-pblue:#0f4264;
        --color-lblue:#00aff0;
        --color-dark-default:#555;
        --color-dark-minus2:#777;
        --color-darked:#222;
        --color-dark:#353535;
        --font-header:"Ubuntu", sans-serif;
        --font-body:"Open Sans", sans-serif;
        --box-shadow:0 0 10px 7px rgba(111, 111, 111,0.1);
    }

    body.form-pages, html.form-pages {
        overflow-x: hidden;
        font-family: "Open Sans", sans-serif;
        font-size: 16px;
        line-height: 180% !important;
        font-weight: 400;
        color: #555;
    }
    body.form-pages *, html.form-pages * {
        box-sizing: border-box;
    }
    @media screen and (max-width: 767px) {
        body.form-pages, html.form-pages {
            font-size: 14px;
        }
    }

    .header-content-img.d-block {
        width: 90%
    }

    @media screen and (max-width:991px){
        .header-content-img.d-block {
            margin: 0 auto;
        }
    }

    .header-top-right {
        font-size: 1em;
    }

    .form-pages h1, .form-pages h2, .form-pages h3, .form-pages h4 {
        font-family: "Ubuntu", sans-serif !important;
        line-height: 120% !important;
    }
    .form-pages h1, .form-pages .header-title {
        font-size: 2.75rem;
        margin-bottom: 2rem;
        letter-spacing: 1px;
        line-height: 110% !important;
    }

    .header-sub-title {
        font-size: 1.8rem;
        font-family: "Open Sans", sans-serif !important;
        font-weight: 300 !important;
        letter-spacing: 1px;
        margin-bottom: 1.5rem;
    }

    .header-text {
        font-size: 1.1rem;
        font-weight: 400 !important;
    }

    .main-header {
        background: #0f4262;
        background: linear-gradient(180deg, #052638 0%, #07324a 3%, #0a4465 34%, #146288 63%, #238dba 92%, #2eade0 100%);
    }

    .header-wrapper {
        position: relative;
        min-height: 30vh;
        padding-top: calc(2rem);
        display: flex;
        align-items: flex-end;
        box-sizing: border-box;
    }
    .header-wrapper .svg-overlay {
        position: absolute;
        bottom: 0;
        width: 100%;
        left: 0;
        z-index: 10;
        height: calc(20% + 12vh + 12vw);
        margin-bottom: -2px;
    }
    .header-wrapper .svg-overlay svg {
        display: block;
        width: calc(100% + 2px);
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
        min-width: 700px;
    }

    .header-container {
        z-index: 11;
        position: relative;
    }

    .header-content {
        padding-right: 15px;
    }
    .header-content .header-title {
        color: #fff !important;
    }
    .header-content .header-sub-title {
        color: #00aff0 !important;
    }
    .header-content .header-text {
        color: #fff !important;
    }
    @media screen and (min-width: 992px) {
        .header-content {
            padding-right: calc(2vw + 2vh + 2rem);
        }
    }

    .header-img img {
        display: block;
        width: 100%;
        height: auto;
        border-radius: 20px;
    }
    @media screen and (min-width: 992px) {
        .header-img img {
            margin-bottom: calc(-2vh - 2vw - 1rem);
        }
    }

    .navbrand .has-border {
        border-bottom: 1px solid rgba(255, 255, 255, 0.8);
        padding-bottom: 15px;
    }
    .navbrand .is-logo {
        height: 80px;
        width: auto;
        display: block;
    }
    @media screen and (max-width: 767px) {
        .navbrand .is-logo {
            height: 50px;
        }
    }
    .navbrand h3, .navbrand h4 {
        color: #fff;
        font-weight: 400 !important;
        font-family: "Open Sans", sans-serif !important;
        font-size: 11px;
        line-height: 130% !important;
        letter-spacing: 1px;
    }

    .form-pages .form-wrapper {
        position: relative;
        border: 1px solid rgba(111, 111, 111, 0.1);
        overflow: hidden;
        margin-bottom: 4rem;
        background: #fafafa;
        border-radius: 0 0 20px 20px;
    }
    .form-pages .form-wrapper .siderbar-from {
        padding: 2rem 2.5rem;
        line-height: 110% !important;
        background: #fafafa;
    }
    .form-pages .form-wrapper .siderbar-from input, .form-pages .form-wrapper .siderbar-from text-area, .form-pages .form-wrapper .siderbar-from select {
        font-size: 14px !important;
        padding: 10px 15px;
        max-width: 100%;
    }
    .form-pages .form-wrapper .siderbar-from input :-ms-input-placeholder, .form-pages .form-wrapper .siderbar-from text-area :-ms-input-placeholder, .form-pages .form-wrapper .siderbar-from select :-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        font-size: 14px !important;
        line-height: 110% !important;
    }
    .form-pages .form-wrapper .siderbar-from input ::-moz-placeholder, .form-pages .form-wrapper .siderbar-from text-area ::-moz-placeholder, .form-pages .form-wrapper .siderbar-from select ::-moz-placeholder {
        font-size: 14px !important;
        line-height: 110% !important;
    }
    .form-pages .form-wrapper .siderbar-from input ::placeholder, .form-pages .form-wrapper .siderbar-from text-area ::placeholder, .form-pages .form-wrapper .siderbar-from select ::placeholder {
        font-size: 14px !important;
        line-height: 110% !important;
    }
    .form-pages .form-wrapper .siderbar-from p {
        font-size: 12px;
        line-height: 120%;
        color: #555 !important;
    }
    .form-pages .form-wrapper .sidebarform-header {
        position: relative;
        padding: 30px 30px 50px 30px;
        box-sizing: border-box;
        background: #0f4262;
        background: linear-gradient(180deg, #052638 0%, #07324a 3%, #0a4465 34%, #146288 63%, #238dba 92%, #2eade0 100%);
        color: #fff;
        padding-right: 30% !important;
    }
    .form-pages .form-wrapper .sidebarform-header .false-nav {
        position: absolute;
        right: 30px;
        top: 50px;
        height: 10px;
        width: 45px;
        border-radius: 5px;
        background: #00aff0;
    }
    .form-pages .form-wrapper .sidebarform-header .svg-form-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        margin-left: -1px;
        width: calc(100% + 2px);
        height: calc(100px);
        margin-bottom: -1px;
    }
    .form-pages .form-wrapper .sidebarform-header .svg-form-overlay svg {
        display: block;
        width: calc(100% + 2px);
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .form-pages .form-wrapper .wsf-title h4 {
        font-size: 3rem;
    }
    .form-pages .form-wrapper .wsf-title p {
        line-height: 140%;
    }
    .form-pages .form-wrapper .custom-control-label {
        line-height: 130%;
        font-size: 14px;
    }
    .form-pages .form-wrapper .btn {
        font-size: 1.25rem !important;
        line-height: 110% !important;
        padding: 0.75rem 1.25rem !important;
        border-radius: 1.45rem !important;
        background-color: #00aff0 !important;
        color: #fff !important;
        font-weight: 500;
        font-style: normal;
        min-width: auto;
        border: none !important;
        font-weight: 500;
        letter-spacing: 1px;
        margin: 30px auto !important;
        display: block;
        transition: all 0.4s ease-out;
        display: flex;
        justify-content: center;
        align-items: center;
        text-transform: uppercase;
        font-family: "Ubuntu", sans-serif;
        letter-spacing: 1px;
        border-radius: 25px;
        max-width: 250px;
    }
    .form-pages .form-wrapper .btn svg {
        display: block;
        height: 24px;
        width: auto;
        margin-left: 20px;
    }
    .form-pages .form-wrapper .btn:hover {
        background: #0f4264 !important;
        transition: all 0.4s ease-out;
    }

    .main-footer {
        background: #0f4264;
    }
    .main-footer .flex-footer-logo {
        list-style: none;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-column-gap: 20px;
        margin: 0;
        padding: 0;
    }
    .main-footer .flex-footer-logo li {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .main-footer .flex-footer-logo li img {
        display: block;
        max-width: 100px;
        margin: 0 auto 15px;
        width: 80%;
    }
    .main-footer .flex-footer-logo li span {
        text-align: center;
        display: block;
        color: #fff;
        line-height: 130%;
        font-size: 1.35rem;
        font-weight: 500;
        letter-spacing: 1px;
        font-family: "Open Sans", sans-serif;
    }
    @media screen and (max-width: 767px) {
        .main-footer .flex-footer-logo li span {
            font-size: 13px;
        }
    }

    .flex-footer-partner {
        padding: 0;
        margin: 0;
        list-style: none;
        display: flex;
        justify-content: center;
    }
    @media screen and (min-width: 992px) {
        .flex-footer-partner {
            display: block;
        }
    }
    .flex-footer-partner li img {
        width: 80%;
        max-width: 160px;
        display: block;
        height: auto;
        margin-bottom: 20px;
    }

    .footer-content-wrapper p {
        color: #fff;
    }
    @media screen and (min-width: 992px) {
        .footer-content-wrapper .right-col {
            padding-left: 40px;
            border-left: 1px solid rgba(255, 255, 255, 0.8);
        }
    }

    .sub-footer {
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.8);
        margin-top: 50px;
        font-size: 14px;
        line-height: 125%;
        font-weight: 300;
    }
    .sub-footer .newline {
        display: block;
        margin-top: 5px;
    }

    .checked-list {
        margin: 30px 0;
        list-style: none;
        padding: 20px;
    }
    .checked-list li {
        background: url(../media/images/icons/checked.svg);
        background-size: 20px;
        background-repeat: no-repeat;
        padding-left: 40px;
        margin-bottom: 7px;
        font-size: 1.1rem;
    }

    .form-container {
        background: #f4f5f7;
        border-radius: 14px;
        overflow: hidden;
        box-sizing: border-box;
        box-shadow: 0 0 10px 7px rgba(111, 111, 111,0.1);
        margin-top: 60px;
        max-width: 400px;
        margin: 60px auto -400px;
    }
    @media screen and (min-width: 992px) {
        .form-container {
            max-width: 350px;
            margin: 70px 0 0 auto !important;
        }
        .form-container.reg-form{
            position: absolute;
            right: 0;
        }
    }
    .form-container .form-header {
        background: #e7ecf0;
    }
    .form-container .form-header h2 {
        font-size: 14px;
        color: #676767;
        text-transform: uppercase;
        padding: 1rem 1.5rem;
        text-align: center;
        margin: 0;
    }
    .form-container .form-content {
        padding: 2rem;
    }
    .form-container .form-content input, .form-container .form-content select {
        border: none;
        font-size: 0.8rem;
        font-family: "Open Sans", sans-serif;
        color: #555;
    }
    .form-container .form-content input::-moz-placeholder, .form-container .form-content select::-moz-placeholder {
        font-family: "Open Sans", sans-serif;
        color: #777;
        font-size: 0.8rem;
    }
    .form-container .form-content input:-ms-input-placeholder, .form-container .form-content select:-ms-input-placeholder {
        font-family: "Open Sans", sans-serif;
        color: #777;
        font-size: 0.8rem;
    }
    .form-container .form-content input::placeholder, .form-container .form-content select::placeholder {
        font-family: "Open Sans", sans-serif;
        color: #777;
        font-size: 0.8rem;
    }
    .form-container .form-content p, .form-container .form-content .form-check-label {
        font-size: 0.8rem;
    }
    .form-container .form-content a {
        text-decoration: none !important;
    }
    .form-container .form-content .form-check-input {
        position: absolute;
        margin-top: 9px;
        margin-left: -1.25rem;
        border: none !important;
        outline: none;
        box-shadow: none !important;
    }
    .form-container .form-content .form-btn {
        display: flex;
        margin: 20px auto 10px;
        align-items: center;
        justify-content: center;
        background: #00aff0;
        border-radius: 19px;
        padding: 5px 16px;
    }
    .form-container .form-content .form-btn .btn-text {
        color: #fff;
        text-transform: uppercase;
        display: block;
        padding: 0 10px 0 0;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1px;
        border-right: 1px solid rgba(255, 255, 255, 0.3);
    }
    .form-container .form-content .form-btn svg {
        height: 18px;
        width: 22px;
        display: block;
        padding: 0 0 0 10px;
    }
    .form-container .form-content .form-btn:hover {
        background-color: #0f4264;
    }
    .form-container .form-footer {
        background: #e7ecf0;
        padding: 5px 20px;
    }
    .form-container .form-footer p {
        margin: 0;
        font-size: 0.8rem;
    }
    .form-container a {
        color: #00aff0;
    }

    .header-content {
        padding-right: 15px;
    }
    .header-content .header-title {
        color: #fff; !important;
        margin-bottom: 10px;
    }
    .header-content .header-sub-title {
        color: #00aff0; !important;
    }
    .header-content .header-text {
        color: #fff; !important;
    }
    .header-content .header-content-img {
        max-width: 450px;
        margin: 40px 0;
    }
    @media screen and (min-width: 992px) {
        .header-content {
            padding-right: calc(2vw + 2vh + 2rem);
        }
        .header-content.for-login .header-content-img {
            margin: 40px 0 -100px 0;
            max-width: 450px;
        }
        .header-content .reg-img.header-content-img {
            margin: 40px 0 -150px 0;
        }
    }
    .header-content .header-description {
        font-weight: 300;
        color: #fff;);
        font-family: "Open Sans", sans-serif !important;
        line-height: 125%;
        margin-bottom: 2rem;
        font-size: 1.5rem;
    }
    @media screen and (min-width: 992px) {
        .header-content .header-description {
            padding-right: calc(2vw + 3rem + 3vh);
        }
    }

    .whip-overlay {
        position: relative;
    }
    .header-wrapper .header-container {
        z-index: 5;
    }
    .header-wrapper .svg-overlay {
        z-index: 1 !important;
    }
    .header-wrapper .svg-overlay {
        height: calc(20% + 12vh + 12vw);
    }

    body.form-pages {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    @media screen and (max-width: 991px) {
        body.form-pages main {
            margin-top: 450px;
        }
    }
    body.form-pages .main-footer {
        margin-top: auto;
        position: relative;
        z-index: 6;
    }
    body.form-pages .header-wrapper:after {
        background-image: url(../media/images/portal-bg.svg);
        position: fixed;
        height: 100vh;
        width: 65%;
        display: block;
        right: 0;
        z-index: 2;
        background-repeat: no-repeat;
        content: "";
        bottom: -200px;
        right: 0;
        background-position: 100% 100%;
        min-height: 1500px;
        min-width: 900px;
    }

    .main-header .header-wrapper {
        z-index: 22;
    }

    .main-footer {
        background: transparent !important;
        z-index: 99 !important;
    }
    .main-footer .footer-wrapper {
        background: #0f4264;
        z-index: 10;
    }
    .main-footer .footer-wrapper p {
        font-size: 0.8rem;
        color: #fff;
        font-weight: 300;
        opacity: 0.8;
    }
    .main-footer .footer-wrapper a {
        font-size: 0.9rem;
        color: #00aff0;
        font-weight: 300;
        opacity: 0.8;
        font-weight: bold;
    }

    .main {
        z-index: 9;
    }/*# sourceMappingURL=app.css.map */
</style>

<!-- Multiselect -->
<link href="{{ asset('media/libs/multiselect/styles/multiselect.css') }}" rel="stylesheet"/>
<script src="{{ asset('media/libs/multiselect/multiselect.min.js') }}"></script>

<!-- Data Table -->
<link href="{{ asset('media/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

{{-- <link href="{{ asset('media/libs/jquery-steps/jquery.steps.css') }}" rel="stylesheet"> --}}

<link href="{{ asset('media/libs/jquery-steps/steps.css') }}" rel="stylesheet">

<!-- Drop Zone -->
<link href="{{ asset('media/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/claviska/jquery-minicolors/jquery.minicolors.css') }}" rel="stylesheet">

{{-- Dragula --}}
<link href="{{ asset('media/extra-libs/prism/prism.css') }}" rel="stylesheet">
<link href="{{ asset('media/libs/dragula/dist/dragula.min.css') }}" rel="stylesheet">

<!-- Draggable Cards -->
<link href="{{ asset('media/libs/dragula/dist/dragula.min.css') }}" rel="stylesheet">
<link href="{{ asset('media/extra-libs/prism/prism.css') }}" rel="stylesheet">

<!-- itour -->
<link href="{{ asset('media/libs/itour/css/itour.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/select2/dist/css/select2.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/code-highlight/default.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('media/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css') }}" rel="stylesheet">

{{-- Code Mirror --}}
<link href="{{ asset('media/extra-libs/codemirror/lib/codemirror.css') }}" rel="stylesheet">
<link href="https://codemirror.net/theme/material.css" rel="stylesheet">

<link href="{{ asset('media/css/quotation/noty.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/daterangepicker/daterangepicker.css') }}" rel="stylesheet">

<!-- Fonts -->
<link href="{{ asset('media/font/flaticon.css') }}" rel="stylesheet">

<link href="{{ asset('media/extra-libs/jodit-3.2.44/build/jodit.min.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css" rel="stylesheet" />

<!-- Include c3min.css for Graph and Flaticon css-->
<link rel="stylesheet" href="{{ asset('media/extra-libs/c3/c3.min.css') }}">
<link href="{{ asset('media/css/fileupload.css') }}" rel="stylesheet">
{{--<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">--}}
<link href="{{ asset('media/extra-libs/x-editable/css/x-editable.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/toastr/build/toastr.min.css') }}" rel="stylesheet">

<link href="{{ asset('media/libs/filepond/css/filepond.css') }}" rel="stylesheet">
<!-- Custom CSS -->

<link href="{{ asset('media/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('media/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('media/css/update.css') }}" rel="stylesheet">
<link href="{{ asset('media/css/new-style.css') }}" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

<![endif]-->
<script type="text/javascript">window.ajaxRoute = "{{route('ajax')}}";</script>
<script type="text/javascript">window.uploaderUrl = "{{route('upload')}}";</script>
