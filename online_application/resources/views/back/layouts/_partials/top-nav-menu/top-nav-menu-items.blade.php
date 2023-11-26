<ul class="navbar-nav float-left mr-auto d-none d-md-inline-flex">
    {{--  @tenant  --}}
    <li class="nav-item">
        <a class="nav-link" href="{{route('campuses.index')}}" aria-expanded="false">
            <span class="d-none d-md-block">{{__('Campuses')}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('courses.index')}}" aria-expanded="false">
            <span class="d-none d-md-block">{{__('Courses')}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('programs.index')}}" aria-expanded="false">
            <span class="d-none d-md-block">{{__('Programs')}}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('calendar.index')}}" aria-expanded="false">
            <span class="d-none d-md-block">{{__('Calendar')}}</span>
        </a>
    </li>

    <!-- ============================================================== -->
    <!-- Search -->
    <!-- ============================================================== -->
    {{-- <li class="nav-item search-box">--}}
        {{-- <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>--}}
        {{-- <form class="app-search position-absolute">--}}
            {{-- <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn">
                <i--}} {{-- class="ti-close"></i>
            </a>--}}
            {{-- </form>--}}
        {{-- </li>--}}
    {{--  @endtenant  --}}
</ul>
@features(['sis'])
@nofeatures(['sis'])
<a href="https://landing.higher-education-marketing.com/hem-sp-pricing" target="_blank" class="d-block m-l-10">
    <span class="badge badge-pill m-r-5 text-white"
        style="background-color: #00adee;padding: 5px 20px;font-weight: 700;font-size: 13px">{{__('PRICING')}}
        <i class="m-l-5 fas fa-external-link-alt"></i></span>
</a>

<li class="nav-item dropdown" style="list-style-type:none;">
    <a class="nav-link dropdown-toggle ml-4 mr-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
		 onMouseOver="this.style.color='#333333'">
        <span class="d-none d-md-block">{{__('Need Help?')}}<i class="fa fa-angle-down ml-2"></i></span>
        <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>   
    </a>
	
     <div class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{route('settings.index')}}#itour">Settings Tour</a>
        <a class="dropdown-item" href="{{route('applications.index', ['hash' => 'newapplication'])}}#newapplication">Application Tour</a>
	<a class="dropdown-item" href="{{route('campuses.index')}}#createcampus">Campus Tour</a>
	<a class="dropdown-item" href="{{route('programs.index')}}#createprogram">Programs Tour</a>
	<a class="dropdown-item" href="{{route('courses.index')}}#createcourse">Course Tour</a>
     </div>
</li>
@endfeatures
