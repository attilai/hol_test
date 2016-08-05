<?php 
class Hellodialog_Tracker_Block_Validator extends Mage_Adminhtml_Block_System_Config_Form_Field
{

	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->setElement($element);

		$key = Mage::getStoreConfig('hellodialog/general/apikey');
		require_once dirname(__FILE__).'/../Model/APIKeyValidator.php';
		$status = APIKeyValidator::validate($key);

		$output = "<div style='background: #eee; width: 258px; border: 1px solid #bbb; color: #888; padding: 5px 10px; margin: 10px 0 20px 0; font-size: 11px;' id='hellodialog_apikey_validation_output'>".$status."</div>";

		$html = $this->getLayout()->createBlock('adminhtml/widget_button')
					->setType('button')
					->setClass('')
					->setLabel('Validate API Key')
					->setOnClick($this->onClick())
					->toHtml();

		return $output.$html."<br/><br/><br/>";
	}

	protected function onClick() {
		return "
			(function() {

				var button  = $$('#row_hellodialog_general_validate button')[0];
				var api_key = $('hellodialog_general_apikey').value;
				var url     = '".Mage::helper('adminhtml')->getUrl('adminhtml/adminhtml_form/check')."';

				button.toggleClassName('loading');
				$('hellodialog_apikey_validation_output').innerHTML = 'Validating...';
				new Ajax.Request(url, {
					'method': 'post',
					'parameters': {
						'api_key': api_key
					},
					'onSuccess': function(response) {
						$('hellodialog_apikey_validation_output').innerHTML = response.responseText;
					},
					'onFailure': function(response) {
						$('hellodialog_apikey_validation_output').innerHTML = 'Could not process validation request due to an error with the Hellodialog Magento Plugin [' + response.status + '].';
					},
					'onComplete': function() {
						button.toggleClassName('loading');
					}
				});
			})();
		";
	}
}
?>