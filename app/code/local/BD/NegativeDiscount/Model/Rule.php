<?php
/**
 * Rule geextend om controle expression in functie te disablen.
 *
 * @category    Mage
 * @package     Mage_Rule
 */

class BD_NegativeDiscount_Model_Rule extends Mage_SalesRule_Model_Rule
{
	/**
     * Prepare data before saving
     *
     * @return Mage_Rule_Model_Rule
     */
    protected function _beforeSave()
    {
        // check if discount amount > 0



        if ($this->getConditions()) {
            $this->setConditionsSerialized(serialize($this->getConditions()->asArray()));
            $this->unsConditions();
        }
        if ($this->getActions()) {
            $this->setActionsSerialized(serialize($this->getActions()->asArray()));
            $this->unsActions();
        }

        $this->_prepareWebsiteIds();

        if (is_array($this->getCustomerGroupIds())) {
            $this->setCustomerGroupIds(join(',', $this->getCustomerGroupIds()));
        }
        //parent::_beforeSave();
    }
}