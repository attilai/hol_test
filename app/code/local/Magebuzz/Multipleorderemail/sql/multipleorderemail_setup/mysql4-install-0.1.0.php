<?php
/*
 * Copyright (c) 2014 www.magebuzz.com
 */
$installer = $this;
$installer->startSetup();
$installer->run("
  CREATE TABLE {$this->getTable('multipleorderemail_rule')} (
  `rule_id` int(10) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL , 
  `actions_serialized` text NOT NULL,
  `description` text NULL,
  `title` varchar(50) NOT NULL,
  `user_group` text NOT NULL,
  `sort_order` int(10) NULL,
  `template_id` int(10) NOT NULL,
  `created_time` datetime NULL,
    PRIMARY KEY  (`rule_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  
    ");
$installer->endSetup(); 