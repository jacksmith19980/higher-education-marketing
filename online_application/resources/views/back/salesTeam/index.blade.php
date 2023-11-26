@extends('back.layouts.default')

@section('content')

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sec-app-dashboard">
                        <div class="app-dashboard-container p-4 mb-4 box-shadow ">
                            <div class="app-dashboard-header">
                                <h3 class="text-center" style="color: #666">{{__('Contact Your Sales Representative')}}</h3>
                                <br>
                                <div class="row">
                                    <div class="col">
                                    </div>

                                    <div class="col" style="text-align: center">
                                        <img src="{{ asset('media/images/sales/Scott.png') }}">
                                        <p class="text-center">
					        <br>Scott Cross<br>Regional Manager, North America<br>
						<a href="mailto:scross@higher-education-marketing.com"> scross@higher-education-marketing.com</a><br>
						<a href="tel:515-312-9048">(515) 312-9048</a>
					</p>
                                        <p class="text-center"><a target="_blank" href="https://meetings.hubspot.com/scross/" class="btn btn-primary rounded-pill">CONTACT SCOTT CROSS</a></p>
                                    </div>
                                    <div class="col">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
