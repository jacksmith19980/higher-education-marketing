<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">

            <ul id="sidebarnav">

                @include('front.instructor._partials.side-bar.dashboard')

                @tenant
                @include('front.instructor._partials.side-bar.attendance')
                @endtenant

                @include('front.instructor._partials.side-bar.calendar')
            </ul>
        </nav>
    </div>
</aside>
