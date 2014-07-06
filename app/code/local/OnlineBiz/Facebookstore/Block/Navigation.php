<?php

/**
 * Export Product data to xml data
 *
 * @category   OnlineBiz
 * @package    OnlineBiz_Facebookstore
 * @author     Nguyen Viet Tung (http://onlinebizsoft.com)
 */
class OnlineBiz_Facebookstore_Block_Navigation extends Mage_Core_Block_Template
{
	protected $_storeCategories;

	protected $_categoryInstance = null;

    /**
     * Array of level position counters
     *
     * @var array
     */
    protected $_itemLevelPositions = array();
	
	protected function _construct()
    {
		$this->setTemplate('onlinebizsoft/facebookstore/navigation/top.phtml');
    } 
    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $shortCacheId = array(
            'CATALOG_NAVIGATION',
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            'template' => $this->getTemplate(),
            'name' => $this->getNameInLayout()
        );
        $cacheId = $shortCacheId;

        $shortCacheId = array_values($shortCacheId);
        $shortCacheId = implode('|', $shortCacheId);
        $shortCacheId = md5($shortCacheId);

        $cacheId['category_path'] = $this->getCurrenCategoryKey();
        $cacheId['short_cache_id'] = $shortCacheId;

        return $cacheId;
    }

    public function getCurrenCategoryKey()
    {
        if ($category = Mage::registry('current_category')) {
            return $category->getPath();
        } else {
            return Mage::app()->getStore()->getRootCategoryId();
        }
    }

    /**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getStoreCategories()
    {
		if(Mage::helper('facebookstore')->showSpecCatalog())
			return $this->getOnFacebookCategories();
        return Mage::helper('catalog/category')->getStoreCategories();
    }
	
	public function getOnFacebookCategories()
    {
        return Mage::helper('facebookstore')->getOnFacebookCategories();
    }
    /**
     * Retrieve child categories of current category
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getCurrentChildCategories()
    {
        $layer = Mage::getSingleton('catalog/layer');
        $category   = $layer->getCurrentCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $categories = $category->getChildrenCategories();
        $productCollection = Mage::getResourceModel('catalog/product_collection');
        $layer->prepareProductCollection($productCollection);
        $productCollection->addCountToCategories($categories);
        return $categories;
    }

    /**
     * Checkin activity of category
     *
     * @param   Varien_Object $category
     * @return  bool
     */
    public function isCategoryActive($category)
    {
        if ($this->getCurrentCategory()) {
            return in_array($category->getId(), $this->getCurrentCategory()->getPathIds());
        }
        return false;
    }

    protected function _getCategoryInstance()
    {
        if (is_null($this->_categoryInstance)) {
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance;
    }

    /**
     * Get url for category data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = $this->_getCategoryInstance()
                ->setData($category->getData())
                ->getUrl();
        }

        return $url;
    }

    /**
     * Return item position representation in menu tree
     *
     * @param int $level
     * @return string
     */
    protected function _getItemPosition($level)
    {
        if ($level == 0) {
            $zeroLevelPosition = isset($this->_itemLevelPositions[$level]) ? $this->_itemLevelPositions[$level] + 1 : 1;
            $this->_itemLevelPositions = array();
            $this->_itemLevelPositions[$level] = $zeroLevelPosition;
        } elseif (isset($this->_itemLevelPositions[$level])) {
            $this->_itemLevelPositions[$level]++;
        } else {
            $this->_itemLevelPositions[$level] = 1;
        }

        $position = array();
        for($i = 0; $i <= $level; $i++) {
            if (isset($this->_itemLevelPositions[$i])) {
                $position[] = $this->_itemLevelPositions[$i];
            }
        }
        return implode('-', $position);
    }

    /**
     * Render category to html
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int Nesting level number
     * @param boolean Whether ot not this item is last, affects list item class
     * @param boolean Whether ot not this item is first, affects list item class
     * @param boolean Whether ot not this item is outermost, affects list item class
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @param boolean Whether ot not to add on* attributes to list item
     * @return string
     */
    protected function _renderCategoryMenuItemHtml($category, $level = 1, $isLast = false, $isFirst = false,
        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
    {
        if (!$category->getIsActive()) {
            return '';
        }
        $html = array();

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);

        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        if ($this->isCategoryActive($category)) {
            $classes[] = 'active';
        }
        $linkClass = '';
        if ($isOutermost && $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="'.$outermostItemClass.'"';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        if ($hasActiveChildren) {
            $classes[] = 'parent';
        }

        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
        if ($hasActiveChildren && !$noEventAttributes) {
             $attributes['onmouseover'] = 'toggleMenu(this,1)';
             $attributes['onmouseout'] = 'toggleMenu(this,0)';
        }

        // assemble list item with attributes
        $htmlLi = '<li';
        foreach ($attributes as $attrName => $attrValue) {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
        }
        $htmlLi .= '>';
        $html[] = $htmlLi;

        $html[] = '<a href="'.$this->getUrl('facebookstore/index/category/id/'.$category->getId(), array('_secure'=>Mage::app()->getStore()->isCurrentlySecure()?true : false)).'"'.$linkClass.'>';
        $html[] = '<span>' . $this->escapeHtml($category->getName()) . '</span>';
        $html[] = '</a>';

        // render children
        $htmlChildren = '';
        $j = 0;
        foreach ($activeChildren as $child) {
            $htmlChildren .= $this->_renderCategoryMenuItemHtml(
                $child,
                ($level + 1),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            $j++;
        }
        if (!empty($htmlChildren)) {
            if ($childrenWrapClass) {
                $html[] = '<div class="' . $childrenWrapClass . '">';
            }
            $html[] = '<ul class="level' . $level . '">';
            $html[] = $htmlChildren;
            $html[] = '</ul>';
            if ($childrenWrapClass) {
                $html[] = '</div>';
            }
        }

        $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;
    }

    /**
     * Render category to html
     *
     * @deprecated deprecated after 1.4
     * @param Mage_Catalog_Model_Category $category
     * @param int Nesting level number
     * @param boolean Whether ot not this item is last, affects list item class
     * @return string
     */
    public function drawItem($category, $level = 0, $last = false)
    {
        return $this->_renderCategoryMenuItemHtml($category, $level, $last);
    }
	protected function _isCurrentCategory($category)
	{
		return ($cat = $this->getCurrentCategory()) && $cat->getId() == $category->getId();
	}
	
	protected function _sortCategoryArrayByName($a, $b)
	{
		return strcoll($a->getName(), $b->getName());
	}

    protected function _getClassNameFromCategoryName($category)
    {
    	$name = $category->getName();
    	$name = preg_replace('/-{2,}/', '-', preg_replace('/[^a-z-]/', '-', strtolower($name)));
		while ($name && $name{0} == '-') $name = substr($name, 1);
		while ($name && substr($name, -1) == '-') $name = substr($name, 0, -1);
    	return $name;
    }

    /**
     * Enter description here...
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if (Mage::getSingleton('catalog/layer')) {
            return Mage::getSingleton('catalog/layer')->getCurrentCategory();
        }
        return false;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getCurrentCategoryPath()
    {
        if ($this->getCurrentCategory()) {
            return explode(',', $this->getCurrentCategory()->getPathInStore());
        }
        return array();
    }

     /**
     * Add project specific formatting
     *
     * @param Mage_Model_Catalog_Category $category
     * @param integer $level
     * @param array $levelClass
     * @return string
     */
    public function drawOpenCategoryItem($category, $level=0, array $levelClass=null)
    {
        $html = array();
		
        if (! $category->getIsActive()) return '';

        if (! isset($levelClass)) $levelClass = array();
		$combineClasses = array();

        $combineClasses[] = 'level' . $level;
        if ($this->_isCurrentCategory($category))
        {
        	$combineClasses[] = 'active';
        }
        else
        {
			$combineClasses[] = $this->isCategoryActive($category) ? 'parent' : 'inactive';
        }
		$levelClass[] = implode('-', $combineClasses);

		$levelClass = array_merge($levelClass, $combineClasses);
		
		$levelClass[] =  $this->_getClassNameFromCategoryName($category);
		
		$productCount = '';
		

        // indent HTML!
        $html[1] = str_pad ( "", (($level * 2 ) + 4), " " ).'<span class="vertnav-cat"><a href="'.$this->getUrl('facebookstore/index/category/id/'.$category->getId(), array('_secure'=>Mage::app()->getStore()->isCurrentlySecure()?true : false)).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'.$productCount."</span>\n";

		if (in_array($category->getId(), $this->getCurrentCategoryPath())
		)
        {
			$children = $category->getChildren();
			
			$children = $this->toLinearArray($children);

			//usort($children, array($this, '_sortCategoryArrayByName'));

			$hasChildren = $children && ($childrenCount = count($children));
			if ($hasChildren)
			{
				$children = $this->toLinearArray($children);
				$htmlChildren = '';

				foreach ($children as $i => $child)
				{
					$class = array();
					if ($childrenCount == 1)
					{
						$class[] = 'only';
					}
					else
					{
						if (! $i) $class[] = 'first';
						if ($i == $childrenCount-1) $class[] = 'last';
					}
					if (isset($children[$i+1]) && $this->isCategoryActive($children[$i+1])) $class[] = 'prev';
					if (isset($children[$i-1]) && $this->isCategoryActive($children[$i-1])) $class[] = 'next';
					$htmlChildren.= $this->drawOpenCategoryItem($child, $level+1, $class);
				}

				if (!empty($htmlChildren))
				{
					$levelClass[] = 'open';

					// indent HTML!
					$html[2] = str_pad ( "", ($level * 2 ) + 2, " " ).'<ul>'."\n"
							.$htmlChildren."\n".
							str_pad ( "", ($level * 2 ) + 2, " " ).'</ul>';
				}
			}
		}
		// indent HTML!
        $html[0] = str_pad ( "", ($level * 2 ) + 2, " " ).sprintf('<li class="%s">', implode(" ", $levelClass))."\n";

        // indent HTML!
        $html[3] = "\n".str_pad ( "", ($level * 2 ) + 2, " " ).'</li>'."\n";
		
		ksort($html);
        return implode('', $html);
    }


    /**
     * Render categories menu in HTML
     *
     * @param int Level number for list item class to start from
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @return string
     */
    public function renderCategoriesMenuHtml($level = 1, $outermostItemClass = '', $childrenWrapClass = '')
    {
        $activeCategories = array();
        foreach ($this->getStoreCategories() as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        $activeCategoriesCount = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $html = '';
        $j = 0;
        foreach ($activeCategories as $category) {
            $html .= $this->_renderCategoryMenuItemHtml(
                $category,
                $level,
                ($j == $activeCategoriesCount - 1),
                ($j == 0),
                true,
                $outermostItemClass,
                $childrenWrapClass,
                true
            );
            $j++;
        }

        return $html;
    }
	/**
     * I need an array with the index being continunig numbers, so
     * it's possible to check for the previous/next category
     *
     * @param mixed $collection
     * @return array
     */
    public function toLinearArray($collection)
    {
    	$array = array();
    	foreach ($collection as $item) $array[] = $item;
    	return $array;
    }
}
