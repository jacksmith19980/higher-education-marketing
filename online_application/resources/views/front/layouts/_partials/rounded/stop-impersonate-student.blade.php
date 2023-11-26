@php
    if(session('impersonated-by') == 'user'){
        $route =  route('school.user.student.destroy');
    }else{
        $route = route('school.agent.student.destroy' , $school );
    }
@endphp
<a class="btn btn-danger" href="#"
                onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Stop Impersonating') }} ( {{$student->name }} )
            </a>



<form id="logout-form" action="{{ $route }}" method="POST" style="display: none;">
    @csrf
    @method('delete')
</form>
