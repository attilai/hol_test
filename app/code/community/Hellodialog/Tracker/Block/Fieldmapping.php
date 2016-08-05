<?php
	class Hellodialog_Tracker_Block_Fieldmapping extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
	{

		private $hellodialog_options;
		private $magento_options;

		public function __construct()
		{
			// $o = new HelloDialog_Tracker_Model_Observer();
			// $o->test();
			// Hellodialog_Tracker_Model_Cron::sync();

			$this->addColumn('magento', array(
				'label' => 'Customer',
				'style' => 'width:130px',
			));
			$this->addColumn('hellodialog', array(
				'label' => 'into Hellodialog',
				'style' => 'width:130px',
			));
			$this->_addAfter = false;
			$this->_addButtonLabel = 'Add';
			parent::__construct();
		}

		protected function _renderCellTemplate($columnName)
		{
			if ($columnName == 'hellodialog') {
				$html = '<select style="width: 130px;" name="'.$this->getElement()->getName() . '[#{_id}][' . $columnName . ']" data-value="#{' . $columnName . '}" class="dropdown-hellodialog-fieldmappings">';
				foreach ($this->_getHellodialogOptions() as $value => $description) {
					$html .= '<option value="'.$value.'">'.$description.'</option>';
				}
				$html .= '</select>';
				return $html;
			}

			if ($columnName == 'magento') {
				$html = '<select style="width: 130px;" name="'.$this->getElement()->getName() . '[#{_id}][' . $columnName . ']" data-value="#{' . $columnName . '}" class="dropdown-hellodialog-fieldmappings">';
				foreach ($this->_getMagentoOptions() as $value => $description) {
					$html .= '<option value="'.$value.'">'.$description.'</option>';
				}
				$html .= '</select>';
				return $html;
			}

			return parent::_renderCellTemplate($columnName);
		}

		protected function _getHellodialogOptions() {
			if (!is_null($this->hellodialog_options)) {
				return $this->hellodialog_options;
			}

			$key = Mage::getStoreConfig('hellodialog/general/apikey');

			if (!class_exists('HDApi', false)) {
				require_once dirname(__FILE__).'/../Model/HDApi.php';
			}

			$this->hellodialog_options = array();
			HDApi::setToken($key);

			try {
				$api       = new HDApi('fields');
				$fields    = (array)$api->get();


				// error accessing API
				if (isset($fields['result']) && isset($fields['result']->message)) {
					$this->hellodialog_options[] = htmlentities($fields['result']->message, ENT_QUOTES);
					return $this->hellodialog_options;
				}

				// read fields from response
				foreach ($fields as $field) {
					$field = (array)$field;
					// only present 'flat' fields as possible targets
					if (is_array($field['columns']) && count($field['columns']) == 1 && $field['columns'][0] != 'email') {
						$this->hellodialog_options[$field['columns'][0]] = $field['name'];
					}
				}
			} catch (Exception $e) {
				// no API token yet, don't crash
			}


			return $this->hellodialog_options;
		}

		protected function _getMagentoOptions() {
			if (!is_null($this->magento_options)) {
				return $this->magento_options;
			}

			$this->magento_options = array();
			
			$this->magento_options['firstname'] = "Firstname";
			$this->magento_options['lastname']  = "Lastname";
			$this->magento_options['name']      = "Name (Firstname + Lastname)";
			$this->magento_options['company']   = "Company";
			$this->magento_options['address']   = "Address";
			$this->magento_options['zipcode']   = "Postcode";
			$this->magento_options['city']      = "City";
			$this->magento_options['country']   = "Country";
			$this->magento_options['telephone'] = "Telephone";

			return $this->magento_options;
		}
	}