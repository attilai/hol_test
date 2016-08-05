<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Full Page Cache
 * @version   1.0.5.2
 * @build     509
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_Fpc_Helper_Smartwaveproto extends Mage_Core_Helper_Abstract
{
    public function addCheckoutMessage(&$content, $message) {
        $content = preg_replace('/<div id="messages_product_view">/ims', '<div id="messages_product_view">' . $message, $content, 1);

        return $this;
    }

    public function getCheckoutMessage()
    { 
        $messageText = '';
        if (Mage::getSingleton('checkout/session')->getMessages()->count()
            && Mage::getSingleton('core/design_package')->getPackageName() == 'smartwave'
            && Mage::getSingleton('core/design_package')->getTheme('frontend') == 'porto'
            && ($message = Mage::getSingleton('checkout/session')->getMessages())
            && ($lastMessage = $message->getLastAddedMessage())) {
                $messageText = $this->prepareMessage($lastMessage);
                $message->clear(); //clear message after first show
        }

        return $messageText;
    }

    protected function prepareMessage($message)
    {
        if (!is_object($message)) {
            return false;
        }

        if ($message->getType() == 'success') {
            $messageText = '<ul class="messages">
                                <li class="success-msg">
                                    <ul>
                                        <li>' . $message->getText() . '</li>
                                    </ul>
                                </li>
                            </ul>';
        } elseif ($message->getType() == 'note') {
            $messageText = '<ul class="messages">
                                <li class="note-msg">
                                    <ul>
                                        <li>' . $message->getText() . '</li>
                                    </ul>
                                </li>
                            </ul>';
        } elseif ($message->getType() == 'note') {
            $messageText = '<ul class="messages">
                                <li class="error-msg">
                                    <ul>
                                        <li>' . $message->getText() . '</li>
                                    </ul>
                                </li>
                            </ul>';
        }

        return $messageText;
    }
}
