<?php
/*
* Copyright (c) 2014 www.magebuzz.com
*/
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE {$this->getTable('multipleorderemail_rule')} ADD `shipping_methods` text NOT NULL;
ALTER TABLE {$this->getTable('multipleorderemail_rule')} ADD `payment_methods` text NOT NULL; 
ALTER TABLE {$this->getTable('multipleorderemail_rule')} ADD `template_invoice_id` int(10) NOT NULL;
ALTER TABLE {$this->getTable('multipleorderemail_rule')} ADD `template_shipment_id` int(10) NOT NULL; 
");
$installer->endSetup(); 