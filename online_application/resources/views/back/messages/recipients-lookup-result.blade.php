
@if($recipients && $recipients->count())

    @foreach($recipients as $recipient)
    <div class="p-3 lookup-item" onclick="app.lookUpSelected(this,'{{json_encode($recipient)}}','{{$recipient->id}}','{{$recipient->name}}')">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-row align-items-center">
            <img src="{{$recipient->avatar}}" alt="{{$recipient->name}}" class="rounded-circle mr-3" width="30">
            <div class="d-flex flex-column">
                <span>{{$recipient->name}}</span>
                <div class="d-flex flex-row align-items-center time-text">
                <small>{{$recipient->email}}</small>
                {{--  <span class="dots"></span> --}}
                </div>

            </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="p-3 lookup-item">
        <div class="d-flex flex-row align-items-center">
            <span class="star">
                <i class="fa fa-times text-danger"></i>
            </span>
            <div>
                {{__('No results found!, Please try again')}}
            </div>
    </div>
@endif
