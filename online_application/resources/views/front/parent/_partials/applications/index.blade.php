<div class="card">
    <div class="card-body">
        <div class="">
            <div class="d-md-flex align-items-center">
                <div>
                    <h4 class="card-title">{{__('Programs')}}</h4>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table no-wrap v-middle">
                    <thead class="bg-info text-white">
                        <tr class="border-0">
                            <th class="border-0">{{__('Program')}}</th>
                            <th class="border-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schoolApplications as $schoolApplication)
                        <tr>
                            <td>
                                <p><strong>{{$schoolApplication->title}}</strong></p>
                                <small class="d-block">{!! $schoolApplication->description !!}</small>
                            </td>
                            <td>

                                @if ($schoolApplication->quotation)
                                <a href="{{route('quotations.show' , ['quotation' => $schoolApplication->quotation , 'school' => $school])}}" class="btn btn-success">
                                    {{__('Get Price')}}
                                </a>
                                @else

                                <button type="button" data-application="{{$schoolApplication->id}}" class="btn btn-success" onclick="app.addStudent('{{route('school.parent.child.create' , $school)}}' , ' ' , 'Book for a child' , this )"><i class="fa fa-plus"></i> {{__('Book for a child')}}</button>

                                @endif

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