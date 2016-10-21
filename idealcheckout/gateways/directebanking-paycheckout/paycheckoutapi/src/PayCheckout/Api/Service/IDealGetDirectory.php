<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiResponse;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResult;

class IDealGetDirectory
{
    /**
     * Get list of ideal issuers
     * 
     * @return ApiMessage
     */
    public static function create()
    {        
        return new ApiMessage(
            ApiAction::IDEAL_GET_DIRECTORY
        );   
    }
    
    /**
     * Get list of ideal issuers per country using API response
     * 
     * @param ApiResponse $response
     * @return Ideal\Country[]|null
     */
    public static function response(ApiResponse $response)
    {        
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::IDEAL_GET_DIRECTORY || $response->getApiReturnValues() == null)
        { 
            return null;
        }
		
        $issuersPerCountry = array();
        
        foreach ($response->getApiReturnValues() as $countryName => $countryValue)
        {
            if ($countryValue !== null)
            {
                $issuers = array();
                
                foreach ($countryValue as $issuerName => $issuerValue)
                {
                    if (isset($issuerValue->Id))
                    {
                        $issuer = new Ideal\Issuer(
                            $issuerName,
                            $issuerValue->Id
                        );
                        $issuers[] = $issuer;
                    }                
                }
                
                $issuersPerCountry[$countryName] = new Ideal\Country(
                    $countryName,
                    $issuers
                );
            }
        }
        
        return $issuersPerCountry;
    }
}
