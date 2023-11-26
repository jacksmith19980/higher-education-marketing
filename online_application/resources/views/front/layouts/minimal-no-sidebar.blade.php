<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <!-- include (Header) -->
    @include('back.layouts._partials.header')
    <!-- tag Manager -->
    @include('front.layouts._partials.head-tracking')

    <style>
        .strip-payment-btn{
			width:100%;
			padding:20px;
			font-size: 20px;
			font-weight: bold;
			background-color: #17bdb6;
			color:#fff;
			cursor: pointer;
			border-radius: 3px;
		}
		
		/*********************************/
		/* Additional CSS - Paymnet page */
		/*********************************/
		
		.payment-wrapper{
			margin-left:0px !important;
		}
		
		.payment-wrapper> .container-fluid{
			width:80% !important;
		}
		
		.payment-navbar{
			margin-left:0px !important;
			
		}
		
		@media only screen and (max-width: 991px) {
			.card-info-wrapper{
				display: flex;
				flex-direction: column;
			}
			
			.card-info{
				order: 2;
			}
			
			.purchase-summary{
				order: 1;
			}
		}
		
		@media only screen and (max-width: 767px) {
			.payment-wrapper> .container-fluid{
				width:100% !important;
			}
			
			.payment-navbar{
				display: block !important;
				top: 0px !important;
			}
			
			.payment-navbar> .sidebar-link{
				padding:25px !important;
			}
			
			.payment-wrapper .app-dashboard-container{
				padding-top: 1px !important;
			}
			
			.payment-wrapper .payment-form{
				margin-top: 2.4rem !important;
			}
		}
		
    </style>
    @if(isset($application))
        {{-- @include('front.layouts._partials.'.$application->layout.'.header') --}}
        @if (isset($application->properties['custom_css']))
            <style>
                {!! $application->properties['custom_css'] !!}

            </style>
        @endif
    @endif


</head>


<body>
    <!-- tag Manager -->
    @include('front.layouts._partials.body-tracking')

    <!-- include (preloader) -->
    @include('back.layouts._partials.preloader')


    <!-- ============================================================== -->

    <!-- Main wrapper - style you can find in pages.scss -->

    <!-- ============================================================== -->

    <!-- <div class="main-wrapper"> -->
    <div id="main-wrapper">


        <!-- include (TOP NAV NO SIDEBAR) -->
		@include('front.layouts._partials.oiart.top-nav-no-sidebar')

        @yield('content')

    </div>

        @include('front.layouts.core.page-notifications.modals.form')
    <!-- ============================================================== -->

    <!-- End Wrapper -->

    <!-- ============================================================== -->

        <!-- include (Footer) -->

        @include('back.layouts._partials.footer')
        @include('front.applications._partials.page-notification')

        <!-- include (Scripts) -->
        @include('front.layouts._partials.parent.scripts')

        @if(isset($application))
            @if (isset($application->properties['custom_js']))
                <script>
                    {!! $application->properties['custom_js'] !!}
                </script>
            @endif
        @endif

</body>

</html>
