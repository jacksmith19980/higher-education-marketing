
<div class="col-12" style="padding-right: 0px;">
    <div class="card" style="padding-right: 0px;">
        <div>

            <div class="float-right btn-group">
                <a href="{{route('create.program.grades', $program->id)}}" class="btn btn-secondary add_new_btn text-light">{{__('Add New Grade')}}</a>
            </div>

        </div>
    </div>
</div>

@if(count($programGrades))

<table id="course_grades_list" class="table responsiveDataTable display new-table nowrap w-100 responsive dataTable">
    <thead>
        <tr>
            <th>{{__('Title')}}</th>
            <th>{{__('Category')}}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
            @foreach ($programGrades as $grade)
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
                        @php
                        $buttons = [
                            ["text"=>"Edit", "icon"=>"icon-pencil", "class"=>"", "url"=>"javascript:edit_grades($grade->id);"],
                        ]
                        @endphp
                        <div class="btn-group more-optn-group float-right">
                            <button type="button"
                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
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
    </tbody>
</table>

@else

<div class="alert alert-warning">
    <strong>
        {{__('No Results Found')}}
    </strong>
    <span class="d-block">
        {{__('there are none data to show!')}}
    </span>
</div>

@endif
