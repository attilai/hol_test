<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResponse;
use PayCheckout\ApiResult;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\PaymentInfo\PaymentInfo;
use PayCheckout\Json\Request\Request;

class GetPaymentInfo
{
    /**
	 * Get payment info
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
        $apiMessage = new ApiMessage(ApiAction::GET_PAYMENT_INFO, $request);  
        
        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\GetPaymentInfo::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;       
    }
    
    /**
	 * Get payment info from repsonse
	 * 
	 * @param ApiResponse $response 
	 * @return PaymentInfo|null
	 */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::GET_PAYMENT_INFO || $response->getApiReturnValues() == null)
		{ 
            return null;
        }

        foreach ($response->getApiReturnValues() as $key => $value)
		{
            if ($value !== null && strpos($key,'PaymentInfo') !== false)
			{
				$data = json_decode($value->json, false, 512, JSON_BIGINT_AS_STRING);    
				
				$paymentInfo = new PaymentInfo();
				$paymentInfo->jsonDeserialize($data);

				return $paymentInfo;
			}
        }
        
        return null;
    }
}
