<!DOCTYPE html>

<html dir="ltr">



<head>

   @include('back.layouts._partials.header')
   <style type="text/css">
    /*.bgimg:before{
       content:"";
        position: absolute;
        -webkit-transform: rotate(15deg);
        width: 100%;
        height: 100%;
        background-image: url(media/images/background/lp_bg.png);
    }*/

    .parent {
      position: relative;
      width: 100%;
    }

    .one, .two {
      position: absolute;
      height: 100%;
      width: 100%;
    }


    .one {
      left: 0;
      top: 0;
      background-image: url(media/images/background/lp_bg.png);
      -webkit-clip-path: polygon(150% 100%, 0% 100%, 0% 0%, 0 0%);
      clip-path: polygon(150% 100%, 0% 100%, 0% 0%, 0 0%);
      background-repeat:  no-repeat;
    }
    .two {
      right: 0;
      top: 0;
      left:0;
      background-color: #2a77a6 ;
      /*background-image: url(http://via.placeholder.com/350x150/fc9fc9);*/
      -webkit-clip-path: polygon(0% 0%, 0% 0%, 100% 0%, 100% 100%);
      clip-path: polygon(0% 0%, 0% 0%, 100% 0%, 100% 100%);
    }

    .landing-auth-box{
        background: #fff;
        padding: 20px;
        box-shadow: 1px 0px 20px rgba(0, 0, 0, 0.08);
        max-width: 400px;
        width: 90%;
        top: 50px;
        right: 200px;
        margin: 0px;
        position: absolute;
        border: 5px solid #2a77a6;
    }

    .landing-page-form input, .landing-page-form select, .landing-page-form textarea{
        border-right: none;
        border-left: none;
        border-top: none;

    }


   </style>


</head>



<body>

    <div class="main-wrapper">

        <!-- ============================================================== -->

        <!-- Preloader - style you can find in spinners.css -->

        <!-- ============================================================== -->

        <div class="preloader">

            <div class="lds-ripple">

                <div class="lds-pos"></div>

                <div class="lds-pos"></div>

            </div>

        </div>

        <!-- ============================================================== -->

        <!-- Preloader - style you can find in spinners.css -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Login box.scss -->

        <!-- ============================================================== -->

        <!-- <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(../../assets/images/background/lp_bg.jpg) no-repeat center bottom;"> -->
        <!-- <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(media/images/background/lp_bg.png) no-repeat bottom left;"> -->
            <!-- <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background: url(media/images/background/lp_bg.png) no-repeat bottom left, linear-gradient(#2a77a6, #2a77a6);"> -->
                <!-- <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background: linear-gradient(#2a77a6, #2a77a6);"> -->
                <div class="auth-wrapper d-flex no-block justify-content-center align-items-center parent">
                    <div class="one"></div>
                    <div class="two"></div>

                    <!-- <div class="row">
                      <div class="col-md-3 col-xs-12 col-sm-4">

                      </div>
                      <div class="col-md-9 col-xs-12 col-sm-8">
                          <h2>CUSTOM-BUILT STUDENT APPLICATION PORTAL</h2>
                      </div>
                  </div> -->
                     <div class="logo" style="top:25px; left:120px; position: absolute; background-color: #fff; padding: 15px;">
                        <img src="media/images/hem_logo.png">
                    </div>
                <!-- <div style="background: url(media/images/background/lp_bg.png) no-repeat bottom left; width:100%; height: 100%; -webkit-clip-path: polygon(0 0, 0 100px, 100px 80px, 100px 0); clip-path: polygon(0 0, 0 100%, 100% 80%, 100% 0);" class="bgimg"></div> -->
                 <!-- -webkit-clip-path: polygon(0 0, 0 100px, 100px 80px, 100px 0); clip-path: polygon(0 0, 0 100%, 100% 80%, 100% 0); -->
            <div class="row">

              <div class="col-md-6">
                <div class="blue-box" style="z-index: 999;">
                  <h3>An intuitive booking experience for students, parents & agents</h3>
                  <hr>
                  <h4>Start your 30-day FREE TRIAL of HEM's Application Portal today!</h4>
                  <br/>
                  <p>Build saveable step-by-step applications, create detailed quotations, monitor agent applications and more with our state of the art Student Application Portal</p>

                </div>
              </div>

              <div class="col-md-6">
                <div class="landing-auth-box on-sidebar">

                   @yield('content')

                </div>
              </div>


          </div>

        </div>

        <!-- ============================================================== -->

        <!-- Login box.scss -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Page wrapper scss in scafholding.scss -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Page wrapper scss in scafholding.scss -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Right Sidebar -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Right Sidebar -->

        <!-- ============================================================== -->

    </div>



    @include('back.layouts._partials.scripts')



    <script>

    $('[data-toggle="tooltip "]').tooltip();

    $(".preloader ").fadeOut();

    </script>

</body>



</html>