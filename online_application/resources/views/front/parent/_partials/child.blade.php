<tr>
    <td>
        <div class="d-flex no-block align-items-center">
            <div class="m-r-10"><img src="https://www.gravatar.com/avatar/{{md5($child->email)}}?s=200&r=pg" alt="user" class="rounded-circle" width="45"></div>
            <div class="">
                <h5 class="m-b-0 font-16 font-medium">{{$child->name}}</h5>
                <span>{{$child->email}}</span>
            </div>
        </div>
    </td>
    
    <td>

        @include('front.parent._partials.child-submissions')
        

    </td>
    
    <td class="blue-grey-text  text-darken-4 font-medium">
    
        <span>{{$child->created_at->diffForHumans()}}</span>
    
    </td>
</tr>