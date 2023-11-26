@if ($students->count())

    @foreach ($students as $student)
        @include('back.students._partials.student' , $student)
    @endforeach

    <tr>
        <td colspan="3">
            <div class="d-flex justify-content-center">
                {{ $students->links() }}
            </div>
        </td>
    </tr>

@else
    <tr>
        <td colspan="3">
            @include('front.agent._partials.no-results')
        </td>
    </tr>
@endif
