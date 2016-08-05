 <?php

 class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
  {

     public function __construct()
     {
          parent::__construct();
          $this->setId('googleshopping_tabs');
          $this->setDestElementId('edit_form');
          $this->setTitle('Google Shopping');
      }

      protected function _beforeToHtml()
      {
          $this->addTab('form_confirguration', array(
                   'label' => $this->__('Configuration'),
                   'title' => $this->__('Configuration'),
                   'content' => $this->getLayout()
		     ->createBlock('googleshopping/adminhtml_googleshopping_edit_tab_configuration')
		     ->toHtml()
         ));
         $this->addTab('form_filter', array(
                   'label' => $this->__('Filters'),
                   'title' => $this->__('Filters'),
                   'content' => $this->getLayout()
		     ->createBlock('googleshopping/adminhtml_googleshopping_edit_tab_filters')
		     ->toHtml()
         ));
          $this->addTab('form_cron', array(
                   'label' => $this->__('Scheduled task'),
                   'title' => $this->__('Scheduled task'),
                   'content' => $this->getLayout()
		     ->createBlock('googleshopping/adminhtml_googleshopping_edit_tab_cron')
		     ->toHtml()
         ));
         return parent::_beforeToHtml();
    }
}
