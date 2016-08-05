<?php

/*
* @copyright Copyright ( c ) 2014 www.magebuzz.com
*/

class Magebuzz_Multipleorderemail_Adminhtml_MultipleorderemailController extends Mage_Adminhtml_Controller_action
{

  protected function _initAction()
  {
    $this->loadLayout()
      ->_setActiveMenu('multipleorderemail/items')
      ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

    return $this;
  }

  public function indexAction()
  {
    $this->_initAction()
      ->renderLayout();
  }

  public function editAction()
  {
    $id = $this->getRequest()->getParam('id');
    $model = Mage::getModel('multipleorderemail/multipleorderemailrule')->load($id);
    $model->getActions()->setJsFormObject('rule_actions_fieldset');
    if ($model->getRuleId() || $id == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(TRUE);
      if (!empty($data)) {
        $model->setData($data);
      }
      Mage::register('multipleorderemail_data', $model);
      $this->loadLayout();
      $this->_setActiveMenu('multipleorderemail/items');

      $this->getLayout()->getBlock('head')->setCanLoadExtJs(TRUE);
      $this->getLayout()->getBlock('head')->setCanLoadTinyMce(TRUE);
      $this->getLayout()->getBlock('head')->setCanLoadRulesJs(TRUE);

      $this->_addContent($this->getLayout()->createBlock('multipleorderemail/adminhtml_multipleorderemail_edit'))
        ->_addLeft($this->getLayout()->createBlock('multipleorderemail/adminhtml_multipleorderemail_edit_tabs'));

      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multipleorderemail')->__('Item does not exist'));
      $this->_redirect('*/*/');
    }
  }

  public function newAction()
  {
    $this->_forward('edit');
  }

  public function gridAction()
  {
    $this->loadLayout();
    $this->getResponse()->setBody($this->getLayout()->createBlock('multipleorderemail/adminhtml_multipleorderemail_grid')->toHtml());
  }

  public function saveAction()
  {
    if ($data = $this->getRequest()->getPost()) {
      $ruleModel = Mage::getModel('multipleorderemail/multipleorderemailrule');
      $id = $this->getRequest()->getParam('id');
      if ($id) {
        $ruleModel->load($id);
      }
      if (isset($data['rule']['actions'])) {
        $data['actions'] = $data['rule']['actions'];
      }
      unset($data['rule']);
      $ruleModel->loadPost($data);
      $groupData = serialize($data['customer_group_ids']);
      $shippingMothods = serialize($data['shipping_method_id']);
      $paymentMothods = serialize($data['payment_method_id']);

      try {
        $ruleModel->setUserGroup($groupData);
        $ruleModel->setShippingMethods($shippingMothods);
        $ruleModel->setPaymentMethods($paymentMothods);
        $ruleModel->setCreatedTime(now());
        $ruleModel->save();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('multipleorderemail')->__('Item was successfully saved'));
        Mage::getSingleton('adminhtml/session')->setFormData(FALSE);
        if ($this->getRequest()->getParam('back')) {
          $this->_redirect('*/*/edit', array('id' => $ruleModel->getRuleId()));
          return;
        }
        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setFormData($data);
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multipleorderemail')->__('Unable to find item to save'));
    $this->_redirect('*/*/');
  }

  public function deleteAction()
  {
    if ($this->getRequest()->getParam('id') > 0) {
      try {
        $model = Mage::getModel('multipleorderemail/multipleorderemailrule');

        $model->setId($this->getRequest()->getParam('id'))
          ->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
        $this->_redirect('*/*/');
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      }
    }
    $this->_redirect('*/*/');
  }

  public function massDeleteAction()
  {
    $multipleorderemailIds = $this->getRequest()->getParam('multipleorderemail');
    if (!is_array($multipleorderemailIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
    } else {
      try {
        foreach ($multipleorderemailIds as $multipleorderemailId) {
          $multipleorderemail = Mage::getModel('multipleorderemail/multipleorderemailrule')->load($multipleorderemailId);
          $multipleorderemail->delete();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('adminhtml')->__(
            'Total of %d record(s) were successfully deleted', count($multipleorderemailIds)
          )
        );
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function massStatusAction()
  {
    $multipleorderemailIds = $this->getRequest()->getParam('multipleorderemail');
    if (!is_array($multipleorderemailIds)) {
      Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
    } else {
      try {
        foreach ($multipleorderemailIds as $multipleorderemailId) {
          $multipleorderemail = Mage::getSingleton('multipleorderemail/multipleorderemailrule')
            ->load($multipleorderemailId)
            ->setStatus($this->getRequest()->getParam('status'))
            ->setIsMassupdate(TRUE)
            ->save();
        }
        $this->_getSession()->addSuccess(
          $this->__('Total of %d record(s) were successfully updated', count($multipleorderemailIds))
        );
      } catch (Exception $e) {
        $this->_getSession()->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function exportCsvAction()
  {
    $fileName = 'multipleorderemail.csv';
    $content = $this->getLayout()->createBlock('multipleorderemail/adminhtml_multipleorderemail_grid')
      ->getCsv();

    $this->_sendUploadResponse($fileName, $content);
  }

  public function exportXmlAction()
  {
    $fileName = 'multipleorderemail.xml';
    $content = $this->getLayout()->createBlock('multipleorderemail/adminhtml_multipleorderemail_grid')
      ->getXml();

    $this->_sendUploadResponse($fileName, $content);
  }

  protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
  {
    $response = $this->getResponse();
    $response->setHeader('HTTP/1.1 200 OK', '');
    $response->setHeader('Pragma', 'public', TRUE);
    $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', TRUE);
    $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
    $response->setHeader('Last-Modified', date('r'));
    $response->setHeader('Accept-Ranges', 'bytes');
    $response->setHeader('Content-Length', strlen($content));
    $response->setHeader('Content-type', $contentType);
    $response->setBody($content);
    $response->sendResponse();
    die;
  }


}
