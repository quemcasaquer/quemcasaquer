<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_MeigeewidgetsBlacknwhite_Model_Templates
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'meigee/meigeewidgetsblacknwhite/grid.phtml', 'label'=>'Grid'),
            array('value'=>'meigee/meigeewidgetsblacknwhite/list.phtml', 'label'=>'List'),
            array('value'=>'meigee/meigeewidgetsblacknwhite/slider.phtml', 'label'=>'Slider')
        );
    }

}