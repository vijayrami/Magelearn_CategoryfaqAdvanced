<?php

namespace Magelearn\Categoryfaq\Setup;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;

/**
 * Class Uninstall
 *
 * @package Magelearn\Categoryfaq\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Drop sample table
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($setup->tableExists('magelearn_categoryfaq_category')) {
            $setup->getConnection()->dropTable('magelearn_categoryfaq_category');
        }
        if ($setup->tableExists('magelearn_categoryfaq_question')) {
            $setup->getConnection()->dropTable('magelearn_categoryfaq_question');
        }
        if ($setup->tableExists('magelearn_categoryfaq_question_category')) {
            $setup->getConnection()->dropTable('magelearn_categoryfaq_question_category');
        }
    }
}