<?php

namespace Mageplaza\BannerSlider\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Upgrade the Catalog module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface {

    protected $eavSetupFactory;
    protected $_io;
    protected $_directoryList;

    public function __construct(
    \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory, \Magento\Framework\Filesystem\Io\File $io, \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_io = $io;
        $this->_directoryList = $directoryList;
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $installer->getConnection()->addColumn(
                    $installer->getTable('mageplaza_bannerslider_slider'), 'category_id', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                'length' => null,
                'nullable' => false,
                'comment' => 'Category Id'
            ]);
        }
        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $installer->getConnection()->addColumn(
                    $installer->getTable('mageplaza_bannerslider_slider'), 'banner_location', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                'length' => null,
                'nullable' => false,
                'comment' => 'Banner Location'
            ]);
        }
        if (version_compare($context->getVersion(), '2.0.2.2', '<')) {
            $installer->getConnection()->addColumn(
                    $installer->getTable('mageplaza_bannerslider_banner'), 'mobile_link_id', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'length' => 5,
                'default' => 0,
                'nullable' => false,
                'comment' => 'Mobile Link ID'
            ]);
           
        }
         if (version_compare($context->getVersion(), '2.0.2.2', '<')) {
          
            $installer->getConnection()->addColumn(
                    $installer->getTable('mageplaza_bannerslider_banner'), 'mobile_type', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 55,
                'default' => '',
                'nullable' => false,
                'comment' => 'Mobile type'
            ]);
        }
        $setup->endSetup();
    }

}
