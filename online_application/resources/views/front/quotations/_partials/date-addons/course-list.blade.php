<!-- loop full-width accordion -->
<div class="program-list-item">
    <div class="program-list-header">
        <div class="flex-container">
            <h3>{{$campus->title}}: {{$course->title}}</h3>
            <span class="fas fa-plus"></span>
        </div>
    </div>
    <div class="program-list-content pt-5 pb-3 px-3 px-md-4 px-lg-5">

        <div class="row justify-content-md-center">
            @foreach ($course->dates()->get() as $date)
                @include('front.quotations._partials.date-addons.dates.' . $date->date_type , [
                    'course' => $course,
                    'date' => $date,
                    ])
            @endforeach
            
        </div>

    </div>
</div>
<!-- end full-width accordion -->