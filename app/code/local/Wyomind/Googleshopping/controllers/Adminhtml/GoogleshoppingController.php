<?php

class Wyomind_Googleshopping_Adminhtml_GoogleshoppingController extends Mage_Adminhtml_Controller_Action
{
	protected function check_activation(){
		
		
		
		$activation_key=Mage::getStoreConfig("googleshopping/license/activation_key");
      	$get_online_license=Mage::getStoreConfig("googleshopping/license/get_online_license");
		$activation_code=Mage::getStoreConfig("googleshopping/license/activation_code");
		$base_url=Mage::getStoreConfig("web/secure/base_url");
		
		$registered_version=Mage::getStoreConfig("googleshopping/license/version");
		$current_version = Mage::getConfig()->getNode("modules/Wyomind_Googleshopping")->version;

		
		$license_request="&rv=".$registered_version."&cv=".$current_version."&activation_key=".$activation_key."&domain=".$base_url."&store_code=".Mage::app()->getStore()->getCode();
		if($registered_version!=$current_version && ($activation_code || !empty($activation_code))){
			Mage::getSingleton("adminhtml/session")->addError(Mage::helper("googleshopping")->__("<u>Extension upgrade from v".$registered_version." to v".$current_version."</u>.<br> Your license must be updated.<br>Please, reload this page."));
			Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
		}
		elseif(!$activation_key){
			
			Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
			Mage::getSingleton("adminhtml/session")->addError(Mage::helper("googleshopping")->__("Your activation key is not yet registered.<br>
										Go to system/configuration/Wyomind/Simple Google Shopping."));
			
			Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
		}
		elseif($activation_key && (!$activation_code || empty($activation_code)) && !$get_online_license){
       		Mage::getSingleton("adminhtml/session")->addError(Mage::helper("googleshopping")->__("Your license is not yet activated.<br>
										<a target='_blank' href='http://www.wyomind.com/license_activation/?method=post".$license_request."'>Go to http://www.wyomind.com/license_activation/</a>"));
			
			Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
		}
		elseif($activation_key && (!$activation_code ||empty($activation_code)) && $get_online_license){
		
			try {
				$activation_code = file_get_contents("http://www.wyomind.com/license_activation/index.php?method=get&rv=".$license_request);
				
				$result=json_decode($activation_code);
				switch($result->status){
					case "success" : 
						 Mage::getConfig()->saveConfig("googleshopping/license/version", $result->version, "default", "0");
						 Mage::getConfig()->saveConfig("googleshopping/license/activation_code", $result->activation, "default", "0");
						 Mage::getConfig()->cleanCache();						 
						 Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("googleshopping")->__($result->message));
						 
					break;
					case "error" : 
						 Mage::getSingleton("adminhtml/session")->addError(Mage::helper("googleshopping")->__($result->message));
						 Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
						Mage::getConfig()->cleanCache();
					break;
					default :
						 Mage::getSingleton("adminhtml/session")->addError(Mage::helper("googleshopping")->__("An error occurs while connecting wyomind license server (500).<br>
							<a target='_blank' href='http://www.wyomind.com/license_activation/?method=post".$license_request."'>Go to http://www.wyomind.com/license_activation/</a>"));
						 Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
						Mage::getConfig()->cleanCache();
					break;
				}
			} 
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError(Mage::helper("googleshopping")->__("An error occurs while connecting wyomind license server (404).<br>
										<a target='_blank' href='http://www.wyomind.com/license_activation/?method=post".$license_request."'>Go to http://www.wyomind.com/license_activation/</a>"));
				 Mage::getConfig()->saveConfig("googleshopping/license/activation_code", "", "default", "0");
					 Mage::getConfig()->cleanCache();
				return;
			}
			
		}
	}
	
	protected function _initAction() {
		
		$this->loadLayout()
		   ->_setActiveMenu('catalog/googleshopping')
           ->_addBreadcrumb($this->__('Google Shopping'),('Google Shopping'));
		
		return $this;
	}   
 
	public function indexAction() {
		
		$this->check_activation();
		
		
		$this->_initAction()
			->renderLayout();
			
	}
		
	public function editAction() {
		$this->check_activation();
	
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('googleshopping/googleshopping')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('googleshopping_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('catalog/googleshopping') ->_addBreadcrumb($this->__('Google Shopping'),('Google Shopping'));
            $this->_addBreadcrumb($this->__('Google Shopping'),('Google Shopping'));
           
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			//$this->_addContent($this->getLayout()->createBlock('googleshopping/adminhtml_googleshopping_edit'));
			$this->_addContent($this->getLayout()
          	->createBlock('googleshopping/adminhtml_googleshopping_edit'))
            ->_addLeft($this->getLayout()
            ->createBlock('googleshopping/adminhtml_googleshopping_edit_tabs'));

			$this->renderLayout();

		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('googleshopping')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction()
    {	
    	$this->check_activation();
    	
	    // check if data sent
        if ($data = $this->getRequest()->getPost()) {
			
            // init model and set data
            $model = Mage::getModel('googleshopping/googleshopping');
			
            if ($this->getRequest()->getParam('googleshopping_id')) {
                $model ->load($this->getRequest()->getParam('googleshopping_id'));

                if ($model->getGoogleshoppingFilename() && file_exists($model->getPreparedFilename())){
                    unlink($model->getPreparedFilename());
                }
            }
	

            $model->setData($data);

            // try to save it
            try {
            	
            	// save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('googleshopping')->__('The data feed has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

              
                
            	if ($this->getRequest()->getParam('continue')) {
                    $this->getRequest()->setParam('id', $model->getId());
                    $this->_forward('edit');
                    return;
                }
            	
                
                // go to grid or forward to generate action
                if ($this->getRequest()->getParam('generate')) {
                    $this->getRequest()->setParam('googleshopping_id', $model->getId());
                    $this->_forward('generate');
                    return;
                }
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('googleshopping_id' => $this->getRequest()->getParam('googleshopping_id')));
                return;
            }
        }
        $this->_redirect('*/*/');

    }

    /**
     * Delete action
     */
    public function deleteAction()
    {	
		$this->check_activation();
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                // init model and delete
                $model = Mage::getModel('googleshopping/googleshopping');
                $model->setId($id);
                // init and load googleshopping model

                /* @var $googleshopping Mage_Googleshopping_Model_Googleshopping */
                $model->load($id);
                // delete file
                if ($model->getGoogleshoppingFilename() && file_exists($model->getPreparedFilename())){
                    unlink($model->getPreparedFilename());
                }
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('googleshopping')->__('The data feed has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('googleshopping_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('googleshopping')->__('Unable to find a data feed to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

    public function sampleAction()
    {	
		
    		
		 // init and load googleshopping model
        $id = $this->getRequest()->getParam('googleshopping_id');
        $limit = $this->getRequest()->getParam('limit');
		
        $googleshopping = Mage::getModel('googleshopping/googleshopping');
        $googleshopping->setId($id);
        $googleshopping->_limit=$limit;
		
		$googleshopping->_display=true;
      
        // if googleshopping record exists
        if ($googleshopping->load($id)) {
        	
            try {
				$content=$googleshopping->generateXml();
				if($googleshopping->_demo){
					 $this->_getSession()->addError(Mage::helper('googleshopping')->__("Invalid license."));
					 Mage::getConfig()->saveConfig('googleshopping/license/activation_code', '', 'default', '0');
					 Mage::getConfig()->cleanCache();
					 $this->_redirect('*/*/');
				}
				else print($content);
			}
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
            catch (Exception $e) {
			
                $this->_getSession()->addException($e, Mage::helper('googleshopping')->__('Unable to generate the data feed.'));
            	$this->_redirect('*/*/');
            }
        }
        else {
			$this->_getSession()->addError(Mage::helper('googleshopping')->__('Unable to find a data feed to generate.'));
        }
	
      
     	
    }
  
	  public function generateAction()
    {	
		
        // init and load googleshopping model
        $id = $this->getRequest()->getParam('googleshopping_id');
		
        $googleshopping = Mage::getModel('googleshopping/googleshopping');
        $googleshopping->setId($id);
		$limit = $this->getRequest()->getParam('limit');
		$googleshopping->_limit=$limit;
	
		
        // if googleshopping record exists
        if ($googleshopping->load($id)) {
        	
        	
            try {
                $googleshopping->generateXml();
				if($googleshopping->_demo){
					$this->_getSession()->addError(Mage::helper('googleshopping')->__("Invalid license."));
					 Mage::getConfig()->saveConfig('googleshopping/license/activation_code', '', 'default', '0');
					 Mage::getConfig()->cleanCache();
				}	
	            else $this->_getSession()->addSuccess(Mage::helper('googleshopping')->__('The data feed "%s" has been generated.', $googleshopping->getGoogleshoppingFilename()));
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
			
                $this->_getSession()->addException($e, Mage::helper('googleshopping')->__('Unable to generate the data feed.'));
            }
        }
        else {
            $this->_getSession()->addError(Mage::helper('googleshopping')->__('Unable to find a data feed to generate.'));
        }

        // go to grid
        $this->_redirect('*/*/');
    }
	
   
   
}
	