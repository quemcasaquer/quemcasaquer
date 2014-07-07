<?php
/**
 * MoIP - Moip Payment Module
 *
 * @title      Magento -> Custom Payment Module for Moip (Brazil)
 * @category   Payment Gateway
 * @package    O2TI_Moip
 * @author     MoIP Pagamentos S/a
 * @copyright  Copyright (c) 2013 O2ti Soluções Web
 * @license    Licença válida por tempo indeterminado
 */
$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$config = array(
                'position' => 1,
                'required'=> 0,
                'label' => 'Proibir Compra Por Boleto:',
                'type' => 'int',
                'input'=>'boolean',
                'apply_to'=>'simple,bundle,grouped,configurable',
                'note'=>'Proibe a compra do carrinho com boleto caso o produto esteja no carrinho'
            );

$setup->addAttribute('catalog_product', 'proibir_boleto' , $config);

$installer->endSetup();
?>
