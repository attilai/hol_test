<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Request;

class SendManualNotification
{
    /**
	 * Send manual notification of last valid payment or action
	 * 
	 * @param string $paymentReference
	 * @return ApiMessage
	 */
    public static function create($paymentReference)
    {
        // Create request
        $request = new Request();
        $request->setPaymentReference($paymentReference);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::MANUAL_NOTIFY, $request );   
        
        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\SendManualNotification::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;
    }
}
