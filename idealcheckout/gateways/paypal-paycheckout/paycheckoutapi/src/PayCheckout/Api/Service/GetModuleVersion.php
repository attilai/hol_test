<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Json\Request\Request;
use PayCheckout\Api\Service\ModuleVersionInfo\ModuleVersionInfo;
use PayCheckout\ApiResponse;
use PayCheckout\ApiResult;
use stdClass;

class GetModuleVersion
{
    /**
     * Get ModuleVersionInfo for the given moduleName and version
     * 
     * @param string $moduleName 
     * @param string $currentVersion 
     * @return ApiMessage
     */
    public static function create($moduleName, $currentVersion)
    {
        // Create parameters
        $parameters = new stdClass;
        $parameters->GetModuleVersion = new stdClass;
        $parameters->GetModuleVersion->ModuleName       = (string) $moduleName;
        $parameters->GetModuleVersion->CurrentVersion   = (string) $currentVersion;
        
        // Create request
        $request = new Request();
        $request->setParameters($parameters);
           
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::GET_MODULE_VERSION, $request);  
        
        // Validation
        if ($moduleName != null && !is_string($moduleName))
        {
            $apiMessage->addValidationError('In method Api\Service\GetModuleVersion::create parameter moduleName[' . $moduleName . '] is supposed to be a string and not a ' . gettype($moduleName));
        }
        if ($currentVersion != null && !is_string($currentVersion))
        {
            $apiMessage->addValidationError('In method Api\Service\GetModuleVersion::create parameter currentVersion[' . $currentVersion . '] is supposed to be a string and not a ' . gettype($currentVersion));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;
        
    }
  
    /**
     * Extract ModuleVersionInfo out of the API response class
     * 
     * @param ApiResponse $response 
     * @return null|ModuleVersionInfo
     */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::GET_MODULE_VERSION || $response->getApiReturnValues() == null)
        {
            return null;
        }
        
        foreach ($response->getApiReturnValues() as $key => $value)
		{
            if ($value !== null && strpos($key,'GetModuleVersion') !== false)
			{
				$data = json_decode($value->json, false, 512, JSON_BIGINT_AS_STRING);    
				
				$moduleVersionInfo = new ModuleVersionInfo();
				$moduleVersionInfo->jsonDeserialize($data);

				return $moduleVersionInfo;
			}
        }       
        return null;
    }
    
    
}
