<?php

/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
	protected $_productCollection;
	
	protected $_defaultToolbarBlock = 'facebookstore/product_list_toolbar';
	
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	
	protected function _getProductCollection()
    {	
		$categoryId = (int) $this->getRequest()->getParam('id', false);
		$layer = $this->getLayer();
		if ($categoryId) {
			$category = Mage::getModel('catalog/category')->load($categoryId);
			if ($category->getId()) {
				$layer->setCurrentCategory($category);
			}
		}
		
        if (is_null($this->_productCollection)) {
			$order = $this->getAction()->getRequest()->getParam('order');
			$direction = $this->getAction()->getRequest()->getParam('dir');
            $storeId = Mage::app()->getStore()->getId();
			$collection = null;
			if ($categoryId) {
				$collection = $layer->getProductCollection();
			}else{
				$collection = Mage::getSingleton('catalogsearch/advanced')->getProductCollection();
				$collection->addAttributeToSelect('*');
				$collection->addUrlRewrite();
				$collection->setStoreId($storeId);
				if(Mage::helper('facebookstore')->showSpecCatalog()){
					$collection->joinField('category_id',
					'catalog/category_product',
					'category_id',
					'product_id=entity_id',
					null,
					'left');
					$collection->distinct(true);
					$collection->addAttributeToFilter('category_id', array('in' => array('finset'=>implode(',', $this->getOnFacebookCategories())))); 
				}
			}
			if(!$categoryId && Mage::getStoreConfig('facebookstore/homepage/display_type')=='featured')
			{
				$productIds = explode(',', Mage::getStoreConfig('facebookstore/homepage/product_ids'));
				$collection->addAttributeToFilter('entity_id', array('in' => $productIds));
				
			}
			if(!$order && Mage::getStoreConfig('facebookstore/homepage/display_type')=='random' &&!$categoryId)
				$collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
			if(Mage::getStoreConfig('facebookstore/homepage/display_type')=='empty' && Mage::helper('facebookstore')->isHomepage())
				$collection->addAttributeToFilter('entity_id', array('in' => array(0)));
			Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
			Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
			$this->_productCollection = $collection;
			$this->_productCollection->getSize();
        }

        return $this->_productCollection;
    }
    
    /**
     * Get catalog layer model
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('catalog/layer');
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     */
    protected function _beforeToHtml()
    {
        /*$toolbar = $this->getLayout()->createBlock('catalog/product_list_toolbar', microtime());
        if ($toolbarTemplate = $this->getToolbarTemplate()) {
            $toolbar->setTemplate($toolbarTemplate);
        }*/
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getProductCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        Mage::dispatchEvent('catalog_block_product_list_collection', array(
            'collection' => $this->_getProductCollection()
        ));

        $this->_getProductCollection()->load();
        Mage::getModel('review/review')->appendSummary($this->_getProductCollection());
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    /**
     * Retrieve additional blocks html
     *
     * @return string
     */
    public function getAdditionalHtml()
    {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    public function setCollection($collection)
    {
        $this->_productCollection = $collection;
        return $this;
    }

    public function addAttribute($code)
    {
        $this->_getProductCollection()->addAttributeToSelect($code);
        return $this;
    }

    public function getPriceBlockTemplate()
    {
        return $this->_getData('price_block_template');
    }

    /**
     * Retrieve Catalog Config object
     *
     * @return Mage_Catalog_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('catalog/config');
    }

    /**
     * Prepare Sort By fields from Category Data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Mage_Catalog_Block_Product_List
     */
    public function prepareSortableFieldsByCategory($category) {
        if (!$this->getAvailableOrders()) {
            $this->setAvailableOrders($category->getAvailableSortByOptions());
        }
        $availableOrders = $this->getAvailableOrders();
        if (!$this->getSortBy()) {
            if ($categorySortBy = $category->getDefaultSortBy()) {
                if (!$availableOrders) {
                    $availableOrders = $this->_getConfig()->getAttributeUsedForSortByArray();
                }
                if (isset($availableOrders[$categorySortBy])) {
                    $this->setSortBy($categorySortBy);
                }
            }
        }

        return $this;
    }

	public function getOnFacebookCategories()
	{
		$categories = array();
		foreach (Mage::helper('facebookstore')->getOnFacebookCategories() as $child) {
			foreach($child->getChildren() as $item) {				
				$categories[$item->getId()] = $item->getId();
			}
		}
		return $categories;
	}
	protected function _afterToHtml($html){
		if(!Mage::helper('facebookstore')->isActivated()) 
			return '';
		return $html;
	}
}