<table class="table mb-0 table-hover compressed-table" data-tablesaw-mode="swipe">
    <tbody>
        <tr>
            <td class="title">{{__('Title')}}</td>
            <td>

                <span class="editable editable-click"
                data-placement="top"
                data-name="title"
                data-value="{{$program->title}}"
                data-url="{{route('programs.fields.update' , [
                    'program' => $program
                    ])}}">
                    {{$program->title}}
                </span>
            </td>
        </tr>
        <tr>
            <td class="title">{{__('Code')}}</td>
            <td>
                <span class="" data-placement="top" data-name="slug" data-value="{{$program->slug}}" data-url="">
                    {{$program->slug}}
                </span>
            </td>
        </tr>
        <tr>
            <td class="title">{{__('Type')}}</td>
            <td>

                <span class="editable editable-click"
                data-placement="top"
                data-name="program_type"
                data-value="{{$program->program_type}}"
                data-url="{{route('programs.fields.update' , [
                    'program' => $program
                    ])}}">
                    {{$program->program_type}}
                </span>
            </td>
        </tr>

        <tr>
            <td class="title">{{__('Campuses')}}</td>
            <td>
                @php
                    $campuses = $program->campuses->pluck('title' , 'slug')->toArray();
                @endphp
                <span class="editable editable-click"
                data-placement="top"
                data-name="program_campus"
                data-value="{{ json_encode($campuses) }}"
                data-url="{{route('programs.fields.update' , [
                    'program' => $program
                    ])}}">
                    {{
                        implode(", ",$campuses)
                    }}
                </span>
            </td>
        </tr>

        @foreach($customFields as $customField)
            <tr>
                <td class="title">{{ $customField['name'] }}</td>
                <td>
                    <span class="editable editable-click" data-placement="top" data-name="{{$customField['slug']}}"
                    data-value="{{ $program->{$customField['slug']} }}" data-url="{{route('programs.fields.update' , [
                    'program' => $program
                    ])}}"
                    >
                        {{ $program->{$customField['slug']} }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
