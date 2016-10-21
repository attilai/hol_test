<?php

namespace PayCheckout\Api;

use \PayCheckout\Currency;
/**
 * Help functions
 *
 * 
 *
 */
class HelpFunction
{
	/**
	 * The largest integer supported for a 32 bit system.
	 */
	const INT_MAX_32_BIT = 2147483647;
	
	/**
	 * The size of an integer in bytes for a 64 bit system.
	 */
	const INT_SIZE_64_BIT = 8;
	
    /**
     * 
     * Retrieve the multiplyfactor used by the specified currency
     * 
     * @param  int $currency 
     * @return int
     */
    public static function getCurrencyMultiplyFactor($currency)
    {
        switch ($currency)
        {
            // Times 1
            case Currency::SEK:
            case Currency::C_BYR:
            case Currency::C_CVE:
            case Currency::C_DJF:
            case Currency::C_GNF:
            case Currency::C_IDR:
            case Currency::C_JPY:
            case Currency::C_KMF:
            case Currency::C_KRW:
            case Currency::C_PYG:
            case Currency::C_RWF:
            case Currency::C_SEK:
            case Currency::C_UGX:
            case Currency::C_VND:
            case Currency::C_VUV:
            case Currency::C_XAF:
            case Currency::C_XOF:
            case Currency::C_XPF:
                return 0;
            // Times 10
            case Currency::C_MRO:
                return 1;
            // Times 1000
            case Currency::C_BHD:
            case Currency::C_JOD:
            case Currency::C_KWD:
            case Currency::C_LYD:
            case Currency::C_OMR:
            case Currency::C_TND:
                return 3;
            default:
                // Times 100
                return 2;
        }
    }
    
	/**
	 * 
     * Retrieve the constant to multiply with for the given currency
     * 
	 * @param int $currency 
	 * @return int
	 */
	public static function getCurrencyMultiplyConstant($currency)
	{
		switch ($currency)
		{
            case Currency::SEK:
            case Currency::C_BYR:
            case Currency::C_CVE:
            case Currency::C_DJF:
            case Currency::C_GNF:
            case Currency::C_IDR:
            case Currency::C_JPY:
            case Currency::C_KMF:
            case Currency::C_KRW:
            case Currency::C_PYG:
            case Currency::C_RWF:
            case Currency::C_SEK:
            case Currency::C_UGX:
            case Currency::C_VND:
            case Currency::C_VUV:
            case Currency::C_XAF:
            case Currency::C_XOF:
            case Currency::C_XPF:
                return HelpFunction::getMultiplyConstant(0);
            case Currency::C_MRO:
                return HelpFunction::getMultiplyConstant(1);
            case Currency::C_BHD:
            case Currency::C_JOD:
            case Currency::C_KWD:
            case Currency::C_LYD:
            case Currency::C_OMR:
            case Currency::C_TND:
                return HelpFunction::getMultiplyConstant(3);
            default:
                return HelpFunction::getMultiplyConstant(2);
		}
	}	
    
	/**
	 * 
     * Retrieve the multiplication constant according to the specified factor
     * 
	 * @param int $factor 
	 * @return int|double
	 */
	public static function getMultiplyConstant($factor)
	{
		switch ($factor)
		{
			case 0: return 1;
			case 1: return 10;
			case 2: return 100;
			case 3: return 1000;
			case 4: return 10000;
			case 5: return 100000;
			case 6: return 1000000;
			case 7: return 10000000;
			case 8: return 100000000;
			default:
				return pow ( 10, $factor );
		}
	}
	
	/**
	 * Check if the type of the given reference is a valid type (64 bit int or string) for a reference.
	 * 
	 * @param mixed $reference 
	 * @return bool
	 */
	public static function isTypeValidForReference($reference)
	{
		return (is_int($reference) && self::is64bit()) || is_string($reference);
	}
	
	/**
	 * Check if the given value is a valid 32 bit int.
	 * 
	 * @param mixed $value 
	 * @return bool
	 */
	public static function is32bitInt($value)
	{
		return is_int($value) && $value <= self::INT_MAX_32_BIT;
	}
	
	/**
	 * Is this a 64 bit PHP build?
	 * 
	 * @return bool
	 */
	public static function is64bit()
	{
		return PHP_INT_SIZE === self::INT_SIZE_64_BIT;
	}
}
