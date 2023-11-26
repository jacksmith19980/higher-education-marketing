@extends('back.layouts.default-thankyou')

@section('content')
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10 col-md-offset-2">
		<div class="card no-padding">
			<div class="card-header" style="background-color: #2a77a6;">
			    <h1 class="card-title" style="color: #fff; font-family: 'Open Sans Condensed', sans-serif; text-align:center; font-weight: 400;">HEM'S STUDENT APPLICATION PORTAL</h1>
			</div>
			    <div class="card-body">
			        <div class="row">
			        	<div class="col-md-12">

			        		<h1 style="text-align: center; font-weight: 600; letter-spacing: 1.5px"><span><img src="media/images/tick.png" style="width:60px;"/></span>Thank you for registering!</h1>

			        		<!-- <h4 class="" style="text-align:center; color:#4d4d4d; font-weight: 500">Please wait while we set up your school.</h4> -->

			        		<div class="m-t-30" style="text-align: center;">
			        			<img src="media/images/loader.gif" style="margin: auto; width: 80px"/>

			        		</div>

			        		<!-- <h4 class="m-t-30" style="text-align:center; color:#4d4d4d; font-weight: 500">Watch our step-by-step guide video to get familiar witht the basics.</h4> -->
			        		<h4 class="m-t-30" style="text-align:center; color:#4d4d4d; font-weight: 500">Please wait while we create your school.</h4>
			        		<h4 class="m-t-30" style="text-align:center; color:#4d4d4d; font-weight: 500">If you're not redirected in 10 seconds, please <a href='{{ URL::to("/settings") }}'>click here.</a></h4>
			        		<!-- <div class="m-t-30" style="text-align: center;">
			        			<iframe width="600" height="400" src="https://www.youtube.com/embed/8NPrzxtfp2w" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			        		</div> -->

			        	</div>
			        	<div class="col-md-6 col-md-offset-3">

			        	</div>
			        </div>
			    </div>
			</div>
		</div>
		<div class="col-md-1"></div>
	</div>
</div>

<!-- <div class="row">
    <div class="col-md-12">
    </div>
</div> -->

@endsection