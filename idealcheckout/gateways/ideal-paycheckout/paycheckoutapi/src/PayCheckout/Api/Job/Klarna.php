<?php

namespace PayCheckout\Api\Job;

use PayCheckout\ApiAction;
use PayCheckout\ApiMessage;

class Klarna
{
    /**
	 * Update P Classes from Klarna
	 * 
	 * @return ApiMessage
	 */
    public static function updatePClasses()
    {
        return new ApiMessage(ApiAction::KLARNA_UPDATE_P_CLASSES);
    }
}