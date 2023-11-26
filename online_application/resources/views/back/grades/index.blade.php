@extends('back.layouts.default')

@section('content')

    <div class="row justify-content-center">

        <div class="col-12">
            <div class="card" style="padding-right: 20px;">
                <div class="card-body">
    
                    <div class="float-left">
                        <h4 class="page-title">{{__('Grades')}}</h4>
                    </div>
    
                    <div class="float-right btn-group">
                        <a href="{{route('grades.create')}}" class="btn btn-secondary add_new_btn text-light">{{__('Add New')}}</a>
                    </div>
    
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card hasShadow" style="box-shadow: rgb(15 15 15 / 15%) 0px 10px 10px 1px; padding-top: 20px">
                <div class="card-body" id="table-card" style="padding: 15px!important;padding-bottom: 30px!important;">
                    <div class="row mt-2">
                        <div class="col-md-1" style="padding-left: 24px;">
                            <div class="form-group" style="width: 80px">
                                <select class="form-control" id="length_menu" style="cursor: pointer;">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="150">150</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 custom-search-bar">
                            <div class="form-group" style="padding-left: 12px;">
                                <input type="text" class="form-control searchicon" id="search_box" placeholder="Search">
                            </div>
                        </div>
                        <div class="col-md-5" style="padding-right: 24px;" style="width: 100%;">
                            <div class="btn-group" style="width: 100%;">
                                <select id="course_program_selector" style="cursor: pointer; height: 36px!important; line-height: 36px!important; width: 100%!important; color: gray; border-color: lightgray; padding-left: 12px;">
                                    <option style="color: gray;" value="all" selected disabled> Select Course Or Proram</option>
                                    <option style="color: gray;" value="" > All</option>
                                        @foreach($gradesCoursesAndPrograms as $value)
                                            <option style="color: gray;" value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                  </select>
                            </div>
                        </div>
                    </div>
                    <table id="grades_list" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
                        <thead>
                            <tr>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Category')}}</th>
                                <th>{{__('Linked to')}}</th>
                                <th>{{__('Course | Program Title')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @if ($grades)
                                @foreach ($grades as $grade)
                                    <tr data-grade-id="{{$grade->id}}">
                    
                                        <td>
                                            <span style="font-size: 14px">
                                                {{$grade->title}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 12px" class="badge badge-secondary">
                                                {{$grade->category->name}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 12px" class="badge badge-primary">
                                                {{(isset($grade->course)) ? 'Course' : 'Program'}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 12px" >
                                                {{(isset($grade->course)) ? $grade->course->title : $grade->program->title}}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                            $buttons = [
                                                ["text"=>"Edit", "icon"=>"icon-pencil", "class"=>"", "url"=>"javascript:edit_grades($grade->id);"],
                                            ]
                                            @endphp
                                            <div class="btn-group more-optn-group float-right">
                                                <button type="button" 
                                                class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    
                                                <div class="dropdown-menu">
                                                    @foreach ($buttons as $button)
                                                            <a class="dropdown-item {{$button['class']}}" href="{{$button['url']}}">
                                                                <i class="{{$button['icon']}}"></i> <span  class="icon-text">{!!__($button['text'])!!}</span>
                                                            </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        
    </div>

@endsection

@section('scripts')
<script>
    $('#grades_list').DataTable({
        "searching": true,
        "info": false,
        "lengthChange": false,
        "sDom": 'lrtip',
        "pageLength": 10,
        "columnDefs": [
            {
                "targets": 4,
                "orderable": false
            }
        ],
    });
    $(document).ready(function() {
        $('#length_menu').change(function(){
            var l = $('#length_menu option:selected').val();
            var t = $('#grades_list').DataTable();
            t.page.len(l).draw();
        });
        $('#search_box').on('keyup', function () {
            var t = $('#grades_list').DataTable();
            t.search($('#search_box').val()).draw();
        });
        $('#course_program_selector').on('change', function() {
            var t = $('#grades_list').DataTable();
            t.column(3).search(this.value).draw();
        });
    });
</script>
<script>
    
    function edit_grades(grade_id) {
        url_edit_grade = '{{route('grades.edit', ['grade' => 'grade_id'])}}';
        url_edit_grade = url_edit_grade.replace('grade_id', grade_id);
        window.open(url_edit_grade, '_self');
    }
</script>
@endsection