<?php

namespace Mtwo\Pickup\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class InstallData implements InstallDataInterface {

    private $eavSetupFactory;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    public function __construct(
    Config $eavConfig, EavSetupFactory $eavSetupFactory, AttributeSetFactory $attributeSetFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
    ModuleDataSetupInterface $setup, ModuleContextInterface $context
    ) {
        $installer = $setup;
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

         

        $eavSetup->addAttribute('customer_address', 'addresscategory', [
            'label' => 'Address Category',
            'input' => 'text',
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'required' => false,
            'position' => 332,
            'visible' => false,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false,
            'backend' => '',
			'visible_on_front' => false

        ]);
 

        $customAttribute3 = $this->eavConfig->getAttribute('customer_address', 'addresscategory');
        $customAttribute3->setData(
                'used_in_forms', ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );
        $customAttribute3->save();
 
        $installer->getConnection()->addColumn(
                $installer->getTable('quote_address'), 'addresscategory', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' => 'Address Type'
                ]
        ); 
        $installer->getConnection()->addColumn(
                $installer->getTable('sales_order_address'), 'addresscategory', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length' => 255,
            'comment' => 'Address Type'
                ]
        );
    }

}
