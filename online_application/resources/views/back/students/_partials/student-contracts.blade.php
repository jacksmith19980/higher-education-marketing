<div class="p-4 tab-pane fade l-psuedu-border bg-grey-1" id="nav-contracts" role="tabpanel" aria-labelledby="pills-contracts-tab">

    <div class="row">
        <div class="text-right m-b-20 col-md-2 offset-10">
            
            <button type="button"
            class="btn btn-primary"
            onclick="app.addTemplateToEnvelope('{{route('envelope.send' , ['student' => $student])}}' , '' , '{{__('Send Envelope')}}' , this)">
                <i class="fa fa-plus"></i> {{__('Send Envelope')}}
            </button>
            
            {{--
            <button data-name="dropdown-toggle" id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fa fa-paper-plane"></i> {{__('Send')}}
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item btn btn-success" onclick="app.sendEnvelope('{{route('envelope.send' , ['student' => $student])}}' , '' , '{{__('Send Envelope')}}' , this)" href="javascript:;">{{__('Send Envelope')}}</a>
                <a class="dropdown-item btn btn-success" onclick="app.showShareDocument('{{route('document.share.show', ['student' => $student])}}' , '' , '{{__('Share document')}}' , this, '{{ route('document.share') }}')" href="javascript:;">{{__('Share document')}}</a>
            </div>
            --}}
        </div>
    </div>

    @if (count($contracts))
        <div class="">
            <table class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
            <thead>
                <tr>
                    <th>{{__('Envelope')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Date')}}</th>
                    <th style="width:40px;">{{__('Action')}}</th>
                </tr>
            </thead>

        @foreach ($contracts as $contract)

            @include('back.students.esignature.' .$contract->service . '.details' , ['contract' => $contract])

        @endforeach
            </table>
        </div>
    @else
        @include('back.students._partials.student-no-results')
    @endif
</div>
