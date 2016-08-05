<?php 
class Hellodialog_Tracker_Block_Cronprogress extends Mage_Adminhtml_Block_System_Config_Form_Field
{

	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$total = Mage::getModel('sales/order')->getCollection()
				->addFieldToFilter('status', 'complete')
				->getSize();

		$total_pages = ceil($total / Hellodialog_Tracker_Model_Cron::orders_per_job());

		return "<a onclick=\"$('cron-progress-details').setStyle({display: 'block'});this.remove();\" style='cursor: pointer;'>View details</a><pre id='cron-progress-details' style='display: none; background: #eee; width: 258px; border: 1px solid #bbb; color: #888; padding: 5px 10px; margin: 10px 0 5px 0; font-size: 11px;'>"
				."order_total ".$total
				."\n"
				."orders_processed ".(Hellodialog_Tracker_Model_Cron::current_page() * Hellodialog_Tracker_Model_Cron::orders_per_job())
				."\n"
				."current_page ".Hellodialog_Tracker_Model_Cron::current_page()." / ".$total_pages
				."\n"
				."cron_started ".date('d-m-Y, H:i:s', Mage::getStoreConfig('hellodialog/synchronize_history/cron_started'))
				."\n"
				."cron_started_unix ".Mage::getStoreConfig('hellodialog/synchronize_history/cron_started')
				."\n"
				."cron_lastrun ".date('d-m-Y, H:i:s', Mage::getStoreConfig('hellodialog/synchronize_history/cron_lastrun'))
				."\n"
				."cron_lastrun_unix ".Mage::getStoreConfig('hellodialog/synchronize_history/cron_lastrun')
				."\n"
				."assume_done_force_disable ".(Hellodialog_Tracker_Model_Cron::current_page() >= $total_pages ? 'true' : 'false')
				."</pre>";
	}
}