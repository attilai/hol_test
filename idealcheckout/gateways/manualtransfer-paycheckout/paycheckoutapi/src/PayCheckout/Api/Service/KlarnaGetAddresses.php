<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResult;
use PayCheckout\ApiResponse;
use PayCheckout\Api\Service\Klarna\KlarnaAddress;
use PayCheckout\Json\Request\Request;
use stdClass;

class KlarnaGetAddresses
{
    /**
	 * Get addresses of customer from Klarna
	 * 
	 * @param string $countryIso3166Alpha2 
	 * @param string $pnoNumber
	 * @return ApiMessage
	 */
    public static function create($countryIso3166Alpha2, $pnoNumber)
    {
        // Create parameters
        $parameters = new stdClass;
        $parameters->KlarnaGetAddresses             = new stdClass;
        $parameters->KlarnaGetAddresses->Country    = (string) $countryIso3166Alpha2;
        $parameters->KlarnaGetAddresses->PnoNumber  = (string) $pnoNumber;
        
        // Create request
        $request = new Request();
        $request->setParameters($parameters);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::KLARNA_GET_ADDRESSES, $request);  
        
        // Validation
        if ($countryIso3166Alpha2 != null && !is_string($countryIso3166Alpha2))
        {
            $apiMessage->addValidationError('In method Api\Service\KlarnaGetAddresses::create parameter countryIso3166Alpha2[' . $countryIso3166Alpha2 . '] is supposed to be a string and not a ' . gettype($countryIso3166Alpha2));
        }
        if ($pnoNumber != null && !is_string($pnoNumber))
        {
            $apiMessage->addValidationError('In method Api\Service\KlarnaGetAddresses::create parameter pnoNumber[' . $pnoNumber . '] is supposed to be a string and not a ' . gettype($pnoNumber));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;              
    }
    
    /**
	 * Get addresses from Klarna using API response
	 * 
	 * @param ApiResponse $response 
	 * @return KlarnaAddress[]|null
	 */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::KLARNA_GET_ADDRESSES || $response->getApiReturnValues() == null)
        {
            return null;
        }
		
		$addresses = array();

        if (isset($response->getApiReturnValues()->KlarnaGetAddresses))
		{
            foreach ($response->getApiReturnValues()->KlarnaGetAddresses as $address)
            {
                $addresses[] = new KlarnaAddress(
                    $address->FirstName, 
                    $address->LastOrCompanyName, 
                    $address->Address, 
                    $address->Postalcode, 
                    $address->City, 
                    $address->Country
                );
            }
        }
		
        return $addresses;
    }
}