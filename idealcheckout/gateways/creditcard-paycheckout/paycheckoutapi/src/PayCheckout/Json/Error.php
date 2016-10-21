<?php

namespace PayCheckout\Json;

class Error
{
    /**
	 * Get message of JSON error
	 * 
	 * @see http://php.net/manual/en/function.json-last-error.php
	 * @param int $jsonError 
	 * @return string
	 */
    public static function getErrorMessage($jsonError)
    {
        switch ($jsonError)
        {
            case JSON_ERROR_NONE:
                return null;
            case JSON_ERROR_DEPTH:
                return 'The maximum stack depth has been exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Invalid or malformed JSON';
            case JSON_ERROR_CTRL_CHAR:
                return 'Control character error, possibly incorrectly encoded';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}