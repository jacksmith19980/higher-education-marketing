<div class="col-12">
    @if(isset($submission))
    <div class="review-page"
        data-route="{{route('submissions.review', [
            'submission' => $submission,
            'application' => $application,
            'school' => $school
            ])}}"
        >
        <center>
            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        </center>
    </div>
    @endif
</div>
