<tr>

    <td>

        <div class="d-flex no-block align-items-center">

            <div class="m-r-10">
                <img src="{{$admission->avatar}}" alt="user" class="rounded-circle" width="45">
            </div>

            <div class="">

                <h5 class="m-b-0 font-16 font-medium">{{$admission->name}}</h5><span>{{$admission->email}}</span>

            </div>

        </div>

    </td>

    <td>{{$admission->timezone}}</td>

    <td>

        <a href="javascript:void(0)"
            data-id="{{$admission->id}}"
            data-prop="available"
            data-model="admission"
            data-controller="admissions"
            onclick="app.toggleStatus(this)">

            @if ($admission->available)
                    <i class="fa fa-circle text-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>
            @else
                    <i class="fa fa-circle text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Inactive"></i>
            @endif

        </a>

        </i>

    </td>



</tr>
