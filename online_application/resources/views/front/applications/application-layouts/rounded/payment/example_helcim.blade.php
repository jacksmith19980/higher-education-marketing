<!--SCRIPT-->
<script type="text/javascript" src="https://secure.myhelcim.com/js/version2.js"></script>

<!--FORM-->
<form name="helcimForm" id="helcimForm" action="{{ route('application.payment.pay.no-login' , ['school' => $school , 'application' => $application , 'invoice' => $invoice ]) }}" method="POST">

    @csrf
    <input type="hidden" name="payment" class="cc_info" data-name="payment" value="helcim" />

    <!--RESULTS-->
    <div id="helcimResults"></div>

    <!--SETTINGS-->
    <input type="hidden" id="token" value="58ae1d44d7ac6959332969">
    <input type="hidden" id="language" value="en">

    <!--CARD-INFORMATION-->
    Card Token: <input type="text" id="cardToken" value="1"><br/>
    Credit Card Number: <input type="text" id="cardNumber" value=""><br/>
    Expiry Month: <input type="text" id="cardExpiryMonth" value=""> Expiry Year: <input type="text" id="cardExpiryYear" value=""><br/>
    CVV: <input type="text" id="cardCVV" value=""><br/>

    <!--OPTIONAL-AVS-->
    Card Holder Name: <input type="text" id="cardHolderName" value=""><br/>
    Card Holder Address: <input type="text" id="cardHolderAddress" value=""><br/>
    Card Holder Postal Code: <input type="text" id="cardHolderPostalCode" value=""><br/>

    <!--OPTIONAL-AMOUNT-->
    Amount: <input type="text" id="amount" value="100.00"><br/>

    <!--BUTTON-->
    <input type="button" id="buttonProcess" value="Process" onclick="javascript:helcimProcess();">

    <button style="margin-top:15px;" class="btn btn-secondary payment-btn"
    onclick="app.proccessPayment(this, 'helcim' , '{{ route('application.payment.pay.no-login' , ['school' => $school , 'application' => $application , 'invoice' => $invoice ]) }}' )">{{__('MAKE
    PAYMENT')}}</button>

</form>
