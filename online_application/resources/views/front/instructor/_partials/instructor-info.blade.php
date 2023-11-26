<div class="row justify-content-center">
    <div class="col-12">
        <div class="card" style="border-top: 5px solid #004d6e;">
            <div class="card-body">
                <h5>{{__('Welcome Back!')}}</h5>
                <span style="font-size: 20px; color: #226581">
                    {{auth()->guard('instructor')->user()->name}} 
                </span>
                <br>
                <span style="font-family: NunitoSans">{{__('instructor')}}</span>
            </div>
        </div>
    </div>
</div>