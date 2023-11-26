<aside class="left-sidebar">
    <!-- Added slide button for desktop and mobile -->
    <a class="d-block nav-link sidebartoggler waves-effect waves-light sidebar-nav-toggler" href="javascript:void(0)"><i
                class="ti-arrow-left"></i></a>
    <a class="nav-toggler waves-effect waves-light d-block sidebar-nav-toggler" href="javascript:void(0)"><i
                class="ti-arrow-left"></i></a>
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                    @include('back.layouts._partials.side-bar.dashboard')
                    @include('back.layouts._partials.side-bar.messages')
                    @include('back.layouts._partials.side-bar.settings')
                    @include('back.layouts._partials.side-bar.applicant')
                    @include('back.layouts._partials.side-bar.submission')
                    @include('back.layouts._partials.side-bar.application')
                    @include('back.layouts._partials.side-bar.envelopes')
                    @include('back.layouts._partials.side-bar.sis')
                    @include('back.layouts._partials.side-bar.quotation')
                    @include('back.layouts._partials.side-bar.assistant')
                    @include('back.layouts._partials.side-bar.agencies')
                    @include('back.layouts._partials.side-bar.finance')
                    @include('back.layouts._partials.side-bar.documents')
            </ul>
        </nav>
    </div>
</aside>
