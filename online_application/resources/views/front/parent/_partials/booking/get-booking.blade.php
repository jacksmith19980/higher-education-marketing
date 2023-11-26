<div class="card">
    <div class="card-body">
        <div class="">
            <div class="table-responsive">

                <table class="table no-wrap v-middle">

                    <thead class="bg-info text-white">

                        <tr class="border-0">

                            <th class="border-0">{{__('Booking')}}</th>
                            <th class="border-0"></th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($schoolQuotations as $schoolQuotation)
                            
                            <tr>
                                <td>
                                    <a href="{{route('quotation.show' , ['quotation' => $schoolQuotation , 'school' => $school])}}">{{$schoolQuotation->title}}</a>
                                    <small class="d-block">{!! $schoolQuotation->description !!}</small>
                                </td>

                                <td>
                                    <a href="{{route('quotation.show' , ['quotation' => $schoolQuotation , 'school' => $school])}}" class="btn btn-sm btn-success">
                                       {{__('Get a price')}}
                                    </a>
                                </td>
                            </tr>   
                        

                        @endforeach 

                    </tbody>

                    <tfoot>

                        <tr>

                            <td colspan="4">

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