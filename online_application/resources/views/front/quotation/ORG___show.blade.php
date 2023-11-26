@extends('front.layouts.quotation-layout')

@section('content')
<div class="page-wrapper" style="padding-top: 100px; display: block;">
    
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-6 offset-md-3">
                    <div class="card border-bottom border-success">

                        <div class="card-header bg-info">
                            <h4 class="m-b-0 text-white">Get a Price</h4>
                        </div>

                        <div class="card-body">
                           
                            <form action="#" method="POST" class="priceForm">
                                @csrf
                                    <div class="form-group">
                                        @include('back.layouts.core.forms.select',
                                        [
                                            'name'      => 'courses',
                                            'label'     => 'Courses' ,
                                            'class'     => '' ,
                                            'required'  => true,
                                            'attr'      => 'onchange=app.courseSelected(this)',
                                            'value'     => '',
                                            'placeholder' => 'Select Course',
                                            'data'      => QuotationHelpers::getCoursesSelection($settings['quotation']['courses']),
                                        ])
                                    </div>

                                    <div class="CourseDetails"></div>
                            </form>

                        </div>

                        <div class="card-footer">
                            <a href="javascript:void(0)" class="btn btn-lg btn-success float-right GetAPriceButton" onclick="app.getPrice(document.querySelector('form'))" disabled="disabled">GET A PRICE</a>
                        </div>

                    </div>


            </div>				
            
            
        </div>
        <div class="row" id="CoursePrice"></div>

    </div>

            

    
</div>
@endsection

