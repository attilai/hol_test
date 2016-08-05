<?php
/*
* Copyright (c) 2014 www.magebuzz.com
*/
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('multipleorderemail_store')};
CREATE TABLE {$this->getTable('multipleorderemail_store')} (                                
   `multipleorderemail_id` int(11)  NOT NULL ,
  `store_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 ");
$installer->endSetup(); 