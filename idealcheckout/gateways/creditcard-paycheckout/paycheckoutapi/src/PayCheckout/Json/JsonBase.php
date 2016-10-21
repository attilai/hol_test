<?php

namespace PayCheckout\Json;

use DateTime;
use JsonSerializable;
use stdClass;

abstract class JsonBase implements JsonSerializable
{    
    /**
	 * Serializes the object to a value that can be serialized natively by json_encode().
	 * 
	 * @return stdClass
	 */
    public function jsonSerialize()
    {
        $data = new stdClass();
        
        foreach (get_object_vars($this) as $name => $value)
        {
            // Include only non-nullable values and non-empty arrays
            if ($value !== null && (!is_array($value) || count($value) > 0))
            {
                $name = ucfirst($name);
                
                // Format a DateTime according to ISO8601                
                if ($value instanceof DateTime)
                {
                    $data->$name = $value->format(DateTime::ISO8601);
                }
                else
                {
                    $data->$name = $value;
                }
            }
        }
        
        return $data;
    }
    
    /**
	 * Deserializes the object with values from StdClass obtained from json_decode().
	 * 
	 * @param mixed $data 
	 */
    public function jsonDeserialize($data)
    {
        if (is_object($data))
        {
            foreach ($data as $name => $value)
            {
                $name = lcfirst($name);
                $this->setJsonData($name, $value);
            }
        }
    }
    
    /**
	 * Set data for a property (name) obtained from JSON
	 * This will only work for non-objects, to support objects override this method and determine the desired class on your own.
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 */
    protected function setJsonData($name, $value)
    {
        if (!is_object($value))
        {		
            $this->{$name} = $value;
        }
    }
}