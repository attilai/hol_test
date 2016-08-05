<?php
class TerraPreta_GoogleAnalytics_Block_Ga extends Mage_GoogleAnalytics_Block_Ga
{

     function _getPageTrackingCode($accountCid){
         Mage::getStoreConfig('google/tracking/another_code_cid');
     } 

}