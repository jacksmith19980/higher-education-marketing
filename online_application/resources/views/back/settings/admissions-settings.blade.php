@php
    $disabled = (!$permissions['edit|settings']) ? 'disabled="disabled"' : '';
@endphp
<div class="row">

    @if (isset($admissions) && $admissions->count() )
        <div class="table-responsive">
            <table class="table no-wrap v-middle">
                <thead>
                    <tr class="border-0">
                        <th class="border-0">{{__('Advisor')}}</th>
                        <th class="border-0">{{__('Timezone')}}</th>
                        <th class="border-0">{{__('Status')}}</th>
                    </tr>
                </thead>
                <tbody id="admissions">
                @foreach ($admissions as $admission)
                    @include('back.settings.admissions.admission' , [
                        'admission' => $admission
                    ])
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="col-md-12">

    <button {{$disabled}} class="btn btn-success float-right" onclick="app.addAdmission('{{route('admissions.create')}}' , 'Add Advisor')">{{__('Add Advisor')}}</button>

    </div>


</div>
