<header class="topbar">



    <nav class="navbar top-navbar navbar-expand-md navbar-light d-flex justify-content-between px-5">



        <div class="navbar-header">



            <a class="navbar-brand" href="{{ $settings['school']['website'] }}">

                    @if (!isset($settings))

                        @php

                            $settings = [];

                        @endphp

                    @endif

                    {!! SchoolHelper::renderSchoolLogo( optional( request()->tenant()) , $settings ) !!}



            </a>

        </div>





        <div>

                <a class="btn btn-danger waves-effect waves-light  text-uppercase" href="#" onclick="event.preventDefault();
                        document.getElementById('stop-impersonate').submit();">
                        {{__('Return to your account')}}
                </a>
                <form id="stop-impersonate" action="{{route('school.parent.child.destroy' , $school )}}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
               

        </div>





        



    </nav>



</header>

