<div id="campus-wrapper">
   @if ( isset($quotation->properties['hide_campus_select']) &&  count($quotation->properties['campuses']) == 1 )

        <input type="hidden" name="campus[]" class="quotation-auto-process" 
        data-quotation="{{$quotation->id}}" data-current="campus" data-next="program"
        value="{{$quotation->properties['campuses'][0]}}"
         />
            
    @elseif( isset($quotation->properties['hide_campus_select']) &&  count($quotation->properties['campuses']) > 1 )
        
        @foreach ($quotation->properties['campuses'] as $campus)
        
            <input type="text" name="campus[]" value="{{$campus}}" data-quotation="{{$quotation->id}}" data-current="campus" data-next="program" />
            
        @endforeach

       
    
    @else
        <div class="form-group">
            @include('back.layouts.core.forms.checkbox-group',
            [
                'name'          => "campus[]",
                'label'         => __('Venue') ,
                'class'         => '' ,
                'required'      => true,
                'attr'          => 'onchange=app.optionChanged(this) data-quotation='.$quotation->id.' data-current=campus data-next=program',
                'value'         => '',
                'placeholder'   => __('Select venue'),
                'data'          =>QuotationHelpers::getCampusesSelection($quotation->properties['campuses']),
            ])
        </div>
    @endif
</div>

<div id="program-wrapper"></div>