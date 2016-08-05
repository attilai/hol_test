<?php
/**
 * Willouhby Stewart
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    WS
 * @package     WS_Recaptcha
 * @copyright   Copyright (c) 2010 Willoughby Stewart (http://www.wsa.net.uk)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog Product Compare Helper
 *
 * @category   WS
 * @package    WS_RemoveCompare
 * @author     Willoughby Stewart <digitalmedia@wsa.net.uk>
 */
class WS_RemoveCompare_Helper_Product_Compare extends Mage_Catalog_Helper_Product_Compare
{
    /**
     * Retrieve url for adding product to coppare list. Here, we are shortcutting this functionality to disable the functionality
     *
     * @param   Mage_Catalog_Model_Product $product
     * @return  string
     */
    public function getAddUrl($product)
    {
        return false;
    }

    /**
     * Retrieve add to cart url
     *
     * @param Mage_Catalog_Model_Product $product. Here, we are shortcutting this functionality to disable the functionality
     * @return string
     */
    public function getAddToCartUrl($product)
    {
	    return false;
    }

}
