<?php

namespace PayCheckout\Curl;

class Error
{    
    /**
	 * @var int
	 */
    private $code;
    
    /**
	 * @var string
	 */
    private $decription;
    
    /**
	 * Create new curl error
	 * 
	 * @param int $code 
	 * @param string $decription 
	 */
    public function __construct($code, $decription)
    {
        $this->code         = $code;
        $this->decription   = $decription;
    }
    
    /**
	 * @return int
	 */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
	 * @return string
	 */
    public function getDescription()
    {
        return $this->decription;
    }
}
