<?php
/**
 * Custom adminhtml sales order item renderer
 *
 */
class PrintScience_Personalization_Block_Adminhtml_Sales_Order_View_Items_Renderer
extends Mage_Adminhtml_Block_Sales_Order_View_Items_Renderer_Default
{
    /**
     * Get URL to personalization preview PDF file of order item
     *
     * @return string
     */
    public function getPersonalizationPreviewPdfUrl()
    {
        $orderHelper = Mage::helper('printscience_personalization/order');
        $data = $orderHelper->getItemData($this->getItem());
        if (empty($data['preview_pdf_url'])) {
            return false;
        }
        return $data['preview_pdf_url'];
    }
}