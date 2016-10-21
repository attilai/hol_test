<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Currency;
use PayCheckout\Json\Request\Request;
use PayCheckout\Api\Service\AvailablePaymentMethod\AvailablePaymentMethod;
use PayCheckout\ApiResponse;
use PayCheckout\ApiResult;
use stdClass;

class GetAvailablePaymentMethods
{    
    /**
     * Get AvailablePaymentMethods for the given amount and currency
     * 
     * @param int $amount
     * @param int $currency
     * @param string $customerIpAddress
     * @param string $cultureInfo (optional when not specified cultureInfo of WebShop is used)
     * @param bool $enforceNoVAT
     * @return ApiMessage
     */
    public static function create($amount, $currency, $customerIpAddress, $cultureInfo = null, $enforceNoVAT = null)
    {
        // Create parameters
        $parameters = new stdClass;
        $parameters->GetAvailablePaymentMethods = new stdClass;
        $parameters->GetAvailablePaymentMethods->Amount             = (string) $amount;
        $parameters->GetAvailablePaymentMethods->Currency           = (int)    $currency;
        $parameters->GetAvailablePaymentMethods->CustomerIpAddress  = (string) $customerIpAddress;
        
        if ($cultureInfo != null)
        {
            $parameters->GetAvailablePaymentMethods->CultureInfo  = (string) $cultureInfo;
        }
        if ($enforceNoVAT)
        {
            $parameters->GetAvailablePaymentMethods->EnforceNoVAT  = 'True';
        }
        
        // Create request
        $request = new Request();
        $request->setParameters($parameters);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::GET_AVAILABLE_PAYMENT_METHODS, $request);
        
        // Validation
        if ($amount != null && !HelpFunction::is32bitInt($amount))
        {
            $apiMessage->addValidationError('In method Api\Service\GetAvailablePaymentMethods::create parameter amount[' . $amount . '] is supposed to be a 32 bit integer and not a ' . gettype($amount));
        }
        if ($currency != null && !in_array($currency, Currency::$allCurrencies))
        {
            $apiMessage->addValidationError('In method Api\Service\GetAvailablePaymentMethods::create parameter currency[' . $currency . '] is supposed to be one of the predefined currencies');
        }
        if ($customerIpAddress != null && !is_string($customerIpAddress))
        {
            $apiMessage->addValidationError('In method Api\Service\GetAvailablePaymentMethods::create parameter customerIpAddress[' . $customerIpAddress . '] is supposed to be a string and not a ' . gettype($customerIpAddress));
        }
        if ($cultureInfo != null && !is_string($cultureInfo))
        {
            $apiMessage->addValidationError('In method Api\Service\GetAvailablePaymentMethods::create parameter cultureInfo[' . $cultureInfo . '] is supposed to be a string and not a ' . gettype($cultureInfo));
        }
        if ($enforceNoVAT != null && !is_bool($enforceNoVAT))
        {
            $apiMessage->addValidationError('In method Api\Service\GetAvailablePaymentMethods::create parameter enforceNoVAT[' . $enforceNoVAT . '] is supposed to be a bool and not a ' . gettype($enforceNoVAT));
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;
    }
  
    /**
     * Extract ModuleVersionInfo out of the API response class
     * 
     * @param ApiResponse $response 
     * @return AvailablePaymentMethod[]|null
     */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::GET_AVAILABLE_PAYMENT_METHODS || $response->getApiReturnValues() == null)
        {
            return null;
        }
        
        foreach ($response->getApiReturnValues() as $key => $value)
		{
            if ($value !== null && strpos($key,'GetAvailablePaymentMethods') !== false)
			{
				$data = json_decode($value->json, false, 512, JSON_BIGINT_AS_STRING);    
				
                if (is_array($data))
                {
                    $result = null;
                    foreach ($data as $itemValues)
                    {
                        // Check if item is an object
                        if (is_object($itemValues))
                        {
                            // Create new item and add to items
                            $item = new AvailablePaymentMethod();
                            $item->jsonDeserialize($itemValues);
							
                            $result[] = $item;
                        }
                    }
                    return $result;
                }
			}
        }       
        return null;
    }
    
    
}
