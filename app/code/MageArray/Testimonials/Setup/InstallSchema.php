<?php namespace MageArray\Testimonials\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;

use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Framework\Setup\SchemaSetupInterface;

use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('magearray_testimonials'))
            ->addColumn(

                'testimonial_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Testimonial ID'
            )
            ->addColumn('name', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
            ->addColumn('email', Table::TYPE_TEXT, 255, ['nullable' => false], 'Email')
            ->addColumn('website', Table::TYPE_TEXT, 255, [], 'Website')
            ->addColumn('company', Table::TYPE_TEXT, 255, [], 'Company')
            ->addColumn('address', Table::TYPE_TEXT, 255, [], 'Address')
            ->addColumn('avatar_name', Table::TYPE_TEXT, 255, [], 'Avatar Name')
            ->addColumn('avatar_path', Table::TYPE_TEXT, 255, [], 'Avatar Path')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Active?')
            ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
            ->setComment('Testimonials');
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
