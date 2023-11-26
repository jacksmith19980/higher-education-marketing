@if(isset($assistantBuilder->properties[$step . '_help_message']))
<div class="helper">
    <div class="helper-container">

        <span class="helper_dissmis" onclick="app.dissmisHelper()">
            <i class="fa fa-times" aria-hidden="true"></i>
        </span>

        <div class="helper-teaser" onclick="app.showHelp()">
            <h5>{{$assistantBuilder->help_title}}</h5>
            <p>{!! $assistantBuilder->help_content !!}</p>
        </div>

        <div class="helper-content">
            @if (isset($assistantBuilder->properties[$step . '_help_title']))
                <h5>
                    {!! $assistantBuilder->properties[$step . '_help_title'] !!}
                </h5>
            @endif

            <p>
                {!! $assistantBuilder->properties[$step . '_help_message'] !!}
            </p>
        </div>

    </div>

    <div class="helper-header" onclick="app.showHelp()">
        <img src="{{ Storage::disk('s3')->temporaryUrl($assistantBuilder->help_logo['path'], \Carbon\Carbon::now()->addMinutes(5)) }}">
    </div>

</div>
@endif
