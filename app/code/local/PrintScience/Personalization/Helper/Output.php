<?php

/**

 * Print Science personalization quote helper

 *

 */

class PrintScience_Personalization_Helper_Output extends Mage_Core_Helper_Abstract 

{

    /**

     * Check if personalization is enabled for this product

     *

     * @param Mage_Catalog_Model_Product $product

     * @return boolean

     */

    public function isPersonalizationEnabled($product)

    {
		$pId = $product->getId();
		
		$product = Mage::getModel('catalog/product'); 
		$product->load($pId); 
		
        $productTypeId = $product->getTypeId();

        if ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {

            return $product->getPersonalizationEnabled();

        } elseif ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {

            $subproducts = $product->getTypeInstance()->getUsedProducts();

            foreach ($subproducts as $product) {

                if (!$product->getPersonalizationEnabled()) {

                    return false;

                }

            }

            return true;

        } elseif ($productTypeId == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
			
			return $product->getPersonalizationEnabled();
			
		}

    }

	

	public function getFrontendParams() {

		$addedtoCart = (int) $_REQUEST['added'];

		return $addedtoCart;

	}

}

