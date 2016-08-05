<?php
  
  $installer = $this;
 
  $installer->startSetup();
 
  $installer->run("
 
    DROP TABLE IF EXISTS {$this->getTable('orderexport')};
 
    CREATE TABLE {$this->getTable('orderexport')} (
      `orderexport_id` int(11) unsigned NOT NULL auto_increment,
      `order_id` int(11) unsigned NOT NULL default 0,
      `order_status` varchar(20) NOT NULL default '',
      `order_type` varchar(20) NOT NULL default '',
      `export_status` varchar(20) NOT NULL default '',
      `export_result` text NOT NULL default '',
      `created_time` datetime NULL,
      `update_time` datetime NULL,
      PRIMARY KEY (`orderexport_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
  ");
 
  $installer->endSetup();