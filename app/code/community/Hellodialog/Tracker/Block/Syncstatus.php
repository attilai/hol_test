<?php 
class Hellodialog_Tracker_Block_Syncstatus extends Mage_Adminhtml_Block_System_Config_Form_Field
{

	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		if (!class_exists('HDApi', false)) {
			require_once dirname(__FILE__).'/../Model/HDApi.php';
		}

		$key = Mage::getStoreConfig('hellodialog/general/apikey');
		HDApi::setToken($key);

		try {
			$api = new HDApi('orders');
			$result = $api->addParam('count', 1)->get();

			if (is_object($result) && isset($result->result) && isset($result->result->data)) {
				$processed = (int) $result->result->data->count;
			} else {
				$processed = 0;
			}

			$total = Mage::getModel('sales/order')->getCollection()
					->addFieldToFilter('status', 'complete')
					->getSize();

			$perc = round(100 * $processed / $total);

			$progress_color = (Mage::getStoreConfig('hellodialog/synchronize_history/enabled') || $perc == 100) ? array('#6ada64', '#296426') : array('#c4c4c4', '#666666');

			return "<div style='background: #e4e4e4; border: 1px solid #aaa; margin-right: 10px; width: 180px; height: 15px; border-radius: 3px; display: inline-block; overflow: hidden; box-shadow: inset 0 0 7px rgba(0,0,0,0.2);'>".
					"<div style='background: ".$progress_color[0]."; height: 100%; border-right: 1px solid #aaa; text-align: center; color: ".$progress_color[1]."; line-height: 15px; font-size: 10px; width: ".$perc."%;'>".$perc."%</div></div>".number_format($processed).' / '.number_format($total);
		} catch (Exception $e) {
			return "<span style='font-style: italic;'>Please fill out your API-Key first.</span>";
		}
	}
}