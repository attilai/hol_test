<?php

namespace PayCheckout;

use Exception;
use PayCheckout\Json;
use PayCheckout\Json\ApiMessageSigned;
use PayCheckout\Json\Notification\Notification;

class ApiExecutor
{
    const SANDBOX_URL   = 'https://sandbox.paycheckout.com/api';
    const LIVE_URL      = 'https://secure.paycheckout.com/api';
    
    /**
	 * @var string
	 */
    private $encryptionPassword;
    
    /**
	 * @var IApiCommunication
	 */
    private $connection;
    
    /**
	 * @var int
	 */
    private $webshopId;
    
    /**
	 * @var $useSandbox
	 */
    private $useSandbox;

    /**
     * @var string
     */
    private $moduleInfo;
    
    /**
	 * Create new ApiExecutor
	 * 
	 * @param int $webshopId 
	 * @param string $encryptionPassword 
	 * @param bool $useSandbox 
     * @param string|null $moduleInfo
	 * @param IApiCommunication|null $connection 
	 */
    public function __construct($webshopId, $encryptionPassword, $useSandbox = false, $moduleInfo = null, IApiCommunication $connection = null)
    {
        $this->webshopId    = $webshopId;
        $this->moduleInfo   = $moduleInfo;
        
        if ($connection === null)
        {
            $this->connection = new ApiCommunication();
        }
        else
        {
            $this->connection = $connection;
        }
        
        $this->encryptionPassword   = $encryptionPassword;
        $this->useSandbox           = $useSandbox;
    }
    
    /**
     * @param string $encryptionPassword
     * @throws Exception If $encryptionPassword is not a string of the format xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
     */
    public function validateEncryptionPassword($encryptionPassword)
    {
        $validChars = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','-'];
        if (!is_string($encryptionPassword))
        {
            throw new Exception('encryptionPassword [' . $encryptionPassword . '] must be of type string and not of type ' . gettype($encryptionPassword));
        }
        else if (strlen($encryptionPassword) != 36 || $encryptionPassword[8] != '-' || $encryptionPassword[13] != '-' || $encryptionPassword[18] != '-' || $encryptionPassword[23] != '-')
        {
            throw new Exception('encryptionPassword [' . $encryptionPassword . '] is not in the format[xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx]');            
        }
        else
        {
            for($i = 0; $i < 36; $i++)
            {
                if (!in_array($encryptionPassword[$i],$validChars))
                {
                    throw new Exception('encryptionPassword [' . $encryptionPassword . '] contains invalid characters. Valid characters are lowercase letters, numbers and hyphens');
                }
            }
        }
    }
    
    /**
	 * Get use sandbox
	 * 
	 * @return bool
	 */
    public function getUseSandbox()
    {
        return $this->useSandbox;
    }
    
    /**
	 * Set use sandbox
	 * 
	 * @param bool $useSandbox 
	 */
    public function setUseSandbox($useSandbox)
    {
        $this->useSandbox = $useSandbox;
    }
    
    /**
	 * Execute an API call based on the given API message
	 * 
	 * @param ApiMessage $apiMessage 
	 * @return ApiResponse
	 */
    public function execute(ApiMessage $apiMessage)
    {
        try
        {
            // Assign webshop id
            $apiMessage->setWebshopId($this->webshopId);

            // Assign moduleInfo if 
            if ($this->moduleInfo != null)
            {
                if (!is_string($this->moduleInfo))
                {
                    $apiMessage->addValidationError('moduleInfo [' . $this->moduleInfo . '] must be of type string and not of type ' . gettype($this->moduleInfo));
                }
                else
                {
                    // Assign module info
                    $apiMessage->setModuleInfo($this->moduleInfo);
                }
            }

            try
            {
                $this->validateEncryptionPassword($this->encryptionPassword);
            }
            catch (Exception $e)
            {
                return new ApiResponse(ApiResult::FAILED,ErrorCode::REQUEST_CONTENT_VALIDATION_FAILED,array($e->getMessage()));
            }

            if ($apiMessage->getValidationError() != null)
            {
                return new ApiResponse(ApiResult::FAILED, ErrorCode::REQUEST_CONTENT_VALIDATION_FAILED, $apiMessage->getValidationError());
            }            
            
            // Secure request
            $apiMessageSigned = new ApiMessageSigned($apiMessage);
            $apiMessageSigned->sign($this->encryptionPassword);
            
            // Convert into JSON string
            $signedApiStr = $apiMessageSigned->getJson();
            
            if (json_last_error() !== JSON_ERROR_NONE)
            {
                return new ApiResponse(ApiResult::FAILED, ErrorCode::PAY_CHECKOUT_API_CALL_INVALID_RESPONSE, array(
                    sprintf('There was a parsing problem encoding the api class into a json string error: %s', Json\Error::getErrorMessage(json_last_error()))
                ));
            }

            // Perform request
            $result = $this->connection->doRequest($this->getUrlToUse(), 'post', 'application/json', $signedApiStr);
            return $this->analyseResult($result);
        }
        catch(Exception $e)
        {
            return new ApiResponse(ApiResult::FAILED, ErrorCode::PAY_CHECKOUT_API_CALL_INVALID_RESPONSE, array(
                sprintf('A fatal exception occured processing the request: %s', $e->getMessage())
            ));
        }
    }
    
    /**
	 * Analyse result of API call and return API response including all necessary information
	 * 
	 * @param ApiCommunicationResult $result 
	 * @return ApiResponse
	 */
    private function analyseResult(ApiCommunicationResult $result)
    { 
        /**
		 * In case the connection failed or showed a timeout at the connection level
		 */
        if ($result->getResponseCode() === null && $result->getStatus() !== null)
        {            
            $errorCode = $result->getStatus()->getCode() === CURLE_OPERATION_TIMEOUTED ? ErrorCode::PAY_CHECKOUT_API_CALL_TIMED_OUT : ErrorCode::CAN_NOT_CONNECT_WITH_PAY_CHECKOUT;
            
            return new ApiResponse(ApiResult::FAILED, $errorCode, array(
               sprintf('%s Status: %s', $this->getUrlToUse(), $result->getStatus()->getDescription())
           ));
        }
        
        /**
		 * Check content type
		 */
        if ($result->getContentType() === null)
        {
            $response = new ApiResponse(ApiResult::FAILED, ErrorCode::PAY_CHECKOUT_API_CALL_INVALID_RESPONSE, array(
                'Returned contentType in html response was not set'
            ));
            
            if ($result->getResponseCode() != 200)
            {
                $response->addError(sprintf('An unexpected responsecode was returned [%s]', $result->getResponseCode()));
            }
            
            // Copy trace reference if found in header
            if ($result->getTraceReference() !== null)
            {
                $response->setTraceReference($result->getTraceReference());
            }
            
            return $response;
        }
        
        /**
		 * Could be a normal formatted string containing an error
		 */
        if (substr($result->getContentType(), 0, 9) == 'text/html')
        {
            $response = new ApiResponse(ApiResult::FAILED, ErrorCode::PAY_CHECKOUT_API_CALL_INVALID_RESPONSE);
            
            if ($result->getResponseCode() != 200)
            {
                $response->addError(sprintf('An unexpected responsecode was returned [%s]', $result->getResponseCode()));
            }
            
            if ($result->getContent() !== null && $result->getContent() != '')
            {
                $response->addError($result->getContent());
            }
            
            // Copy trace reference if found in header
            if ($result->getTraceReference() !== null)
            {
                $response->setTraceReference($result->getTraceReference());
            }
            
            return $response;
        }
        
        /**
		 * Everything seems ok
		 */
        
        // Decode JSON
        $resultSigned = new ApiMessageSigned();
        $data = json_decode($result->getContent(), false, 512, JSON_BIGINT_AS_STRING);
        
        if (json_last_error() === JSON_ERROR_NONE)
        {
            $resultSigned->setJson($data);
        }
        else
        {
            $response = new ApiResponse(ApiResult::FAILED, ErrorCode::PAY_CHECKOUT_API_CALL_INVALID_RESPONSE, array(
                sprintf('There was a parsing problem decoding the json string [%s] into the api class error: %s', $result->getContent(), Json\Error::getErrorMessage(json_last_error()))
            ));
            
            // Copy trace reference if found in header
            if ($result->getTraceReference() !== null)
            {
                $response->setTraceReference($result->getTraceReference());
            }
            
            return $response;
        }
        
        if (!$resultSigned->verify($this->encryptionPassword))
        {
            if ($resultSigned->getApiMessage()->getResponse() === null)
            {
                $resultSigned->getApiMessage()->setResponse(new ApiResponse());
            }
            
            $resultSigned->getApiMessage()->getResponse()->setApiResult(ApiResult::FAILED);
            $resultSigned->getApiMessage()->getResponse()->setErrorCode(ErrorCode::PAY_CHECKOUT_API_CALL_RESPONSE_COMPROMISED);
            $resultSigned->getApiMessage()->getResponse()->addError('Response failed verification and should be considered as comprimised');
        }
        
        return ApiResponse::createFromJsonResponse($resultSigned->getApiMessage()->getResponse());
    }
    
    /**
	 * Process API notification
	 * 
	 * @param string $contentType 
	 * @param string $content 
	 * @throws Exception If something went wrong and no nofication could be retrieved.
	 * @return Notification
	 */
    public function processNotification($contentType, $content)
    {
        // Check for valid content type (JSON)
        if ($contentType != 'application/json') {
            throw new Exception('Process notification called with an invalid content type.');
        }
        
        // Decode JSON
        $data = json_decode($content, false, 512, JSON_BIGINT_AS_STRING);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Process notification called with invalid JSON.');  
        }
        
        // Create API message
        $resultSigned = new ApiMessageSigned();
        $resultSigned->setJson($data);
        
        // Verify API message
        if (!$resultSigned->verify($this->encryptionPassword))
		{
            // Verification failed
            throw new Exception('Verification of notification failed.');
        } else if ($resultSigned->getApiMessage()->getNotification() === null)
		{
            // No Notification object found
            throw new Exception('Notification URL requested but Notification object is missing.');  
        }

        // Return notification
        return $resultSigned->getApiMessage()->getNotification();
    }
    
    /**
	 * Get url to use
	 * 
	 * @return string
	 */
    private function getUrlToUse()
    {
        if ($this->useSandbox)
        {
            return self::SANDBOX_URL;
        }
        else
        {
            return self::LIVE_URL;
        }
    }
}