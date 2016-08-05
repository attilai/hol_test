<?php

/*
* Copyright (c) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Model_Mysql4_Multipleorderemailrule_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
  public function _construct()
  {
    parent::_construct();
    $this->_init('multipleorderemail/multipleorderemailrule');
  }
}