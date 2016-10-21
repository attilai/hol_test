<?php

namespace PayCheckout\Api\Service;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;
use PayCheckout\Api\HelpFunction;
use PayCheckout\Json\Request\Request;
use PayCheckout\Json\Parameter\RefundCost;
use PayCheckout\Json\Generic\Invoice\TrackAndTraceInfo;
use stdClass;

class UpdateOrder
{
    /**
	 * Update an order
	 * 
	 * @param string $paymentReference
	 * @param string $invoiceNumber
	 * @param string $refundInvoiceTransaction
	 * @param string $refundCreditNumber
	 * @param RefundCost|null $refundCost
     * @param TrackAndTraceInfo|null $trackAndTraceInfo
     * @param array  $parameters 
	 * @return ApiMessage
	 */
    public static function create($paymentReference, $invoiceNumber = null, $refundInvoiceTransaction = null, $refundCreditNumber = null, RefundCost $refundCost = null, TrackAndTraceInfo $trackAndTraceInfo = null, $parameters = null)
    {
        // Create request
        $request = new Request();
        $request->setPaymentReference($paymentReference);

        // Create parameters
        $allParameters = new stdClass;
        $allParameters->UpdateOrder = new stdClass;

        if ($invoiceNumber != null || $refundInvoiceTransaction != null || $refundCreditNumber != null || $refundCost != null || $trackAndTraceInfo != null || $parameters != null)
		{
			if ($invoiceNumber != null)
			{
                $allParameters->UpdateOrder->InvoiceNumber = (string) $invoiceNumber;
			}

			if ($refundInvoiceTransaction != null)
			{
                $allParameters->UpdateOrder->RefundInvoiceTransaction = (string) $refundInvoiceTransaction;
			}

			if ($refundCreditNumber != null)
			{
                $allParameters->UpdateOrder->RefundCreditNumber = (string) $refundCreditNumber;
			}

			if ($refundCost != null)
			{
                $allParameters->UpdateOrder->RefundCost = json_encode($refundCost->jsonSerialize());
			}

            if ($trackAndTraceInfo != null)
			{
                $allParameters->UpdateOrder->TrackAndTraceInfo = json_encode($trackAndTraceInfo->jsonSerialize());
			}
		}
		
        // Create ApiMessage
        $apiMessage = new ApiMessage(ApiAction::UPDATE_ORDER, $request);  

        // Iterate thru parameters and set if present
        if ($parameters != null)
        {
            $dictParameters = new stdClass;

            if (!is_array($parameters))
            {
                $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter parameters[' . $parameters . '] is supposed to be an associated array of key/values');
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
                        $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create associative array parameter has key[' . $key . '] which is supposed to be a string and not a ' . gettype($key));
                    }
                    if (!is_string($value))
                    {
                        $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create associative array parameter has value[' . $value . '] which is supposed to be a string and not a ' . gettype($value));
                    }
                }
            }
            $allParameters->UpdateOrder->Parameters = $dictParameters;
        }

        // Set parameters
        $request->setParameters($allParameters);

        // Validation
        if ($paymentReference != null && !HelpFunction::isTypeValidForReference($paymentReference))
        {
            $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter paymentReference[' . $paymentReference . '] is supposed to be a 64 bit integer or a string and not a ' . gettype($paymentReference));
        }
        if ($invoiceNumber != null && !is_string($invoiceNumber))
        {
            $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter invoiceNumber[' . $invoiceNumber . '] is supposed to be a string and not a ' . gettype($invoiceNumber));
        }
        if ($refundInvoiceTransaction != null && !is_string($refundInvoiceTransaction))
        {
            $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundInvoiceTransaction[' . $refundInvoiceTransaction . '] is supposed to be a string and not a ' . gettype($refundInvoiceTransaction));
        }
        if ($refundCreditNumber != null && !is_string($refundCreditNumber))
        {
            $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCreditNumber[' . $refundCreditNumber . '] is supposed to be a string and not a ' . gettype($refundCreditNumber));
        }
        if ($refundCost != null)     
        {
            if (!($refundCost instanceof RefundCost))
            {
                $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCost[' . ((string)$refundCost) . '] is supposed to be an instance of class RefundCost');
            }
            else
            {
                if ($refundCost->getCostName() != null && !is_string($refundCost->getCostName()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCost membervariable costName[' . $refundCost->getCostName() . '] is supposed to be a string value');
                }
                if ($refundCost->getCostDescription() != null && !is_string($refundCost->getCostDescription()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCost membervariable costDescription[' . $refundCost->getCostDescription() . '] is supposed to be a string value');
                }
                if ($refundCost->getCostExclusiveVat() != null && !HelpFunction::is32bitInt($refundCost->getCostExclusiveVat()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCost membervariable costExclusiveVat[' . $refundCost->getCostExclusiveVat() . '] is supposed to be a 32 bit integer value');
                }
                if ($refundCost->getCostInclusiveVat() != null && !HelpFunction::is32bitInt($refundCost->getCostInclusiveVat()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCost membervariable costInclusiveVat[' . $refundCost->getCostInclusiveVat() . '] is supposed to be a 32 bit integer value');
                }
                if ($refundCost->getVatDisplayPercentage() != null && !HelpFunction::is32bitInt($refundCost->getVatDisplayPercentage()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter refundCost membervariable vatDisplayPercentage[' . $refundCost->getVatDisplayPercentage() . '] is supposed to be a 32 bit integer value');
                }
            }
        }
        if ($trackAndTraceInfo != null)
        {
            if (!($trackAndTraceInfo instanceof TrackAndTraceInfo))
            {
                $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo[' . ((string)$trackAndTraceInfo) . '] is supposed to be an instance of class TrackAndTraceInfo');
            }
            else
            {
                if ($trackAndTraceInfo->getTrackingNumber() != null && !is_string($trackAndTraceInfo->getTrackingNumber()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable trackingNumber[' . $trackAndTraceInfo->getTrackingNumber() . '] is supposed to be a string value');
                }
                if ($trackAndTraceInfo->getTrackingUrl() != null && !is_string($trackAndTraceInfo->getTrackingUrl()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable trackingUrl[' . $trackAndTraceInfo->getTrackingUrl() . '] is supposed to be a string value');
                }
                if ($trackAndTraceInfo->getShippingCompany() != null && !is_string($trackAndTraceInfo->getShippingCompany()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable shippingCompany[' . $trackAndTraceInfo->getShippingCompany() . '] is supposed to be a string value');
                }
                if ($trackAndTraceInfo->getShippingMethod() != null && !is_string($trackAndTraceInfo->getShippingMethod()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable shippingMethod[' . $trackAndTraceInfo->getShippingMethod() . '] is supposed to be a string value');
                }
                if ($trackAndTraceInfo->getReturnTrackingNumber() != null && !is_string($trackAndTraceInfo->getReturnTrackingNumber()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable returnTrackingNumber[' . $trackAndTraceInfo->getReturnTrackingNumber() . '] is supposed to be a string value');
                }
                if ($trackAndTraceInfo->getReturnTrackingUrl() != null && !is_string($trackAndTraceInfo->getReturnTrackingUrl()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable returnTrackingUrl[' . $trackAndTraceInfo->getReturnTrackingUrl() . '] is supposed to be a string value');
                }
                if ($trackAndTraceInfo->getReturnShippingCompany() != null && !is_string($trackAndTraceInfo->getReturnShippingCompany()) )
                {
                    $apiMessage->addValidationError('In method Api\Service\UpdateOrder::create parameter trackAndTraceInfo membervariable returnShippingCompany[' . $trackAndTraceInfo->getReturnShippingCompany() . '] is supposed to be a string value');
                }
            }
        }
        // End validation
        
        // return apiMessage
        return $apiMessage;                      
    }
}
