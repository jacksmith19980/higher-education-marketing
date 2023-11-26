<div>

<div class="d-block">
<p>
    <strong>
        @if(isset($validation['is_valid']) && $validation['is_valid']['status']  )
            <i class="text-success fas fa-check-circle"></i>
        @else
            <i class="text-danger fas fa-times-circle"></i>
        @endif
        {{__('Domain Verifiation')}}
    </strong>
</p>
<p></p>
</div>

<div class="d-block">
<p>
    <strong>
        @if(isset($validation['is_valid']) && $validation['spf']['status']  )
            <i class="text-success fas fa-check-circle"></i>
        @else
            <i class="text-danger fas fa-times-circle"></i>
        @endif
         {{__('SPF')}}
    </strong>
</p>
<p></p>
</div>

<div class="d-block">
<p>
    <strong>
        @if(isset($validation['is_valid']) && $validation['dkim']['status']  )
            <i class="text-success fas fa-check-circle"></i>
        @else
            <i class="text-danger fas fa-times-circle"></i>
        @endif
         {{__('DKIM')}}
    </strong>
</p>
<p></p>
</div>
</div>
