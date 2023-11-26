<aside class="left-sidebar">
    <div class="overlay-sidebar" style="top:0; left:0; width:100%; height:100%;color: #fff;position: absolute;z-index: 999;"></div>
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">

            <ul id="sidebarnav">

                @include('back.layouts._partials.side-bar.dashboard')


                    @include('back.layouts._partials.side-bar.school')
                    @include('back.layouts._partials.side-bar.applicant')
                    @include('back.layouts._partials.side-bar.application')
                    @include('back.layouts._partials.side-bar.quotation')
                    @include('back.layouts._partials.side-bar.assistant')
                    @include('back.layouts._partials.side-bar.call-back')
                    @include('back.layouts._partials.side-bar.agencies')
                    @include('back.layouts._partials.side-bar.promocodes')

            </ul>
        </nav>
    </div>
</aside>
