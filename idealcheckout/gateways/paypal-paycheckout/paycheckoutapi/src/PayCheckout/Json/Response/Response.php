<?php

namespace PayCheckout\Json\Response;

use PayCheckout\Json\JsonBase;
use PayCheckout\Json\Request\Transaction;
use PayCheckout\Json\Generic\Order;

class Response extends JsonBase
{
    /**
     * @var int
     */
    protected $apiResult;
    
    /**
     * @var int
     */
    protected $actionPerformed;
    
    /**
     * @var int|string
     */
    protected $traceReference;
    
    /**
	 * @var int|string
     */
    protected $paymentReference;
    
    /**
	 * @var int|string
     */
    protected $transactionReference;
    
    /**
     * @var int
     */
    protected $errorCode;
    
    /**
     * @var string[]
     */
    protected $errors;
    
    /**
     * @var string[]
     */
    protected $warnings;
    
    /**
     * @var string
     */
    protected $errorToShowToConsumer;
    
    /**
     * @var string
     */
    protected $redirectInfo;
    
    /**
     * @var TransactionResult
     */
    protected $transactionResult;
    
    /**
     * @var mixed
     */
    protected $apiReturnValues;
    
    /**
     * Create new response
     * 
     * @param int $apiResult 
     * @param int $errorCode 
     * @param array $errors 
     */
    public function __construct($apiResult = null, $errorCode = null, $errors = null)
    {
        $this->apiResult    = $apiResult;
        $this->errorCode    = $errorCode;
        $this->errors       = $errors;
        $this->warnings     = null;
    }
    
    /**
     * @return int
     */
    public function getApiResult()
    {
        return $this->apiResult;
    }
    
    /**
     * @param int $apiResult
     */
    public function setApiResult($apiResult)
    {
        $this->apiResult = $apiResult;
    }
    
    /**
     * @return int
     */
    public function getActionPerformed()
    {
        return $this->actionPerformed;
    }
    
    /**
     * @param int $actionPerformed 
     */
    public function setActionPerformed($actionPerformed)
    {
        $this->actionPerformed = $actionPerformed;
    }
    
    /**
	 * @return int|string
     */
    public function getTraceReference()
    {
        return $this->traceReference;
    }
    
    /**
	 * @param int|string $traceReference
     */
    public function setTraceReference($traceReference)
    {
        $this->traceReference = $traceReference;
    }
    
    /**
	 * @return int|string
     */
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }
    
    /**
	 * @param int|string $paymentReference
     */
    public function setPaymentReference($paymentReference)
    {
        $this->paymentReference = $paymentReference;
    }
    
    /**
	 * @return int|string
     */
    public function getTransactionReference()
    {
        return $this->transactionReference;
    }
        
    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    
    /**
     * @param int $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }
    
    /**
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * @param string[] $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
	
	/**
     * Add an error
     * 
     * @param string $error 
     * @param array|null $args 
     */
    public function addError($error, array $args = null)
    {
        if ($args == null)
        {
            $this->errors[] = $error;
        }
        else
        {
            $this->errors[] = sprintf($error, $args);
        }
	}
    
    /**
     * @return string[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }
    
    /**
     * @param string[] $warnings
     */
    public function setWarnings(array $warnings)
    {
        $this->warnings = $warnings;
    }
	
    /**
     * Add a warning
     * 
     * @param string $warning 
     * @param array|null $args 
     */
    public function addWarning($warning, array $args = null)
    {
        if ($args == null)
        {
            $this->warnings[] = $warning;
        }
        else
        {
            $this->warnings[] = sprintf($warning, $args);
        }
    }

    /**
     * @return string
     */
    public function getErrorToShowToConsumer()
    {
        return $this->errorToShowToConsumer;
    }
    
    /**
     * @param string $errorToShowToConsumer
     */
    public function setErrorToShowToConsumer($errorToShowToConsumer)
    {
        $this->errorToShowToConsumer = $errorToShowToConsumer;
    }
    
    /**
     * @return string
     */
    public function getRedirectInfo()
    {
        return $this->redirectInfo;
    }
    
    /**
     * @param string $redirectInfo
     */
    public function setRedirectInfo($redirectInfo)
    {
        $this->redirectInfo = $redirectInfo;
    }
    
    /**
     * @return TransactionResult
     */
    public function getTransactionResult()
    {
        return $this->transactionResult;
    }
    
    /**
     * @param TransactionResult $transactionResult
     */
    public function setTransactionResult(TransactionResult $transactionResult)
    {
        $this->transactionResult = $transactionResult;
    }
    
    /**
     * @return mixed
     */
    public function getApiReturnValues()
    {
        return $this->apiReturnValues;
    }
    
    /**
     * @param mixed $apiReturnValues
     */
    public function setApiReturnValues($apiReturnValues)
    {
        $this->apiReturnValues = $apiReturnValues;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function setJsonData($name, $value)
    {
        if (is_object($value))
        {
            switch($name)
            {
                case 'transactionResult':
                    $this->transactionResult = new TransactionResult();
                    $this->transactionResult->jsonDeserialize($value);
                    break;
                case 'apiReturnValues':
                    $this->setApiReturnValues($value);
                    break;
            }
        }
        else
        {
            parent::setJsonData($name, $value);
        }
    }
}