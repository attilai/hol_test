<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Request;
use stdClass;

class Refund
{
    /**
	 * Refund an order (partially)
	 * 
	 * @param string $paymentReference
	 * @param int $amountInclVat 
	 * @param int $amountExclVat 
	 * @param int $vatDisplayPercentage 
	 * @param string $refundInvoiceTransaction 
	 * @param string $customerNote 
     * @param bool $processOffline
	 * @return ApiMessage
	 */
    public static function create($paymentReference, $amountInclVat, $amountExclVat, $vatDisplayPercentage, $refundInvoiceTransaction = null, $customerNote = null,$processOffline = null)
    {
        // Create request
        $request = new Request();
        $request->setPaymentReference($paymentReference);

        // Create parameters
        $parameters = new stdClass;
        $parameters->Refund                       = new stdClass;
        $parameters->Refund->AmountInclVat        = (string) $amountInclVat;
        $parameters->Refund->AmountExclVat        = (string) $amountExclVat;
        $parameters->Refund->VatDisplayPercentage = (string) $vatDisplayPercentage;
		
        if ($refundInvoiceTransaction != null)
		{
			$parameters->Refund->RefundInvoiceTransaction = (string) $refundInvoiceTransaction;
		}
        
		if ($customerNote != null)
		{
			$parameters->Refund->CustomerNote = (string) $customerNote;
		}

        if ($processOffline)
        {
			$parameters->Refund->ProccesOffline = 'True';
        }
		
        // Set parameters
        $request->setParameters($parameters);
        
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::REFUND, $request);  
        
        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        if ($amountInclVat != null && !HelpFunction::is32bitInt($amountInclVat))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter amountInclVat[' . $amountInclVat . '] is supposed to be a 32 bit integer and not a ' . gettype($amountInclVat));
        }
        if ($amountExclVat != null && !HelpFunction::is32bitInt($amountExclVat))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter amountExclVat[' . $amountExclVat . '] is supposed to be a 32 bit integer and not a ' . gettype($amountExclVat));
        }
        if ($vatDisplayPercentage != null && !HelpFunction::is32bitInt($vatDisplayPercentage))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter vatDisplayPercentage[' . $vatDisplayPercentage . '] is supposed to be a 32 bit integer and not a ' . gettype($vatDisplayPercentage));
        }       
        if ($refundInvoiceTransaction != null && !is_string($refundInvoiceTransaction))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter refundInvoiceTransaction[' . $refundInvoiceTransaction . '] is supposed to be a string and not a ' . gettype($refundInvoiceTransaction));
        }
        if ($customerNote != null && !is_string($customerNote))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter customerNote[' . $customerNote . '] is supposed to be a string and not a ' . gettype($customerNote));
        }       
        if ($processOffline != null && !is_bool($processOffline))
        {
            $apiMessage->addValidationError('In method Api\Service\Refund::create parameter processOffline[' . $processOffline . '] is supposed to be a bool and not a ' .gettype($processOffline));
        }       
        // End validation
        
        // return apiMessage
        return $apiMessage;                      
    }
}
