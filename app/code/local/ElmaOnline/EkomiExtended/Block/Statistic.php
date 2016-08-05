<?php   
class ElmaOnline_EkomiExtended_Block_Statistic extends Mage_Core_Block_Template{   

    const EKOMI_FEED_URL = 'http://api.ekomi.de/v3/getFeedback?auth=31501|WGkLM21n6HKxbRR1ktG1RuA2U&version=cust-1.0.0&type=csv&charset=utf-8';

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('ekomiextended/statistic.phtml');
        $feed_source = file_get_contents(self::EKOMI_FEED_URL);
        $feed_data = str_getcsv($feed_source, "\n");
        $counter = 0;
        $total = 0;
        foreach($feed_data as &$row) {
            $row = str_getcsv($row, ",");
            $total++;
            if($row[0] > strtotime("-1 year")) {
                $counter++;    
            }
            
        }
        $this->setFeedCount(array('year' => $counter, 'total' => $total));
    }

}