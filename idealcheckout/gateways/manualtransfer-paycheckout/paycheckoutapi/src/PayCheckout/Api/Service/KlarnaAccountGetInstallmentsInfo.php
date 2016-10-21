<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\ApiResult;
use PayCheckout\ApiResponse;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Api\Service\Klarna\InstallmentInfo;
use PayCheckout\Api\Service\Klarna\KlarnaInstallmentCalculationTarget;
use PayCheckout\Currency;
use PayCheckout\Json\Request\Request;
use stdClass;

class KlarnaAccountGetInstallmentsInfo
{
    /**
	 * Get installments info from Klarna Account
	 * 
	 * @param string $countryIso3166Alpha2 
	 * @param int $currency 
	 * @param int $checkoutAmount
	 * @param int $target
	 * @return ApiMessage
	 */
    public static function create($countryIso3166Alpha2, $currency, $checkoutAmount, $target = KlarnaInstallmentCalculationTarget::ON_CHECKOUT_PAGE)
    {
        // Create parameters
        $parameters = new stdClass;
        $parameters->KlarnaAccountGetInstallmentsInfo				= new stdClass;
        $parameters->KlarnaAccountGetInstallmentsInfo->Amount       = (string) $checkoutAmount;
        $parameters->KlarnaAccountGetInstallmentsInfo->Country      = (string) $countryIso3166Alpha2;
        $parameters->KlarnaAccountGetInstallmentsInfo->Currency		= (string) $currency;
        $parameters->KlarnaAccountGetInstallmentsInfo->OnCheckout	= $target == KlarnaInstallmentCalculationTarget::ON_CHECKOUT_PAGE ? 1 : 0;
        
        // Create request
        $request = new Request();
        $request->setParameters($parameters);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::KLARNA_ACCOUNT_GET_INSTALLMENTS_INFO, $request);  
        
        // Validation
        if ($checkoutAmount != null && !HelpFunction::is32bitInt($checkoutAmount))
        {
            $apiMessage->addValidationError('In method Api\Service\KlarnaAccountGetInstallmentsInfo::create parameter checkoutAmount[' . $checkoutAmount . '] is supposed to be a 32 bit integer and not a ' . gettype($checkoutAmount));
        }
        if ($countryIso3166Alpha2 != null && !is_string($countryIso3166Alpha2))
        {
            $apiMessage->addValidationError('In method Api\Service\KlarnaAccountGetInstallmentsInfo::create parameter countryIso3166Alpha2[' . $countryIso3166Alpha2 . '] is supposed to be a string and not a ' . gettype($countryIso3166Alpha2));
        }
        if ($currency != null && !in_array($currency, Currency::$allCurrencies))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccountGetInstallmentsInfo::create parameter currency[' . $currency . '] is supposed to be one of the predefined currencies');
        }       
        // End validation
        
        // return apiMessage
        return $apiMessage;               
    }
    
    /**
	 * Get account data for checkout from Klarna using API response
	 * 
	 * @param ApiResponse $response 
	 * @return InstallmentInfo[]|null
	 */
    public static function response(ApiResponse $response)
    {
        if ($response->getApiResult() != ApiResult::SUCCESS || $response->getActionPerformed() != ApiAction::KLARNA_ACCOUNT_GET_INSTALLMENTS_INFO || $response->getApiReturnValues() == null)
        {
            return null;
        }
        
        $installments = array();
		
        foreach ($response->getApiReturnValues() as $r)
        {
            $installments[] = new InstallmentInfo(
                $r->Description,
                intval($r->TotalInstallments),
                floatval($r->MonthlyAmount),
                intval($r->PClassId),
                intval($r->Currency)
            );
        }
		
        return $installments;
    }
}