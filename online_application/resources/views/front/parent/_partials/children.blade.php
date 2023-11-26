<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-md-flex align-items-center">
                <div>
                    <h4 class="card-title">{{__('Children')}}</h4>
                </div>
                <div class="ml-auto d-flex no-block align-items-center">
                    <div class="dl m-b-15">
                        {{-- <button type="button" class="btn btn-success" onclick="app.addStudent('{{route('school.parent.child.create' , $school)}}' , ' ' , 'Create Childs\'s Account' , this )"><i class="fa fa-plus"></i> {{__('Add Child')}}</button> --}}
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table no-wrap v-middle">
                    <thead class="bg-info text-white">
                        <tr class="border-0">
                            <th class="border-0">{{__('Name')}}</th>
                            <th class="border-0">{{__('Applications')}}</th>
                            <th class="border-0">{{__('Created Date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($children->count())
                        @foreach ($children as $child)
                           
                           @include('front.parent._partials.child' , ['child'=> $child] )

                        @endforeach

                        @else
                        <tr>
                            <td colspan="3">
                                <span>{{ __('You didn\'t add any child yet' )}}</span>
                            </td>
                        </tr>

                        @endif


                       

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <div class="d-flex justify-content-center">
                                    
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>