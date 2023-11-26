<!DOCTYPE html>

<html dir="ltr" lang="en" class="saplp">

<head>
   @include('back.layouts._partials.header')
</head>

<body class="form-pages">

    <header class="main-header">
    <div class="navbrand pt-4 pb-3">
        <div class="container has-border">
            <div class="row align-items-center">
                <div class="col-6">
                    <img src="../media/images/HEM_SP.svg" class="is-logo" alt="" srcset="">
                </div>
                <div class="col-6">
                    <div class="text-right header-top-right" style="text-transform: uppercase; color: #fff">Everything you need to<br /><span style="color:#00AFF0;">Convert More Students Online</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-wrapper d-flex ">
        <div class="svg-overlay"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 650.54"
                                      preserveAspectRatio="none">
                <path d="M463.89,586C284.05,658.19,80.06,575.64,0,401.47L.43,650.54H1920L1918.88,0Z"
                      transform="translate(0 0)" fill="#fff"></path>
            </svg></div>
        <div class="header-container container">
            <div class="header-content-wrapper row align-items-start">
                <div class="header-content for-login col-md-12 col-lg-7 pb-3 pb-md-5">
                    <h3 class="header-sub-title">WELCOME TO</h3>
                    <h1 class="header-title">HEM's Student Portal</h1>
                    <h4 class="header-description">Smarter SIS and Admissions Management For Your School</h4>
                    <img src="../media/images/laptop_ready1b.png" alt="" srcset="" class="header-content-img d-block">

                </div>
                @yield('content')
            </div>
        </div>
    </div>
</header>

    <main>
        <div class="main-container container">
            <div class="row main-content-wrapper">
                <div class="row">
                    <div class="col-md-12 col-lg-7 pb-3 pb-md-5">

                    </div>
                    <div class="col-md-12 col-lg-5 pl-lg-5"></div>
                </div>
            </div>
        </div>

    </main>

    <footer class="main-footer">
        <div class="footer-wrapper py-3">
            <div class="container text-center">
                <p class="mb-0"><a href="https://www.higher-education-marketing.com/privacy-policy" target="_blank">Privacy Policy</a> | <a href="https://www.higher-education-marketing.com/data-protection-policy" target="_blank">Data Protection Policy</a></p>
                <p class="mb-0">© 2022 Higher Education Marketing | All Rights Reserved</p>
            </div>
        </div>
    </footer>

    @include('back.layouts._partials.scripts')

    <script>

    $('[data-toggle="tooltip "]').tooltip();

    $(".preloader ").fadeOut();

    </script>

</body>



</html>
