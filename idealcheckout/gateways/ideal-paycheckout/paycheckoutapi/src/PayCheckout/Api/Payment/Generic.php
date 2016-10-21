<?php

namespace PayCheckout\Api\Payment;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Transaction;
use PayCheckout\Json\Request\Request;
use PayCheckout\Currency;
use stdClass;

class Generic
{
    /**
     * Create new generic payment
     * 
     * @param int    $paymentMethod 
     * @param string $merchantOrderReference 
     * @param string $description 
     * @param int    $amount 
     * @param int    $currency 
     * @param string $customerIpAddress 
     * @param string $configuredCultureOverride 
     * @param int    $expiryTimeInMinutesOverride 
     * @param array  $parameters 
     * @return ApiMessage
     */
    public static function create($paymentMethod,$merchantOrderReference, $description, $amount, $currency,$customerIpAddress, $configuredCultureOverride = null , $expiryTimeInMinutesOverride = null, array $parameters = null)
    {
        // Create transaction
        $transaction = new Transaction\Transaction(
            $paymentMethod,
            $currency,
            $amount,
            $merchantOrderReference,
            $description,
            $customerIpAddress,
            $configuredCultureOverride,
            null
        );
        
        // Create request
        $request = new Request();
        $request->setTransaction($transaction);

        $apiMessage = new ApiMessage(ApiAction::PAYMENT, $request);

        $genParameters  = new stdClass;
        $dictParameters = new stdClass;
        // Add parameter expiry if applicable
        if ($expiryTimeInMinutesOverride != null && $expiryTimeInMinutesOverride > 0)
        {
            $genParameters->ExpiryTimeInMinutesOverride = (string) $expiryTimeInMinutesOverride;
        }

        // Iterate thru parameters and set if present
        if ($parameters != null)
        {
            if (!is_array($parameters))
            {
                $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter parameters[' . $parameters . '] is supposed to be an associated array of key/values');
            }
            else
            {
                foreach ($parameters as $key => $value)
                {
                    if (is_string($key) && is_string($value))
                    {
                        $dictParameters->$key = $value;
                        continue;
                    }
                    if (!is_string($key))
                    {
                        $apiMessage->addValidationError('In method Api\Payment\Generic::create associative array parameter has key[' . $key . '] which is supposed to be a string and not a ' . gettype($key));
                    }
                    if (!is_string($value))
                    {
                        $apiMessage->addValidationError('In method Api\Payment\Generic::create associative array parameter has value[' . $value . '] which is supposed to be a string and not a ' . gettype($value));
                    }
                }
            }
        }

        $genParameters->GenericPayment = (object) $dictParameters;
        $request->setParameters($genParameters);


        // Validate
        if ($merchantOrderReference != null && !is_string($merchantOrderReference))
        {
            $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter merchantOrderReference[' . $merchantOrderReference . '] is supposed to be a string and not a ' . gettype($merchantOrderReference));
        }
        if ($description != null && !is_string($description))
        {
            $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter description[' . $description . '] is supposed to be a string and not a ' . gettype($description));
        }
        if ($amount != null && !HelpFunction::is32bitInt($amount))
        {
            $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter amount[' . $amount . '] is supposed to be a 32 bit integer and not a ' . gettype($amount));
        }
        if ($currency != null && !in_array($currency, Currency::$allCurrencies))
        {
            $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter currency[' . $currency . '] is supposed to be one of the predefined currencies');
        }
        if ($customerIpAddress != null && !is_string($customerIpAddress))
        {
            $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter description[' . $customerIpAddress . '] is supposed to be a string and not a ' . gettype($customerIpAddress));
        }
        if ($paymentMethod != null && !HelpFunction::is32bitInt($paymentMethod))
        {
            $apiMessage->addValidationError('In method Api\Payment\Generic::create parameter paymentMethod[' . $paymentMethod . '] is supposed to be a 32 bit integer and not a ' . gettype($paymentMethod));
        }
        if ($configuredCultureOverride != null && !is_string($configuredCultureOverride))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter configuredCultureOverride[' . $configuredCultureOverride . '] is supposed to be a string and not a ' . gettype($configuredCultureOverride));
        }
        if ($expiryTimeInMinutesOverride != null && !HelpFunction::is32bitInt($expiryTimeInMinutesOverride))
        {
            $apiMessage->addValidationError('In method Api\Payment\Hosted::create parameter expiryTimeInMinutesOverride[' . $expiryTimeInMinutesOverride . '] is supposed to be a 32 bit integer and not a ' . gettype($expiryTimeInMinutesOverride));
        }
        // End of validation
        
        // return API message
        return $apiMessage;
    }
}