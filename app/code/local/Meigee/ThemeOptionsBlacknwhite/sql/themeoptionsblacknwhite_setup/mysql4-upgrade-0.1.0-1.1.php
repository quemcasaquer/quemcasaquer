<?php
$installer = $this;
$installer->startSetup();
$installer->addAttribute('catalog_product', 'blacknwhite_productimg', array(
    'group'         => 'Meigee - Blacknwhite options',
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Product gallery',
    'note' => 'Cloud zoom will show detailed product image, slider will rotate the pictures of your items w/o zooming',
	'source'            => 'ThemeOptionsBlacknwhite/attribute_source_productimg',
    'default' => 0,
    'default_value' => 0,
    'sort_order' => 11,
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$installer->endSetup();