<?php

namespace PayCheckout\Api\Payment;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Currency;
use PayCheckout\Json\Request\Transaction;
use PayCheckout\Json\Request\Request;
use PayCheckout\PaymentMethod;

class KlarnaAccount
{
    /**
	 * Create new Klarna Account payment
	 * 
	 * @param string $merchantOrderReference
	 * @param string $description
	 * @param int $amount 
	 * @param int $currency
     * @param string $customerIpAddress
	 * @param int $pClassId
     * @param bool $enforceNoVAT
     * @return ApiMessage
	 */
    public static function create($merchantOrderReference, $description, $amount, $currency, $customerIpAddress, $pClassId, $enforceNoVAT = null)
    {
        // Create Klarna transaction
        $klarna = new Transaction\Klarna(
            $pClassId
        );
        
        // Create transaction
        $transaction = new Transaction\Transaction(
            PaymentMethod::KLARNAACCOUNT,
            $currency,
            $amount,
            $merchantOrderReference,
            $description,
            $customerIpAddress,
            null,
            null,
            $enforceNoVAT
        );
        $transaction->setKlarna($klarna);
              
        // Create request
        $request = new Request();
        $request->setTransaction($transaction);
        
        // Create apiMessage
        $apiMessage = new ApiMessage(ApiAction::PAYMENT, $request);
        
        // Validate
        if ($merchantOrderReference != null && !is_string($merchantOrderReference))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter merchantOrderReference[' . $merchantOrderReference . '] is supposed to be a string and not a ' . gettype($merchantOrderReference));
        }
        if ($description != null && !is_string($description))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter description[' . $description . '] is supposed to be a string and not a ' . gettype($description));
        }
        if ($amount != null && !HelpFunction::is32bitInt($amount))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter amount[' . $amount . '] is supposed to be a 32 bit integer and not a ' . gettype($amount));
        }
        if ($currency != null && !in_array($currency, Currency::$allCurrencies))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter currency[' . $currency . '] is supposed to be one of the predefined currencies');
        }
        if ($customerIpAddress != null && !is_string($customerIpAddress))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter customerIpAddress[' . $customerIpAddress . '] is supposed to be a string and not a ' . gettype($customerIpAddress));
        }
        if ($pClassId != null && !HelpFunction::is32bitInt($pClassId))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter pClassId[' . $pClassId . '] is supposed to be a 32 bit integer and not a ' . gettype($pClassId));
        }
        if ($enforceNoVAT != null && !is_bool($enforceNoVAT))
        {
            $apiMessage->addValidationError('In method Api\Payment\KlarnaAccount::create parameter enforceNoVAT[' . $enforceNoVAT . '] is supposed to be a bool and not a ' . gettype($enforceNoVAT));
        }
        // End of validation
        
        // Return API message
        return $apiMessage;
    }
}