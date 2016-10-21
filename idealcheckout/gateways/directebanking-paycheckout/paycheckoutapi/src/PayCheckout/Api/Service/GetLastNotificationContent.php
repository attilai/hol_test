<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Request;
use PayCheckout\ApiResponse;
use PayCheckout\ApiResult;

class GetLastNotificationContent
{
    /**
	 * Get last content of last notification
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
        $apiMessage = new ApiMessage(ApiAction::GET_LAST_NOTIFICATION_CONTENT, $request );   
        
        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\GetLastNotificationContent::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;
    }

    /**
     * Get current configuration using API response
     * 
     * @param ApiResponse $response 
     * @return string NotificationContent
     */
    public static function response(ApiResponse $response)
    {        
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::GET_LAST_NOTIFICATION_CONTENT || $response->getApiReturnValues() == null)
        { 
            return null;
        }
        
        foreach ($response->getApiReturnValues() as $key => $value)
		{
            if ($value !== null && strpos($key, 'GetLastNotificationContent') !== false)
			{
				return $value->json;                    
			}
        }       
        return null;
    }

}
