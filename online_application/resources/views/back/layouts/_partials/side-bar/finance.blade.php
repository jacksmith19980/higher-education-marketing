<li class="sidebar-item">
    <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false"
        data-toggle="tooltip" data-placement="right" title="{{ __('Finance') }}">
        <i class="mdi mdi-credit-card"></i>
        <span class="hide-menu">{{ __('Finance') }}</span>
    </a>

    <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
            <a href="{{ route('accounting.index') }}" class="sidebar-link waves-effect waves-dark sidebar-link"
                aria-expanded="false" data-toggle="tooltip" data-placement="right" title="{{ __('Invoices') }}">
                <i class="mdi mdi-credit-card"></i>
                <span class="hide-menu">{{ __('Invoices') }}</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('services.index') }}" class="sidebar-link waves-effect waves-dark sidebar-link"
                aria-expanded="false" data-toggle="tooltip" data-placement="right"
                title="{{ __('Educational Services') }}">
                <i class="mdi mdi-credit-card"></i>
                <span class="hide-menu">{{ __('Educational Services') }}</span>
            </a>
        </li>
    </ul>
</li>
