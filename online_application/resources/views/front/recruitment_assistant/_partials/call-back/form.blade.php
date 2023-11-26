<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<div class="row">
  
    <div class="col-12 p-3 request-call">
        
        <h2 class="request-call-title">Request a Phone Call</h2>
        <p>
            Need help choosing the right program? Have questions about auto careers? Want to know more about your financial aid options? Fill out your details below and one of our advisors will call you as soon as we are available!
        </p>

        
        <div id="modal-content-wrapper">
            <form class="d-block call-me-back-request" id="requestCallBack" onsubmit="app.requestCallBack(this)" action="{{route('call.back.request' , ['school' => $school, 'step' => $step,'assistantBuilder'  => $assistantBuilder])}}" 

                data-conference-route = "{{route('call.back.add' , ['school' => $school, 'step' => $step,'assistantBuilder'  => $assistantBuilder])}}"

                data-status-route="{{route('call.back.status' , ['school' => $school, 'step' => $step,'assistantBuilder'  => $assistantBuilder])}}"
                

                method="POST">
                <div class="form-group">
                    <input class="form-control form-control-lg" required type="text" placeholder="Your Name" name="name">    

                    <div class="invalid-feedback" id="name-error">
                        Please provide your name.
                    </div>

                </div>
                
                <div class="form-group">
                    <input class="form-control form-control-lg" required type="text" placeholder="Your Email" name="email">

                    <div class="invalid-feedback" id="email-error">
                        Please provide your email.
                    </div>

                </div>

                <div class="form-group">
                    <input class="form-control form-control-lg" required type="text" placeholder="Your Phone" name="phone">
                    
                    <div class="invalid-feedback" id="phone-error">
                        Please provide your phone.
                    </div>

                </div>
            
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" required id="consent" name="consent" value="true" checked>
                    <label class="form-check-label" for="consent">I agree to receive communications from {{$school->name}}</label>

                    <div class="invalid-feedback" id="name-error">
                        Please allow us to contact you.
                    </div>
                </div>

                <div class="form-group pt-2">
                    <button class="btn btn-danger waves-effect text-right float-right">CONNECT</button>
                </div>

            </form>
        </div>

    </div>
    
</div>