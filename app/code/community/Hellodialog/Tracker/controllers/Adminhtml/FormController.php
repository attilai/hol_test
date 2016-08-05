<?php

	class Hellodialog_Tracker_Adminhtml_FormController extends Mage_Adminhtml_Controller_Action
	{
		public function checkAction()
		{
			require_once dirname(__FILE__).'/../../Model/APIKeyValidator.php';
			Mage::app()->getResponse()->setBody(APIKeyValidator::validate($_POST['api_key']));
		}
	}