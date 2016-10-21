<?php

namespace PayCheckout\Api\Payment;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Currency;
use PayCheckout\Json\Request\Request;
use PayCheckout\PaymentMethod;
use PayCheckout\Json\Request\Transaction;

class PayPal
{
    /**
	 * Create new PayPal payment
	 * 
	 * @param string $merchantOrderReference
	 * @param string $description
	 * @param int $amount
	 * @param int $currencyToReceiveIn
     * @param string $customerIpAddress
	 * @param string $configuredCultureOverride
     * @param bool $enforceNoVAT
     * @return ApiMessage
	 */
    public static function create($merchantOrderReference, $description, $amount, $currencyToReceiveIn, $customerIpAddress, $configuredCultureOverride = null, $enforceNoVAT = null)
    {
        // Create transaction
        $transaction = new Transaction\Transaction(
            PaymentMethod::PAYPAL,
            $currencyToReceiveIn,
            $amount,
            $merchantOrderReference,
            $description,
            $customerIpAddress,
            $configuredCultureOverride,
            null,
            $enforceNoVAT
        );
        
        // Create request
        $request = new Request();
        $request->setTransaction($transaction);
        
        // Create apiMessage
        $apiMessage = new ApiMessage(ApiAction::PAYMENT, $request);
        
        // Validate
        if ($merchantOrderReference != null && !is_string($merchantOrderReference))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter merchantOrderReference[' . $merchantOrderReference . '] is supposed to be a string and not a ' . gettype($merchantOrderReference));
        }
        if ($description != null && !is_string($description))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter description[' . $description . '] is supposed to be a string and not a ' . gettype($description));
        }
        if ($amount != null && !HelpFunction::is32bitInt($amount))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter amount[' . $amount . '] is supposed to be a 32 bit integer and not a ' . gettype($amount));
        }
        if ($currencyToReceiveIn != null && !in_array($currencyToReceiveIn, Currency::$allCurrencies))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter currencyToReceiveIn[' . $currencyToReceiveIn . '] is supposed to be one of the predefined currencies');
        }
        if ($customerIpAddress != null && !is_string($customerIpAddress))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter customerIpAddress[' . $customerIpAddress . '] is supposed to be a string and not a ' . gettype($customerIpAddress));
        }
        if ($configuredCultureOverride != null && !is_string($configuredCultureOverride))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter configuredCultureOverride[' . $configuredCultureOverride . '] is supposed to be a string and not a ' . gettype($configuredCultureOverride));
        }
        if ($enforceNoVAT != null && !is_bool($enforceNoVAT))
        {
            $apiMessage->addValidationError('In method Api\Payment\PayPal::create parameter enforceNoVAT[' . $enforceNoVAT . '] is supposed to be a bool and not a ' .gettype($enforceNoVAT));
        }
        // End of validation
        
        // Return API message
        return $apiMessage;
    }
}