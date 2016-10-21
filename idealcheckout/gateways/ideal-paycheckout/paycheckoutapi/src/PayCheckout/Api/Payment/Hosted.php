<?php

namespace PayCheckout\Api\Payment;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Transaction;
use PayCheckout\Json\Request\Request;
use PayCheckout\PaymentMethod;
use PayCheckout\Currency;
use stdClass;

class Hosted
{
    /**
	 * Create new hosted payment
	 * 
	 * @param string $merchantOrderReference
	 * @param string $description
	 * @param int    $amount
	 * @param int    $currency
     * @param array  $paymentMethods
     * @param int    $expiryTimeInMinutesOverride
     * @param string $configuredCultureOverride
     * @param int    $expiryTimeInMinutesOverride
     * @param bool   $enforceNoVAT
     * @return ApiMessage
	 */
    public static function create($merchantOrderReference, $description, $amount, $currency, array $paymentMethods = null, $configuredCultureOverride = null , $expiryTimeInMinutesOverride = null, $enforceNoVAT = null)
    {
        // Create transaction
        $transaction = new Transaction\Transaction(
            PaymentMethod::HOSTED,
            $currency,
            $amount,
            $merchantOrderReference,
            $description,
            null,
            $configuredCultureOverride,
            $paymentMethods,
            $enforceNoVAT
        );
        
        // Create request
        $request = new Request();
        $request->setTransaction($transaction);
        
        // Add parameter expiry if applicable
        if ($expiryTimeInMinutesOverride != null && $expiryTimeInMinutesOverride > 0)
        {
            $parameters = new stdClass;
            $parameters->ExpiryTimeInMinutesOverride = (string) $expiryTimeInMinutesOverride;
            $request->setParameters($parameters);
        }
        
        $apiMessage = new ApiMessage(ApiAction::PAYMENT, $request);

        // Validate
        if ($merchantOrderReference != null && !is_string($merchantOrderReference))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter merchantOrderReference[' . $merchantOrderReference . '] is supposed to be a string and not a ' . gettype($merchantOrderReference));
        }
        if ($description != null && !is_string($description))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter description[' . $description . '] is supposed to be a string and not a ' . gettype($description));
        }
        if ($amount != null && !HelpFunction::is32bitInt($amount))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter amount[' . $amount . '] is supposed to be a 32 bit integer and not a ' . gettype($amount));
        }
        if ($currency != null && !in_array($currency, Currency::$allCurrencies))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter currency[' . $currency . '] is supposed to be one of the predefined currencies');
        }
        if ($paymentMethods != null)
        {
            if (!is_array($paymentMethods))
            {
                $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter paymentMethods[' . $paymentMethods . '] is supposed to be an array of payment methods');
            }
            else
            {
                foreach ($paymentMethods as $paymentMethod )
                {
                    if (!HelpFunction::is32bitInt($paymentMethod))
                    {
                        $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter paymentMethods array element paymentMethod[' . $paymentMethod . '] is supposed to be a 32 bit integer and not a ' . gettype($paymentMethod));
                    }
                    else if (!in_array( $paymentMethod, PaymentMethod::$allHostedPaymentMethods))
                    {
                        $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter paymentMethods array element paymentMethod[' . $paymentMethod . '] is not in the list of valid hosted paymentMethods');
                    }
                }
                unset($paymentMethod);
            }
        }
        if ($configuredCultureOverride != null && !is_string($configuredCultureOverride))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter configuredCultureOverride[' . $configuredCultureOverride . '] is supposed to be a string and not a ' . gettype($configuredCultureOverride));
        }
        if ($expiryTimeInMinutesOverride != null && !HelpFunction::is32bitInt($expiryTimeInMinutesOverride))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter expiryTimeInMinutesOverride[' . $expiryTimeInMinutesOverride . '] is supposed to be a 32 bit integer and not a ' . gettype($expiryTimeInMinutesOverride));
        }
        if ($enforceNoVAT != null && !is_bool($enforceNoVAT))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter enforceNoVAT[' . $enforceNoVAT . '] is supposed to be a bool and not a ' .gettype($enforceNoVAT));
        }
        // End of validation
        
        // return API message
        return $apiMessage;
    }
}