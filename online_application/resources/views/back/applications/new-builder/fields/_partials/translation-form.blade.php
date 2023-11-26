<div class="translation-form">

    <div class="translation_input new-field">
        <select name="langauges" class="form-control custom-select select2 form-control-lg mb-3">
            <option value="en">{{__('English')}}</option>
            <option value="fr">{{__('French')}}</option>
            <option value="it">{{__('Italian')}}</option>
        </select>

        {{--  <h6 style="text-align:left">English</h6>  --}}

        <input type="text"  class="form-control" placeholder="{{__('Add Translation')}}" />
        <button class="mt-3 btn btn-success bt-xs">{{__('Save')}}</button>
    </div>

    <div class="tranlsations-list">
        <span class="tr-lang">DU</span>
        <span class="tr-lang">FR</span>
        <span class="tr-lang"><i class="fa fa-plus"></i></span>
    </div>


</div>
