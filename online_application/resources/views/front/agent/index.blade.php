@extends('front.layouts.agents')



@section('content')



	<div class="page-wrapper" style="padding-top: 100px;">

		@if( $agent->is_admin && (empty($agency->phone) || empty($agency->country) || empty($agency->city)) )



			<div class="container-fluid">

				<div class="row">

					@include('front.agent.agency._partials.agency-update')

				</div>

			</div>





		@else

			<div class="container-fluid">

				<div class="row">

					@include('front.agent.dashboard.index')

				</div>

			</div>

		@endif



	</div>

@endsection

