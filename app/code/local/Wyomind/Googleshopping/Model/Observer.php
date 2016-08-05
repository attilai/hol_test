<?php

class Wyomind_Googleshopping_Model_Observer
{	
	
    /**
     * Cronjob expression configuration
     */
   
   public function scheduledGenerateCatalogs($schedule)
    {
        $errors = array();

        $collection = Mage::getModel('googleshopping/googleshopping')->getCollection();
        
        foreach ($collection as $googleshopping) {
            try {
                 $cron=(Mage::getModel('cron/schedule')->setCronExpr($googleshopping->getCronExpr())->trySchedule(time()));   
                //echo "id :: ".$datafeed->getFeedId()." :: executed ::".$cron."<br>";
                if($cron) $googleshopping->generateXml();
            }
            catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        } 

    }
}