<?php
/*
* Copyright (c) 2014 www.magebuzz.com
*/
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE {$this->getTable('multipleorderemail_rule')} ADD notification_email varchar(100) DEFAULT NULL; 
");
$installer->endSetup(); 