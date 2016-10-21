<?php

namespace PayCheckout\Json;

class ApiMessageSigned
{
    /**
	 * @var ApiMessage
	 */
    private $apiMessage;
    
    /**
	 * @var string
	 */
    private $securitySha256Hash;
    
    /**
	 * JSON string to verify (received from API)
	 * 
	 * @var string
	 */
    private $jsonStringToVerify;
    
    /**
	 * Create new signed API message based on the given API message
	 * 
	 * @param ApiMessage $apiMessage 
	 */
    public function __construct(ApiMessage $apiMessage = null)
    {
        $this->apiMessage = $apiMessage;
    }
    
    /**
	 * Get API message
	 * 
	 * @return ApiMessage
	 */
    public function getApiMessage()
    {
        return $this->apiMessage;
    }
    
    /**
	 * Sign message with the given password
	 * Serialize the actual ApiMessage class
	 * 
	 * @param string $passwordToSignWith
	 */
    public function sign($passwordToSignWith)
    {
        $this->securitySha256Hash = $this->calculateHash($this->getApiMessageJson(), $passwordToSignWith);
    }
    
    /**
	 * Verify message with the given password
	 * Verification is done with the received JSON string
	 * 
	 * @param string $passwordToVerifyWith
	 * @return bool
	 */
    public function verify($passwordToVerifyWith)
    {
        if ($this->jsonStringToVerify !== null && $this->securitySha256Hash !== null)
        {
            return $this->securitySha256Hash === $this->calculateHash($this->jsonStringToVerify, $passwordToVerifyWith);
        }
        
        return false;
    }
    
    /**
	 * Get JSON of signed API message
	 * 
	 * @return string
	 */
    public function getJson()
    {
        // First encoding API message
        $apiMessageJson = $this->getApiMessageJson();
        
        if (json_last_error() === JSON_ERROR_NONE)
        {
            // Encoding API message went well, encode signed API message
            return json_encode(array(
                'ApiMessageJson' => $apiMessageJson,
                'SecuritySha256Hash' => $this->securitySha256Hash
            ));
        }
        
        return null;
    }
    
    /**
	 * Set JSON of signed API message
	 * Note: parameters from API are always uppercase first
	 * 
	 * @param mixed
	 */
    public function setJson($data)
    {
        if (is_object($data))
        {
            // API message
            if (isset($data->ApiMessageJson))
            {
                // Decode data
                $decodedData = json_decode($data->ApiMessageJson, false, 512, JSON_BIGINT_AS_STRING);
                
                $this->apiMessage = new ApiMessage();
                $this->apiMessage->jsonDeserialize($decodedData);
                
                // Store original string for verification
                $this->jsonStringToVerify = $data->ApiMessageJson;
            }
            
            // Security SHA256 hash
            if (isset($data->SecuritySha256Hash))
            {
                $this->securitySha256Hash = $data->SecuritySha256Hash;
            }
        }
    }
    
    /**
	 * Calculate hash of base string with the given password
	 * 
	 * @param string $baseString
	 * @param string $passwordToHashWith 
	 * @return string
	 */
    private function calculateHash($baseString, $passwordToHashWith)
    {
        if ($this->getApiMessage() !== null)
        {
            $computedHash = hash('sha256', $baseString . $passwordToHashWith, true);   
            return base64_encode($computedHash);
        }
        
        return null;
    }
    
    /**
	 * Get JSON of API message
	 * 
	 * @return string|null
	 */
    private function getApiMessageJson()
    {
        if ($this->getApiMessage() !== null)
        {
            return json_encode($this->getApiMessage());
        }
        
        return null;
    }
}