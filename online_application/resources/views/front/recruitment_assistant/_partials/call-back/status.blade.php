@if ($status == 'pending')
    <div>
        <div class="d-block text-center call-status">
            <strong>Pending</strong>
        </div>

        {{--  <p class="text-center">
            <strong>
                Please, Hold Tight! {{$admission->name}} will call you now!
            </strong>
        </p>  --}}
    </div>
@endif

@if ($status == 'scheduled')
    <div>
        <p class="text-center">
            <strong>
                Sorry, none of our advisor is avaialable right now,
                    <br>
                We will contact you shortly
            </strong>
        </p>
    </div>
@endif
