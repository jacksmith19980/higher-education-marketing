<div id="nav-application" class="tab-pane fade" role="tabpanel" aria-labelledby="nav-application">
@if ( $count = $applicant->submissions()->count() )

    @foreach ($applicant->submissions as $submission)
        {{--  @if(PermissionHelpers::checkActionPermission('submission', 'view', $submission))  --}}
        @include('back.students._partials.student-application' ,
            [
                'show'          => ($count == 1) ? true : false,
                'canView'       => PermissionHelpers::checkActionPermission('submission', 'view', $submission),
                'canEdit'       => PermissionHelpers::checkActionPermission('submission', 'edit', $submission),
                'canDelete'     => PermissionHelpers::checkActionPermission('submission', 'delete', $submission)
            ])
        {{--  @endif  --}}
    @endforeach

    @else
        <div class="tab-pane fade show active" id="nav-application" role="tabpanel" aria-labelledby="nav-application">
            <div class="alert alert-warning">
                <strong>{{__('No Results Found')}}</strong>
                <span class="d-block">{{__('there are none!')}} {{$applicant->name}} {{__('didn\'t submit any application yet!')}}</span>
            </div>
        </div>

    @endif
</div>
