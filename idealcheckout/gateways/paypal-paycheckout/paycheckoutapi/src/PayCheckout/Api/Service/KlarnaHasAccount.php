<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResult;
use PayCheckout\ApiResponse;
use PayCheckout\Json\Request\Request;
use stdClass;

class KlarnaHasAccount
{
    /**
	 * Check if customer has a Klarna account
	 * 
	 * @param string $countryIso3166Alpha2 
	 * @param string $pnoNumber
	 * @return ApiMessage
	 */
    public static function create($countryIso3166Alpha2, $pnoNumber)
    {
        // Create parameters
        $parameters = new stdClass;
        $parameters->KlarnaHasAccount             = new stdClass;
        $parameters->KlarnaHasAccount->Country    = (string) $countryIso3166Alpha2;
        $parameters->KlarnaHasAccount->PnoNumber  = (string) $pnoNumber;
        
        // Create request
        $request = new Request();
        $request->setParameters($parameters);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::KLARNA_HAS_ACCOUNT, $request);  
        
        // Validation
        if ($countryIso3166Alpha2 != null && !is_string($countryIso3166Alpha2))
        {
            $apiMessage->addValidationError('In method Api\Service\KlarnaHasAccount::create parameter countryIso3166Alpha2[' . $countryIso3166Alpha2 . '] is supposed to be a string and not a ' . gettype($countryIso3166Alpha2));
        }
        if ($pnoNumber != null && !is_string($pnoNumber))
        {
            $apiMessage->addValidationError('In method Api\Service\KlarnaHasAccount::create parameter pnoNumber[' . $pnoNumber . '] is supposed to be a string and not a ' . gettype($pnoNumber));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;              
    }
    
    /**
	 * Check if customer has Klarna account using API response
	 * 
	 * @param ApiResponse $response 
	 * @return bool
	 */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::KLARNA_HAS_ACCOUNT || $response->getApiReturnValues() == null)
		{ 
            return null;
        }
        
        if (isset($response->getApiReturnValues()->KlarnaHasAccount))
		{
            return $response->getApiReturnValues()->KlarnaHasAccount;
        }
        
        return null;
    }
}