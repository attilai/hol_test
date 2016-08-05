<?php

class Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

   public function __construct()
   {
       parent::__construct();
       $this->setId('googleShoppingGrid');
       $this->setDefaultSort('googleshopping_id');
       $this->setDefaultDir('DESC');
       $this->setSaveParametersInSession(true);
     
   }

   protected function _prepareCollection()
   {
      $collection = Mage::getModel('googleshopping/googleshopping')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
    }

   protected function _prepareColumns()
   {
       $this->addColumn('googleshopping_id',
             array(
                     'header'    => $this->__('ID'),
                    'align' =>'right',
                    'width' => '50px',
                    'index' => 'googleshopping_id',
               ));

       $this->addColumn('googleshopping_filename',
               array(
                    'header'    => $this->__('Googleshopping filename'),
                    'align' =>'left',
                    'index' => 'googleshopping_filename',
              ));

       $this->addColumn('googleshopping_path', array(
                   'header'    => $this->__('File path'),
                    'align' =>'left',
                    'index' => 'googleshopping_path',
             ));

        $this->addColumn('link', array(
                    'header'    => $this->__('File link'),
                     'align' =>'left',
                     'index' => 'link',
					 'renderer'  => 'Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Renderer_Link',
          ));
		   $this->addColumn('googleshopping_time', array(
                    'header'    => $this->__('Last update'),
                     'align' =>'left',
                     'index' => 'googleshopping_time',
					 'type'      => 'datetime',
        ));
		if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => $this->__('Store View'),
                'index'     => 'store_id',
                'type'      => 'store',
            ));
        }


		   $this->addColumn('action', array(
                       'header'    => $this->__('Action'),
                     'align' =>'left',
                     'index' => 'action',
					    'filter'   => false,
					'sortable' => false,
					 'renderer'  => 'Wyomind_Googleshopping_Block_Adminhtml_Googleshopping_Renderer_Action',
          ));

         return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
         return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}