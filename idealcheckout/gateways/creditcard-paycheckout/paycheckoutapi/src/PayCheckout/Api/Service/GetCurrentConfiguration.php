<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResponse;
use PayCheckout\ApiResult;
use PayCheckout\Json\Generic\Configuration\Configuration;

class GetCurrentConfiguration
{
    /**
	 * Get current configuration
	 * 
	 * @return ApiMessage
	 */
    public static function create()
    {
        return new ApiMessage(
            ApiAction::GET_CURRENT_CONFIGURATION
        );   
    }
    
    /**
	 * Get current configuration using API response
	 * 
	 * @param ApiResponse $response 
	 * @return Configuration|null
	 */
    public static function response(ApiResponse $response)
    {        
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::GET_CURRENT_CONFIGURATION || $response->getApiReturnValues() == null)
        { 
            return null;
        }
        
        foreach ($response->getApiReturnValues() as $key => $value)
		{
            if ($value !== null && strpos($key, 'CurrentConfiguration') !== false)
			{
				$data = json_decode($value->json, false, 512, JSON_BIGINT_AS_STRING);                    
				
				$configuration = new Configuration();
				$configuration->jsonDeserialize($data);

				return $configuration;
			}
        }
        
        return null;
    }
}
