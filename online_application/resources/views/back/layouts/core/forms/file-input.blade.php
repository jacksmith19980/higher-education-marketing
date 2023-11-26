<div class="form-group">

    @label($label)

    <label for="{{$name}}">{{ __($label) }}{!! ($required)? '<span class="text-danger">*</span>' : '' !!}</label>

    @endlabel


        <ul class="list-unstyled">
            <li class="media">
                @if (isset($value) && !empty($value) )
                    <img class="d-flex m-r-15" src="{{ Storage::disk('s3')->temporaryUrl($value, \Carbon\Carbon::now()->addMinutes(5)) }}" width="60" alt="Generic placeholder image">
                @endif
                <div class="media-body" style="padding-top: 15px;">
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input {{$class}}" id="{{$name}}" name="{{$name}}" {{$attr}}>
                            <label class="custom-file-label label_{{$name}}" for="{{$name}}">{{__('Choose file')}}</label>
                        </div>
                    </div>
                </div>
            </li>
        </ul>





    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
