<?php

namespace PayCheckout;

use PayCheckout\Json\Response\Response;

class ApiResponse extends Json\Response\Response
{   
    /**
	 * Creates a PayCheckout\ApiResponse from a PayCheckout\Json\Response\Response
	 * 
	 * @param Response $response 
	 * @return ApiResponse
	 */
    static function createFromJsonResponse(Json\Response\Response $response)
    {
        $apiResponse = new ApiResponse();
        
        $apiResponse->apiResult             = $response->getApiResult();       
        $apiResponse->apiReturnValues		= $response->getApiReturnValues();
        $apiResponse->actionPerformed		= $response->getActionPerformed();
        $apiResponse->traceReference		= $response->getTraceReference();
        $apiResponse->paymentReference		= $response->getPaymentReference();
        $apiResponse->errorCode				= $response->getErrorCode();
        $apiResponse->errors				= $response->getErrors();
        $apiResponse->warnings				= $response->getWarnings();
        $apiResponse->errorToShowToConsumer	= $response->getErrorToShowToConsumer();
        $apiResponse->redirectInfo			= $response->getRedirectInfo();
        $apiResponse->transactionResult		= $response->getTransactionResult();
        $apiResponse->transactionReference  = $response->getTransactionReference();
        
        return $apiResponse;
    }
}