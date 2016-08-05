<?php

/*
* Copyright (c) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Block_Adminhtml_Multipleorderemail extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_multipleorderemail';
    $this->_blockGroup = 'multipleorderemail';
    $this->_headerText = Mage::helper('multipleorderemail')->__('Order Email Template Manager');
    $this->_addButtonLabel = Mage::helper('multipleorderemail')->__('Add Order Template');
    parent::__construct();
  }
}