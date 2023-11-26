<a class="btn btn-danger" href="#"
                onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                <i class="fa fa-power-off m-r-5 m-l-5"></i>{{ __('Return to your account') }} </a>

<form id="logout-form" action="{{ route('school.parent.child.destroy' , $school ) }}" method="POST" style="display: none;">
    @csrf
    @method('delete')
</form>