
<div  id="MessagesTable" >
    @if (count($messages))
        <table class="table email-table no-wrap table-hover v-middle">
            <tbody>
                @foreach($messages as $message)
                    @include('back.messages.message-list' , [
                        'message'       => $message ,
                        'front'         => isset($front) ? $front : false,
                        'allMessages'   => isset($allMessages) ? $allMessages : false
                        ])
                @endforeach
            </tbody>
        </table>

        @if( get_class($messages) == 'Illuminate\Pagination\LengthAwarePaginator')
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted">
                    {{$messages->total()}} {{Str::plural('message' , $messages->total())}}, {{(int) ceil($messages->total() / $messages->perPage())}} {{__('pages in total')}}
                    </p>
                </div>
                <div>
                    {!! $messages->links() !!}
                </div>
            </div>
        @endif
    @else
        @include('back.students._partials.student-no-results')
    @endif
</div>
