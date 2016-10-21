<?php

namespace PayCheckout\Api\Service\ModuleVersionInfo;
use PayCheckout\Json\JsonBase;

class ModuleVersionInfo extends JsonBase
{
    /** 
     *  @var int
     */
    protected $versionIsCurrent;   
    
    /**
     * @var string
     */
    protected $newestVersion;
       
    /**
     * @return int
     */
    public function getVersionIsCurrent()
    {
        return $this->versionIsCurrent;
    }
    
    /**
     * @return string
     */
    public function getNewestVersion()
    {
        return $this->newestVersion;
    }   
}
