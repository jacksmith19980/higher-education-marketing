<div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
    <span class="with-arrow"><span class="bg-primary"></span></span>
    <ul class="list-style-none">
        <li>
            <div class="drop-title bg-primary text-white">
                <h4 class="m-b-0 m-t-5">{{count($notifications)}} New</h4>
                <span class="font-light">Notifications</span>
            </div>
        </li>
        <li>
            <div class="message-center notifications">
                
                @if ($notifications->count())
                    @foreach ($notifications as $notification)
                        {!! $notification->text !!}
                    @endforeach
                @endif
               <!--  <a href="javascript:void(0)" class="message-item">
                   <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                   <span class="mail-contnet">
                       <h5 class="message-title">Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span> </span>
               </a> -->
            </div>
        </li>
        <li>
            <a class="nav-link text-center m-b-5" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
        </li>
    </ul>
</div>