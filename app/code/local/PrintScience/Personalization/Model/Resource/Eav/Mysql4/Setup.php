<?php
/**
 * Print Science Product Personalization setup
 *
 */
class PrintScience_Personalization_Model_Resource_Eav_Mysql4_Setup extends Mage_Eav_Model_Entity_Setup 
{
    /**
     * Prepare personalization attribute values to save
     *
     * @param array $attr
     * @return array
     */
    protected function _prepareValues($attr) 
    {
        $data = parent::_prepareValues($attr);
        $data = array_merge($data, array(
            'frontend_input_renderer'   => $this->_getValue($attr, 'input_renderer', ''),
            'is_global'                 => $this->_getValue($attr, 'global', 1),
            'is_visible'                => $this->_getValue($attr, 'visible', 1),
            'is_searchable'             => $this->_getValue($attr, 'searchable', 0),
            'is_filterable'             => $this->_getValue($attr, 'filterable', 0),
            'is_comparable'             => $this->_getValue($attr, 'comparable', 0),
            'is_visible_on_front'       => $this->_getValue($attr, 'visible_on_front', 0),
            'is_wysiwyg_enabled'        => $this->_getValue($attr, 'wysiwyg_enabled', 0),
            'is_html_allowed_on_front'  => $this->_getValue($attr, 'is_html_allowed_on_front', 0),
            'is_visible_in_advanced_search'
                                        => $this->_getValue($attr, 'visible_in_advanced_search', 0),
            'is_filterable_in_search'   => $this->_getValue($attr, 'filterable_in_search', 0),
            'used_in_product_listing'   => $this->_getValue($attr, 'used_in_product_listing', 0),
            'used_for_sort_by'          => $this->_getValue($attr, 'used_for_sort_by', 0),
            'apply_to'                  => $this->_getValue($attr, 'apply_to', ''),
            'position'                  => $this->_getValue($attr, 'position', 0),
            'is_configurable'           => $this->_getValue($attr, 'is_configurable', 1),
            'is_used_for_promo_rules'   => $this->_getValue($attr, 'used_for_promo_rules', 0)
        ));
        return $data;
    }
    
    /**
     * Get entities to setup
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        return array(
            'catalog_product' => array(
                'entity_model'                => 'catalog/product',
                'attribute_model'             => 'catalog/resource_eav_attribute',
                'table'                       => 'catalog/product',
                'additional_attribute_table'  => 'catalog/eav_attribute',
                'entity_attribute_collection' => 'catalog/product_attribute_collection',       
                'attributes'                  => array(
                    'personalization_enabled' => array(
                        'group'                   => 'Personalization',
                        'type'                    => 'int',
                        'backend'                 => '',
                        'frontend'                => '',
                        'label'                   => 'Enable Personalization',
                        'input'                   => 'boolean',
                        'class'                   => '',
                        'source'                  => '',
                        'global'                  => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'                 => true,
                        'required'                => false,
                        'user_defined'            => false,
                        'default'                 => '',
                        'searchable'              => false,
                        'filterable'              => false,
                        'comparable'              => false,
                        'visible_on_front'        => false,
                        'unique'                  => false,
                        'apply_to'                => 'simple',
                        'used_in_product_listing' => 1
                    ),
                    'personalization_template_id' => array(
                        'group'             => 'Personalization',
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Product ID',
                        'input'             => 'text',
                        'class'             => '',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'default'           => '',
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'unique'            => false,
                        'apply_to'          => 'simple'
                    )
                )                     
            )
        );
    }
}