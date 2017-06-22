<?php
/**
 *   ╲          ╱
 * ╭──────────────╮  COPYRIGHT (C) 2016 GINGER PAYMENTS B.V.
 * │╭──╮      ╭──╮│
 * ││//│      │//││
 * │╰──╯      ╰──╯│
 * ╰──────────────╯
 *   ╭──────────╮    The MIT License (MIT)
 *   │ () () () │
 *
 * @category    ING
 * @package     ING_PSP
 * @author      Ginger B.V. (info@gingerpayments.com)
 * @version     v1.1.2
 * @copyright   COPYRIGHT (C) 2016 GINGER PAYMENTS B.V. (https://www.gingerpayments.com)
 * @license     The MIT License (MIT)
 *
 **/

/** @var $this Mage_Catalog_Model_Resource_Setup */
$this->startSetup();

$this->addAttribute('quote', 'ingpsp_fee', array(
    'label'    => 'INGPSP fee',
    'visible'  => false,
    'required' => false,
    'type' => 'decimal',
    'position'     => 1,
));

$this->addAttribute('order', 'ingpsp_fee', array(
    'label'    => 'INGPSP fee',
    'visible'  => false,
    'required' => false,
    'type' => 'decimal',
    'position'     => 1,
));

$this->addAttribute('invoice', 'ingpsp_fee', array(
    'label'    => 'INGPSP fee',
    'visible'  => false,
    'required' => false,
    'type' => 'decimal',
    'position'     => 1,
));

/** @var $conn Varien_Db_Adapter_Pdo_Mysql */
$conn = $this->getConnection();

$conn->addColumn($this->getTable('sales/quote'), 'ingpsp_fee', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'nullable'  => true,
    'default'   => NULL,
    'comment'   => 'INGPSP Fee amount',
));

$conn->addColumn($this->getTable('sales/order'), 'ingpsp_fee', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'nullable'  => true,
    'default'   => NULL,
    'comment'   => 'INGPSP Fee amount',
));

$conn->addColumn($this->getTable('sales/invoice'), 'ingpsp_fee', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'nullable'  => true,
    'default'   => NULL,
    'comment'   => 'INGPSP Fee amount',
));

$this->endSetup();
