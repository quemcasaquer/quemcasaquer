<?php
$installer = $this;
$installer->installEntities();

if (!$installer->getConnection()->fetchOne("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$this->getTable('sales/quote_item')}' AND COLUMN_NAME = 'personalization_info'")) {
    $installer->run("
        ALTER TABLE {$this->getTable('sales/quote_item')} ADD `personalization_info` TEXT NULL
    ");
}

$installer->startSetup();
$installer->updateAttribute('catalog_product', 'personalization_template_id', 'apply_to', implode(',',array('simple','configurable','bundle')));
$installer->updateAttribute('catalog_product', 'personalization_enabled', 'apply_to', implode(',',array('simple','configurable','bundle')));
$installer->endSetup();