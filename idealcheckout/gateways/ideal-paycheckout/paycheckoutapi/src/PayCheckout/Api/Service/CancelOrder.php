<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Request;
use stdClass;

class CancelOrder
{
    /**
	 * Cancel an order
	 * 
	 * @param string $paymentReference
     * @param bool $processOffline
	 * @return ApiMessage
	 */
    public static function create($paymentReference, $processOffline = false)
    {
        // Create request
        $request = new Request();
        $request->setPaymentReference($paymentReference);

        if ($processOffline)
        {
            $parameters = new stdClass;
            $parameters->CancelOrder = new stdClass;
			$parameters->CancelOrder->ProcessOffline = 'True';
            // Set parameters
            $request->setParameters($parameters);
        }

        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::CANCEL_ORDER, $request );  
        
        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\CancelOrder::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        if ($processOffline != null && !is_bool($processOffline))
        {
            $apiMessage->addValidationError('In method Api\Service\CancelOrder::create parameter processOffline[' . $processOffline . '] is supposed to be a bool and not a ' . gettype($processOffline));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;
    }
}
