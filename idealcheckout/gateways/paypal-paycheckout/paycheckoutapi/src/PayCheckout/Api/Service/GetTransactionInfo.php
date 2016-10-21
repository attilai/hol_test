<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResponse;
use PayCheckout\ApiResult;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\PaymentInfo\TransactionInfo;
use PayCheckout\Json\Request\Request;
use stdClass;

class GetTransactionInfo
{
    /**
	 * Get transaction info
	 * 
	 * @param string $paymentReference
	 * @param string $transactionReference
	 * @return ApiMessage
	 */
    public static function create($paymentReference, $transactionReference)
    {
        // Create request
        $request = new Request();
        $request->setPaymentReference($paymentReference);
        
        // Create parameters
        $parameters = new stdClass;
        $parameters->GetTransactionInfo                         = new stdClass;
        $parameters->GetTransactionInfo->TransactionReference   = (string) $transactionReference;
        
        // Set parameters
        $request->setParameters($parameters);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::GET_TRANSACTION_INFO, $request);  
        
        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\GetTransactionInfo::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        if ($transactionReference != null && !HelpFunction::isTypeValidForReference($transactionReference))
        {
            $apiMessage->addValidationError('In method Api\Service\GetTransactionInfo::create parameter transactionReference[' . $transactionReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($transactionReference));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;              
    }
    
    /**
	 * Get transaction info from response
	 * 
	 * @param ApiResponse $response 
	 * @return TransactionInfo|null
	 */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::GET_TRANSACTION_INFO || $response->getApiReturnValues() == null)
		{ 
            return null;
        }
        
        foreach ($response->getApiReturnValues() as $key => $value)
		{
            if ($value !== null && strpos($key,'TransactionInfo') !== false)
			{
				$data = json_decode($value->json, false, 512, JSON_BIGINT_AS_STRING);
				$transactionInfo = new TransactionInfo();
				$transactionInfo->jsonDeserialize($data);
				return $transactionInfo;
			}
        }
        
        return null;
    }
}
