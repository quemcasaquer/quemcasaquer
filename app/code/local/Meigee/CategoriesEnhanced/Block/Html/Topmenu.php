<?php
/**
 * Magento
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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Page
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Top menu block
 *
 * @category    Mage
 * @package     Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Meigee_CategoriesEnhanced_Block_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
        $html = '';
        if (Mage::getStoreConfig('meigee_categoriesenhanced/options/status')) {
            $childrenWrapClass = 'menu-wrapper';

            $children = $menuTree->getChildren();
            $parentLevel = $menuTree->getLevel();
            $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

            $counter = 1;
            $childrenCount = $children->count();

            $parentPositionClass = $menuTree->getPositionClass();
            $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

            foreach ($children as $child) {

                $catId = explode("category-node-", $child->getId());
                $categoryComplete = Mage::getModel('catalog/category')->load($catId[1]);

                if ($categoryComplete->getMeigeeCatCustomlink()) {
                    if ($categoryComplete->getMeigeeCatCustomlink() == '/') {
                        $itemUrl = Mage::getBaseUrl();
                    } 
                    elseif ($categoryComplete->getMeigeeCatCustomlink() == '#') {
                        $itemUrl = '#';
                    } 
                    else $itemUrl = $categoryComplete->getMeigeeCatCustomlink();
                }
                else $itemUrl = $child->getUrl();

                // Get ratio value
                $ratio = explode("/", $categoryComplete->getMeigeeCatRatio());

                $child->setLevel($childLevel);
                $child->setIsFirst($counter == 1);
                $child->setIsLast($counter == $childrenCount);
                $child->setPositionClass($itemPositionClassPrefix . $counter);

                $outermostClassCode = '';
                $outermostClass = $menuTree->getOutermostClass();

                if ($childLevel == 0 && $outermostClass) {
                    $outermostClassCode = ' class="' . $outermostClass . '" ';
                    $child->setClass($outermostClass);
                }

                if ($childLevel == 1) {
                    $html .= '<li class="level1 grid_2 alpha">';
                }
                else {
                    $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
                }

                $subTitle = '';
                if ($childLevel == 1) {
                    $subTitle = ' class="subtitle"';
                }
                if ($categoryComplete->getMeigeeCatBlockTop() && $childLevel > 0) {
                    $html .= '<div class="top-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockTop()) . '</div><div class="clear"></div>';
                }
                $catLabel = '';
                if ($categoryComplete->getMeigeeCatCustomlabel()) {
                    $catLabel = '<em class="category-label ' . $categoryComplete->getMeigeeCatCustomlabel() . '">' . $categoryComplete->getMeigeeCatLabeltext() . '</em>';
                }
                if (!$categoryComplete->getMeigeeCatSubcontent()) {
                    $html .= '<a href="' . $itemUrl . '" ' . $outermostClassCode . '><span' . $subTitle . '>'
                    . $this->escapeHtml($child->getName()) . '</span>' . $catLabel . '</a>';
                    
                }
                elseif ($categoryComplete->getMeigeeCatSubcontent() && $childLevel == 0) {
                    $html .= '<a href="' . $itemUrl . '" ' . $outermostClassCode . '><span' . $subTitle . '>'
                    . $this->escapeHtml($child->getName()) . '</span>' . $catLabel . '</a>';
                }

                if ($child->hasChildren()) {
                    if (!empty($childrenWrapClass) && $childLevel == 0) {
                        $html .= '<div class="' . $childrenWrapClass . '">';
                    }
                    if ($categoryComplete->getMeigeeCatSubcontent()) {
                        $html .= '<div class="sub-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatSubcontent()) . '</div>';
                    }
                    else {
                        if ($categoryComplete->getMeigeeCatBlockTop() && $childLevel == 0) {
                            $html .= '<div class="top-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockTop()) . '</div><div class="clear"></div>';
                        }
                        if ($categoryComplete->getMeigeeCatBlockRight()) {
                            $html .= '<div class="grid_'. $ratio[0] .' alpha omega">';
                        }
                        $html .= '<ul class="level' . $childLevel . '">';
                        $html .= $this->_getHtml($child, $childrenWrapClass);
                        $html .= '</ul>';

                        if (!empty($childrenWrapClass) && $childLevel == 0) {
                            if ($categoryComplete->getMeigeeCatBlockRight()) {
                                $html .= '</div>';
								$html .= '<div class="grid_'. $ratio[1] .' alpha omega right-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockRight()) . '</div>';
                            }
                            $html .= '<div class="clear"></div>';
                        }
                        if ($categoryComplete->getMeigeeCatBlockBottom()) {
                            $html .= '<div class="bottom-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockBottom()) . '</div><div class="clear"></div>';
                        }
                    }
                    if (!empty($childrenWrapClass) && $childLevel == 0) {
                        $html .= '</div>';
                    }
                }
                $html .= '</li>';

                $counter++;
            }
        } 
        else {
            $children = $menuTree->getChildren();
            $parentLevel = $menuTree->getLevel();
            $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

            $counter = 1;
            $childrenCount = $children->count();

            $parentPositionClass = $menuTree->getPositionClass();
            $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

            foreach ($children as $child) {

                $catId = explode("category-node-", $child->getId());
                $categoryComplete = Mage::getModel('catalog/category')->load($catId[1]);

                if ($categoryComplete->getMeigeeCatCustomlink()) {
                    if ($categoryComplete->getMeigeeCatCustomlink() == '/') {
                        $itemUrl = Mage::getBaseUrl();
                    }
                    if ($categoryComplete->getMeigeeCatCustomlink() == '#') {
                        $itemUrl = '#';
                    } 
                    else $itemUrl = $categoryComplete->getMeigeeCatCustomlink();
                }
                else $itemUrl = $child->getUrl();

                $child->setLevel($childLevel);
                $child->setIsFirst($counter == 1);
                $child->setIsLast($counter == $childrenCount);
                $child->setPositionClass($itemPositionClassPrefix . $counter);

                $outermostClassCode = '';
                $outermostClass = $menuTree->getOutermostClass();

                if ($childLevel == 0 && $outermostClass) {
                    $outermostClassCode = ' class="' . $outermostClass . '" ';
                    $child->setClass($outermostClass);
                }

                $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
                $html .= '<a href="' . $itemUrl . '" ' . $outermostClassCode . '><span>'
                    . $this->escapeHtml($child->getName()) . '</span></a>';

                if ($child->hasChildren()) {
                    if (!empty($childrenWrapClass)) {
                        $html .= '<div class="' . $childrenWrapClass . '">';
                    }
                    $html .= '<ul class="level' . $childLevel . '">';
                    $html .= $this->_getHtml($child, $childrenWrapClass);
                    $html .= '</ul>';

                    if (!empty($childrenWrapClass)) {
                        $html .= '</div>';
                    }
                }
                $html .= '</li>';

                $counter++;
            }
        }

        return $html;
    }
}