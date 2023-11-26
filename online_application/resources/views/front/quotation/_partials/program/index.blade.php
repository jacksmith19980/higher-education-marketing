

<div id="program-wrapper">

    @if ( isset($quotation->properties['hide_program_select']) &&  count($programs) == 1 )



            <input type="hidden" name="program[]" class="" 

            data-quotation="{{$quotation->id}}" data-current="program" data-next="date"

            value="{{key($programs)}}"

            />

        

        @elseif( isset($quotation->properties['hide_program_select']) &&  count($programs) > 1 )

        

            @foreach ($programs as $porgramID => $program)

                

                    <input type="hidden" name="program[]" data-quotation="{{$quotation->id}}" data-current="program" data-next="date" value="{{$porgramID}}" />

                

            @endforeach



        @else

            <div class="form-group">

                @include('back.layouts.core.forms.checkbox-group',

                [

                    'name'          => "program[]",

                    'label'         => __('Please select which programme you would like to book.') ,

                    'class'         => '' ,

                    'required'      => true,

                    'attr'          => 'onchange=app.optionChanged(this) data-quotation='.$quotation->id.' data-current=program data-next=date',

                    'value'         => '',

                    'placeholder'   => __('Select program'),

                    'data'          => $programs,

                ])

            </div>

        @endif

    </div>



    <div id="date-wrapper"></div>



<script>

    $(function () {

        app.optionChanged(  document.getElementsByName("program[]") );

    })

</script>