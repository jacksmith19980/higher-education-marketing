<?php

namespace App\Payment\moneris;

class mpgResponse
{

    var $responseData;

    var $p; //parser

    var $currentTag;
    var $purchaseHash = array();
    var $refundHash;
    var $correctionHash = array();
    var $isBatchTotals;
    var $term_id;
    var $receiptHash = array();
    var $ecrHash = array();
    var $CardType;
    var $currentTxnType;
    var $ecrs = array();
    var $cards = array();
    var $cardHash= array();

    //specifically for Resolver transactions
    var $resolveData;
    var $resolveDataHash;
    var $data_key="";
    var $DataKeys = array();
    var $isResolveData;

    //specifically for VdotMe transactions
    var $vDotMeInfo;
    var $isVdotMeInfo;

    //specifically for MasterPass transactions
    var $isPaypass;
    var $isPaypassInfo;
    var $masterPassData = array();

    //specifically for MPI transactions
    var $ACSUrl;
    var $isMPI = false;

    //specifically for Risk transactions
    var $isResults;
    var $isRule;
    var $ruleName;
    var $results = array();
    var $rules = array();

    function __construct($xmlString)
    {

        $this->p = xml_parser_create();
        xml_parser_set_option($this->p,XML_OPTION_CASE_FOLDING,0);
        xml_parser_set_option($this->p,XML_OPTION_TARGET_ENCODING,"UTF-8");
        xml_set_object($this->p,$this);
        xml_set_element_handler($this->p,"startHandler","endHandler");
        xml_set_character_data_handler($this->p,"characterHandler");
        xml_parse($this->p,$xmlString);
        xml_parser_free($this->p);

    }	//end of constructor


    function getMpgResponseData()
    {
        return($this->responseData);
    }

    //To prevent Undefined Index Notices
    private function getMpgResponseValue($responseData, $value)
    {
        return (isset($responseData[$value]) ? $responseData[$value] : '');
    }

    function getRecurSuccess()
    {
        return $this->getMpgResponseValue($this->responseData, 'RecurSuccess');
    }

    function getStatusCode()
    {
        return $this->getMpgResponseValue($this->responseData, 'status_code');
    }

    function getStatusMessage()
    {
        return $this->getMpgResponseValue($this->responseData, 'status_message');
    }

    function getAvsResultCode()
    {
        return $this->getMpgResponseValue($this->responseData,'AvsResultCode');
    }

    function getCvdResultCode()
    {
        return $this->getMpgResponseValue($this->responseData,'CvdResultCode');
    }

    function getCardType()
    {
        return $this->getMpgResponseValue($this->responseData,'CardType');
    }

    function getTransAmount()
    {
        return $this->getMpgResponseValue($this->responseData,'TransAmount');
    }

    function getTxnNumber()
    {
        return $this->getMpgResponseValue($this->responseData,'TransID');
    }

    function getReceiptId()
    {
        return $this->getMpgResponseValue($this->responseData, 'ReceiptId');
    }

    function getTransType()
    {
        return $this->getMpgResponseValue($this->responseData,'TransType');
    }

    function getReferenceNum()
    {
        return $this->getMpgResponseValue($this->responseData,'ReferenceNum');
    }

    function getResponseCode()
    {
        return $this->getMpgResponseValue($this->responseData,'ResponseCode');
    }

    function getISO()
    {
        return $this->getMpgResponseValue($this->responseData,'ISO');
    }

    function getBankTotals()
    {
        return $this->getMpgResponseValue($this->responseData,'BankTotals');
    }

    function getMessage()
    {
        return $this->getMpgResponseValue($this->responseData,'Message');
    }

    function getAuthCode()
    {
        return $this->getMpgResponseValue($this->responseData,'AuthCode');
    }

    function getComplete()
    {
        return $this->getMpgResponseValue($this->responseData,'Complete');
    }

    function getTransDate()
    {
        return $this->getMpgResponseValue($this->responseData,'TransDate');
    }

    function getTransTime()
    {
        return $this->getMpgResponseValue($this->responseData,'TransTime');
    }

    function getTicket()
    {
        return $this->getMpgResponseValue($this->responseData,'Ticket');
    }

    function getTimedOut()
    {
        return $this->getMpgResponseValue($this->responseData,'TimedOut');
    }

    function getCorporateCard()
    {
        return $this->getMpgResponseValue($this->responseData,'CorporateCard');
    }

    function getCavvResultCode()
    {
        return $this->getMpgResponseValue($this->responseData,'CavvResultCode');
    }

    function getCardLevelResult()
    {
        return $this->getMpgResponseValue($this->responseData,'CardLevelResult');
    }

    function getITDResponse()
    {
        return $this->getMpgResponseValue($this->responseData,'ITDResponse');
    }

    function getIsVisaDebit()
    {
        return $this->getMpgResponseValue($this->responseData,'IsVisaDebit');
    }

    function getMaskedPan()
    {
        return $this->getMpgResponseValue($this->responseData,'MaskedPan');
    }

    function getCfSuccess()
    {
        return $this->getMpgResponseValue($this->responseData,'CfSuccess');
    }

    function getCfStatus()
    {
        return $this->getMpgResponseValue($this->responseData,'CfStatus');
    }

    function getFeeAmount()
    {
        return $this->getMpgResponseValue($this->responseData,'FeeAmount');
    }

    function getFeeRate()
    {
        return $this->getMpgResponseValue($this->responseData,'FeeRate');
    }

    function getFeeType()
    {
        return $this->getMpgResponseValue($this->responseData,'FeeType');
    }

    //--------------------------- RecurUpdate response fields ----------------------------//

    function getRecurUpdateSuccess()
    {
        return $this->getMpgResponseValue($this->responseData,'RecurUpdateSuccess');
    }

    function getNextRecurDate()
    {
        return $this->getMpgResponseValue($this->responseData,'NextRecurDate');
    }

    function getRecurEndDate()
    {
        return $this->getMpgResponseValue($this->responseData,'RecurEndDate');
    }

    //--------------------------- MCP response fields ----------------------------//

    function getMCPAmount()
    {
        return $this->getMpgResponseValue($this->responseData,'MCPAmount');
    }

    function getMCPCurrencyCode()
    {
        return $this->getMpgResponseValue($this->responseData,'MCPCurrencyCode');
    }

    //-------------------------- Resolver response fields --------------------------------//

    function getDataKey()
    {
        return $this->getMpgResponseValue($this->responseData,'DataKey');
    }

    function getResSuccess()
    {
        return $this->getMpgResponseValue($this->responseData,'ResSuccess');
    }

    function getPaymentType()
    {
        return $this->getMpgResponseValue($this->responseData,'PaymentType');
    }

    //------------------------------------------------------------------------------------//

    function getResolveData()
    {
        if($this->responseData['ResolveData']!='null'){
            return ($this->resolveData);
        }

        return $this->getMpgResponseValue($this->responseData,'ResolveData');
    }

    function setResolveData($data_key)
    {
        $this->resolveData=$this->resolveDataHash[$data_key];
    }

    function getResolveDataHash()
    {
        return ($this->resolveDataHash);
    }

    function getDataKeys()
    {
        return ($this->DataKeys);
    }

    function getResDataDataKey()
    {
        return $this->getMpgResponseValue($this->resolveData,'data_key');
    }

    function getResDataPaymentType()
    {
        return $this->getMpgResponseValue($this->resolveData,'payment_type');
    }

    function getResDataCustId()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_id');
    }

    function getResDataPhone()
    {
        return $this->getMpgResponseValue($this->resolveData,'phone');
    }

    function getResDataEmail()
    {
        return $this->getMpgResponseValue($this->resolveData,'email');
    }

    function getResDataNote()
    {
        return $this->getMpgResponseValue($this->resolveData,'note');
    }

    function getResDataPan()
    {
        return $this->getMpgResponseValue($this->resolveData,'pan');
    }

    function getResDataMaskedPan()
    {
        return $this->getMpgResponseValue($this->resolveData,'masked_pan');
    }

    function getResDataExpDate()
    {
        return $this->getMpgResponseValue($this->resolveData,'expdate');
    }

    function getResDataAvsStreetNumber()
    {
        return $this->getMpgResponseValue($this->resolveData,'avs_street_number');
    }

    function getResDataAvsStreetName()
    {
        return $this->getMpgResponseValue($this->resolveData,'avs_street_name');
    }

    function getResDataAvsZipcode()
    {
        return $this->getMpgResponseValue($this->resolveData,'avs_zipcode');
    }

    function getResDataCryptType()
    {
        return $this->getMpgResponseValue($this->resolveData,'crypt_type');
    }

    function getResDataSec()
    {
        return $this->getMpgResponseValue($this->resolveData,'sec');
    }

    function getResDataCustFirstName()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_first_name');
    }

    function getResDataCustLastName()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_last_name');
    }

    function getResDataCustAddress1()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_address1');
    }

    function getResDataCustAddress2()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_address2');
    }

    function getResDataCustCity()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_city');
    }

    function getResDataCustState()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_state');
    }

    function getResDataCustZip()
    {
        return $this->getMpgResponseValue($this->resolveData,'cust_zip');
    }

    function getResDataRoutingNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'routing_num');
    }

    function getResDataAccountNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'account_num');
    }

    function getResDataMaskedAccountNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'masked_account_num');
    }

    function getResDataCheckNum()
    {
        return $this->getMpgResponseValue($this->resolveData,'check_num');
    }

    function getResDataAccountType()
    {
        return $this->getMpgResponseValue($this->resolveData,'account_type');
    }

    function getResDataPresentationType()
    {
        return $this->getMpgResponseValue($this->resolveData,'presentation_type');
    }

    function getResDataPAccountNumber()
    {
        return $this->getMpgResponseValue($this->resolveData,'p_account_number');
    }

    //-------------------------- VdotMe specific fields --------------------------------//
    function getVDotMeData()
    {
        return($this->vDotMeInfo);
    }

    function getCurrencyCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'currencyCode');
    }

    function getPaymentTotal()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'total');
    }

    function getUserFirstName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userFirstName');
    }

    function getUserLastName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userLastName');
    }

    function getUserName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userName');
    }

    function getUserEmail()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'userEmail');
    }

    function getEncUserId()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'encUserId');
    }

    function getCreationTimeStamp()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'creationTimeStamp');
    }

    function getNameOnCard()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'nameOnCard');
    }

    function getExpirationDateMonth()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['expirationDate'],'month');
    }

    function getExpirationDateYear()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['expirationDate'],'year');
    }

    function getBillingId()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'id');
    }

    function getLastFourDigits()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'lastFourDigits');
    }

    function getBinSixDigits()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'binSixDigits');
    }

    function getCardBrand()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'cardBrand');
    }

    function getVDotMeCardType()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'cardType');
    }

    function getBillingPersonName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'personName');
    }

    function getBillingAddressLine1()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'line1');
    }

    function getBillingCity()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'city');
    }

    function getBillingStateProvinceCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'stateProvinceCode');
    }

    function getBillingPostalCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'postalCode');
    }

    function getBillingCountryCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'countryCode');
    }

    function getBillingPhone()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'phone');
    }

    function getBillingVerificationStatus()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'verificationStatus');
    }

    function getIsExpired()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'expired');
    }

    function getPartialShippingCountryCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['partialShippingAddress'],'countryCode');
    }

    function getPartialShippingPostalCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['partialShippingAddress'],'postalCode');
    }

    function getShippingPersonName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'personName');
    }

    function getShippingCity()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'city');
    }

    function getShippingStateProvinceCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'stateProvinceCode');
    }

    function getShippingPostalCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'postalCode');
    }

    function getShippingCountryCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'countryCode');
    }

    function getShippingPhone()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'phone');
    }

    function getShippingDefault()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'default');
    }

    function getShippingId()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'id');
    }

    function getShippingVerificationStatus()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'verificationStatus');
    }

    function getBaseImageFileName()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'baseImageFileName');
    }

    function getHeight()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'height');
    }

    function getWidth()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'width');
    }

    function getIssuerBid()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo,'issuerBid');
    }

    function getRiskAdvice()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'advice');
    }

    function getRiskScore()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'score');
    }

    function getAvsResponseCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'avsResponseCode');
    }

    function getCvvResponseCode()
    {
        return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'cvvResponseCode');
    }

    //--------------------------- MasterPass response fields -----------------------------//

    function getCardBrandId()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBrandId');
    }


    function getCardBrandName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBrandName');
    }


    function getCardBillingAddressCity()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCity');
    }


    function getCardBillingAddressCountry()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCountry');
    }


    function getCardBillingAddressCountrySubdivision()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCountrySubdivision');
    }


    function getCardBillingAddressLine1()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressLine1');
    }


    function getCardBillingAddressLine2()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressLine2');
    }


    function getCardBillingAddressPostalCode()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressPostalCode');
    }


    function getCardBillingAddressRecipientPhoneNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressRecipientPhoneNumber');
    }


    function getCardBillingAddressRecipientName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressRecipientName');
    }


    function getCardCardHolderName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardCardHolderName');
    }


    function getCardExpiryMonth()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardExpiryMonth');
    }


    function getCardExpiryYear()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardExpiryYear');
    }


    function getContactEmailAddress()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactEmailAddress');
    }


    function getContactFirstName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactFirstName');
    }


    function getContactLastName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactLastName');
    }


    function getContactPhoneNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ContactPhoneNumber');
    }


    function getShippingAddressCity()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCity');
    }


    function getShippingAddressCountry()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCountry');
    }


    function getShippingAddressCountrySubdivision()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCountrySubdivision');
    }

    function getShippingAddressLine2()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressLine2');
    }


    function getShippingAddressPostalCode()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressPostalCode');
    }


    function getShippingAddressRecipientName()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressRecipientName');
    }


    function getShippingAddressRecipientPhoneNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressRecipientPhoneNumber');
    }


    function getPayPassWalletIndicator()
    {
        return $this->getMpgResponseValue($this->masterPassData,'PayPassWalletIndicator');
    }


    function getAuthenticationOptionsAuthenticateMethod()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsAuthenticateMethod');
    }


    function getAuthenticationOptionsCardEnrollmentMethod()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsCardEnrollmentMethod');
    }


    function getCardAccountNumber()
    {
        return $this->getMpgResponseValue($this->masterPassData,'CardAccountNumber');
    }


    function getAuthenticationOptionsEciFlag()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsEciFlag');
    }


    function getAuthenticationOptionsPaResStatus()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsPaResStatus');
    }


    function getAuthenticationOptionsSCEnrollmentStatus()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsSCEnrollmentStatus');
    }


    function getAuthenticationOptionsSignatureVerification()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsSignatureVerification');
    }


    function getAuthenticationOptionsXid()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsXid');
    }


    function getAuthenticationOptionsCAvv()
    {
        return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsCAvv');
    }


    function getTransactionId()
    {
        return $this->getMpgResponseValue($this->masterPassData,'TransactionId');
    }

    function getMPRequestToken()
    {
        return $this->getMpgResponseValue($this->responseData,'MPRequestToken');
    }

    function getMPRedirectUrl()
    {
        return $this->getMpgResponseValue($this->responseData,'MPRedirectUrl');
    }

    //------------------- VDotMe & MasterPass shared response fields ---------------------//

    function getShippingAddressLine1()
    {
        if ($this->isPaypass)
        {
            return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressLine1');
        }
        else
        {
            return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'line1');
        }
    }
//------------------- MPI response fields ---------------------//
    function getMpiType()
    {
        return $this->getMpgResponseValue($this->responseData,'MpiType');
    }

    function getMpiSuccess()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiSuccess');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'success');
        }
    }

    function getMpiMessage()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiMessage');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'message');
        }
    }

    function getMpiPaReq()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiPaReq');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'PaReq');
        }
    }

    function getMpiTermUrl()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiTermUrl');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'TermUrl');
        }
    }

    function getMpiMD()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiMD');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'MD');
        }
    }

    function getMpiACSUrl()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiACSUrl');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'ACSUrl');
        }
    }

    function getMpiCavv()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiCavv');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'cavv');
        }
    }

    function getMpiEci()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiEci');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'eci');
        }
    }


    function getMpiPAResVerified()
    {
        if ($this->isMPI === false)
        {
            return $this->getMpgResponseValue($this->responseData,'MpiPAResVerified');
        }
        else
        {
            return $this->getMpgResponseValue($this->responseData,'PAResVerified');
        }
    }

    function getMpiResponseData()
    {
        return($this->responseData);
    }

    function getMpiInLineForm()
    {

        $inLineForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
            "<!--
				function OnLoadEvent()
				{
					document.downloadForm.submit();
				}
				-->
				</SCRIPT>" .
            '<body onload="OnLoadEvent()">
					<form name="downloadForm" action="' . $this->getMpiACSUrl() .
            '" method="POST">
					<noscript>
					<br>
					<br>
					<center>
					<h1>Processing your 3-D Secure Transaction</h1>
					<h2>
					JavaScript is currently disabled or is not supported
					by your browser.<br>
					<h3>Please click on the Submit button to continue
					the processing of your 3-D secure
					transaction.</h3>
					<input type="submit" value="Submit">
					</center>
					</noscript>
					<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
					<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
					<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
				</form>
				</body>
				</html>';

        return $inLineForm;
    }

    function getMpiPopUpWindow()
    {
        $popUpForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
            "<!--
					function OnLoadEvent()
					{
						window.name='mainwindow';
						//childwin = window.open('about:blank','popupName','height=400,width=390,status=yes,dependent=no,scrollbars=yes,resizable=no');
						//document.downloadForm.target = 'popupName';
						document.downloadForm.submit();
					}
					-->
					</SCRIPT>" .
            '<body onload="OnLoadEvent()">
						<form name="downloadForm" action="' . $this->getMpiAcsUrl() .
            '" method="POST">
						<noscript>
						<br>
						<br>
						<center>
						<h1>Processing your 3-D Secure Transaction</h1>
						<h2>
						JavaScript is currently disabled or is not supported
						by your browser.<br>
						<h3>Please click on the Submit button to continue
						the processing of your 3-D secure
						transaction.</h3>
						<input type="submit" value="Submit">
						</center>
						</noscript>
						<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
						<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
						<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
						</form>
					</body>
					</html>';

        return $popUpForm;
    }


    //-----------------  Risk response fields  ---------------------------------------------------------//

    function getRiskResponse()
    {
        return ($this->responseData);
    }

    function getResults()
    {
        return ($this->results);
    }

    function getRules()
    {
        return ($this->rules);
    }

    //--------------------------- BatchClose response fields -----------------------------//

    function getTerminalStatus($ecr_no)
    {
        return $this->getMpgResponseValue($this->ecrHash,$ecr_no);
    }

    function getPurchaseAmount($ecr_no,$card_type)
    {
        return ($this->purchaseHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->purchaseHash[$ecr_no][$card_type]['Amount']);
    }

    function getPurchaseCount($ecr_no,$card_type)
    {
        return ($this->purchaseHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->purchaseHash[$ecr_no][$card_type]['Count']);
    }

    function getRefundAmount($ecr_no,$card_type)
    {
        return ($this->refundHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->refundHash[$ecr_no][$card_type]['Amount']);
    }

    function getRefundCount($ecr_no,$card_type)
    {
        return ($this->refundHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->refundHash[$ecr_no][$card_type]['Count']);
    }

    function getCorrectionAmount($ecr_no,$card_type)
    {
        return ($this->correctionHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->correctionHash[$ecr_no][$card_type]['Amount']);
    }

    function getCorrectionCount($ecr_no,$card_type)
    {
        return ($this->correctionHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->correctionHash[$ecr_no][$card_type]['Count']);
    }

    function getTerminalIDs()
    {
        return ($this->ecrs);
    }

    function getCreditCardsAll()
    {
        return (array_keys($this->cards));
    }

    function getCreditCards($ecr)
    {
        return $this->getMpgResponseValue($this->cardHash,$ecr);
    }

    function characterHandler($parser,$data)
    {
        if($this->isBatchTotals)
        {
            switch($this->currentTag)
            {
                case "term_id"    :
                {
                    $this->term_id=$data;
                    array_push($this->ecrs,$this->term_id);
                    $this->cardHash[$data]=array();
                    break;
                }

                case "closed"     :
                {
                    $ecrHash=$this->ecrHash;
                    $ecrHash[$this->term_id]=$data;
                    $this->ecrHash = $ecrHash;
                    break;
                }

                case "CardType"   :
                {
                    $this->CardType=$data;
                    $this->cards[$data]=$data;
                    array_push($this->cardHash[$this->term_id],$data) ;
                    break;
                }

                case "Amount"     :
                {
                    if($this->currentTxnType == "Purchase")
                    {
                        $this->purchaseHash[$this->term_id][$this->CardType]['Amount']=$data;
                    }
                    elseif( $this->currentTxnType == "Refund")
                    {
                        $this->refundHash[$this->term_id][$this->CardType]['Amount']=$data;
                    }
                    elseif( $this->currentTxnType == "Correction")
                    {
                        $this->correctionHash[$this->term_id][$this->CardType]['Amount']=$data;
                    }
                    break;
                }

                case "Count"     :
                {
                    if($this->currentTxnType == "Purchase")
                    {
                        $this->purchaseHash[$this->term_id][$this->CardType]['Count']=$data;
                    }
                    elseif( $this->currentTxnType == "Refund")
                    {
                        $this->refundHash[$this->term_id][$this->CardType]['Count']=$data;
                    }
                    else if( $this->currentTxnType == "Correction")
                    {
                        $this->correctionHash[$this->term_id][$this->CardType]['Count']=$data;
                    }
                    break;
                }
            }

        }
        elseif($this->isResolveData && $this->currentTag != "ResolveData")
        {
            if($this->currentTag == "data_key")
            {
                $this->data_key=$data;
                array_push($this->DataKeys,$this->data_key);
                $this->resolveData[$this->currentTag] = $data;
            }
            else
            {
                $this->resolveData[$this->currentTag] = $data;
            }
        }
        elseif($this->isVdotMeInfo)
        {
            if($this->ParentNode != "")
                $this->vDotMeInfo[$this->ParentNode][$this->currentTag] = $data;
            else
                $this->vDotMeInfo[$this->currentTag] = $data;
        }
        else if ($this->isPaypassInfo)
        {
            $this->masterPassData[$this->currentTag] = $data;
        }
        elseif($this->isResults)
        {
            $this->results[$this->currentTag] = $data;

        }
        elseif($this->isRule)
        {

            if ($this->currentTag == "RuleName")
            {
                $this->ruleName=$data;
            }
            $this->rules[$this->ruleName][$this->currentTag] = $data;

        }
        else
        {
            $this->responseData[$this->currentTag] = $data;
        }

    }//end characterHandler



    function startHandler($parser,$name,$attrs)
    {

        $this->currentTag=$name;

        if($this->currentTag == "ResolveData")
        {
            $this->isResolveData=1;
        }
        elseif($this->isResolveData)
        {
            $this->resolveData[$this->currentTag]="";
        }
        elseif($this->currentTag == "MpiResponse")
        {
            $this->isMPI=true;
        }
        elseif($this->currentTag == "VDotMeInfo")
        {
            $this->isVdotMeInfo=1;
        }
        elseif($this->isVdotMeInfo)
        {
            switch($name){
                case "billingAddress":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "partialShippingAddress":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "shippingAddress":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "riskData":
                {
                    $this->ParentNode=$name;
                    break;
                }
                case "expirationDate":
                {
                    $this->ParentNode=$name;
                    break;
                }
            }
        }
        else if($this->currentTag == "PayPassInfo")
        {
            $this->isPaypassInfo=1;
            $this->isPaypass=1;
        }
        elseif($this->currentTag == "BankTotals")
        {
            $this->isBatchTotals=1;
        }
        elseif($this->currentTag == "Purchase")
        {
            $this->purchaseHash[$this->term_id][$this->CardType]=array();
            $this->currentTxnType="Purchase";
        }
        elseif($this->currentTag == "Refund")
        {
            $this->refundHash[$this->term_id][$this->CardType]=array();
            $this->currentTxnType="Refund";
        }
        elseif($this->currentTag == "Correction")
        {
            $this->correctionHash[$this->term_id][$this->CardType]=array();
            $this->currentTxnType="Correction";
        }
        elseif($this->currentTag == "Result")
        {
            $this->isResults=1;
        }
        elseif($this->currentTag == "Rule")
        {
            $this->isRule=1;
        }
    }

    function endHandler($parser,$name)
    {

        $this->currentTag=$name;
        if($this->currentTag == "ResolveData")
        {
            $this->isResolveData=0;
            if($this->data_key!="")
            {
                $this->resolveDataHash[$this->data_key]=$this->resolveData;
                $this->resolveData=array();
            }
        }
        elseif($this->currentTag == "VDotMeInfo")
        {
            $this->isVdotMeInfo=0;
        }
        elseif($this->isVdotMeInfo)
        {
            switch($this->currentTag){
                case "billingAddress":
                {
                    $this->ParentNode="";
                    break;
                }
                case "partialShippingAddress":
                {
                    $this->ParentNode="";
                    break;
                }
                case "shippingAddress":
                {
                    $this->ParentNode="";
                    break;
                }
                case "riskData":
                {
                    $this->ParentNode="";
                    break;
                }
                case "expirationDate":
                {
                    $this->ParentNode="";
                    break;
                }
            }
        }
        elseif($name == "BankTotals")
        {
            $this->isBatchTotals=0;
        }
        else if($this->currentTag == "PayPassInfo")
        {
            $this->isPaypassInfo=0;
        }
        elseif($name == "Result")
        {
            $this->isResults=0;
        }
        elseif($this->currentTag == "Rule")
        {
            $this->isRule=0;
        }

        $this->currentTag="/dev/null";
    }

}//end class mpgResponse
