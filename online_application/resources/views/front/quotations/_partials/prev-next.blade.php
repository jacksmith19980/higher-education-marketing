<section class="section-footer-nav">
    @if (isset($steps['prev']['step']))
    <a href="{{ route('quotations.show' , [
            'school'        => $school,
            'quotation'     => $quotation,
            'step'          => $steps['prev']['step']
            ]) }}" class="btn is-flat btn-accent-1 prev px-5">{{__('Back')}}</a>
    @endif

    @if (isset($steps['next']['step']))
        <a href="javascript:void(0)" data-step="{{$step}}" data-route="{{ route('quotations.show' , [
                                'school'        => $school,
                                'quotation'     => $quotation,
                                'step'          => $steps['next']['step']
            ]) }}" class="btn is-flat btn-accent-1 next px-5" onclick="app.isValid(this)" data-message="{{__($steps['current']['error_message'])}}">{{__('Next')}}</a>

    @endif
</section>