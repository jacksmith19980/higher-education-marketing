@extends('front.layouts.auth')
@section('content')

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center
            auth_{{$school->slug}}" style="{{ (isset($settings['auth']['background'])) ? 'background:url('.Storage::disk('s3')->temporaryUrl($settings['auth']['background']['path'], \Carbon\Carbon::now()->addMinutes(5)).') no-repeat center center' : ''}} ; background-size: cover;position:relative;padding:5vh 0;min-height:100vh;">

            <div class="container lg-container">
                <div class="reg-container">
                    <div class="row no-gutters">
                        <!-- column for extra content-->
                        <div class="col-sm-12 col-md-12 col-lg-7 first-col">
                            <div class="content-container pt-4 pt-md-5 pb-0 px-4 px-md-5 h-100">

                                @if(isset($settings['auth']['login_title_text']))
                                    {!! $settings['auth']['login_title_text'] !!}
                                @endif
                                @if(isset($settings['auth']['login_read_more_text']))
                                <div style="display: block">
                                  <a class="btn show-more data-togler mt-4 mb-3"
                                  onclick = "app.toggleReadMore(this)"
                                  href="javascript:void(0)"  role="button">
                                          <span class="btn-wrapper">
                                              {{__('Learn more')}}
                                              <i class="arrow-down  btn-icon"></i>
                                          </span>
                                  </a>


                                  <div class="toggle-body">
                                    {!! $settings['auth']['login_read_more_text'] !!}
                                    <div class="d-flex justify-content-end p-0">
                                        <a
                                        onclick = "app.toggleReadMore(this)"
                                        href="javascript:void(0)"   role="button"
                                        class="data-togler is-span show-less"
                                        >
                                            <span class="btn-wrapper">
                                                <i class="arrow-up btn-icon"></i>{{__('Read less')}}
                                            </span>
                                        </a></div>
                                  </div>
                                </div>
                                @endif
                                @if(isset($settings['auth']['login_background']['path']))
                                  <img src="{{Storage::disk('s3')->temporaryUrl($settings['auth']['login_background']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="" srcset="" class="toogle-img">
                                @endif
                            </div>
                            <div class="bk-filter"></div>
                        </div>
                        <!-- End column for extra content-->
                        <!-- column for Form content-->
                        <div class="col-sm-12 col-md-12 col-lg-5">
                            <div class="form-container p-5 h-100">
                                <div class="form-header d-flex">
                                    @if (isset($settings['branding']['logo']))
                                        <img src="{{Storage::disk('s3')->temporaryUrl($settings['branding']['logo']['path'], \Carbon\Carbon::now()->addMinutes(5))}}" alt="logo" class="img-fluid mb-5 full-width"/>
                                    @else
                                        <h3>{{$school->name}}</h3>
                                    @endif
                                </div>
                                <div class="form-content">
                                    @include('front.auth._partials.login-form')

                                </div>
                            </div>

                        </div>
                        <!-- End column for Form content-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
