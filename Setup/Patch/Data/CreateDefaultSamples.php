<?php

namespace Magelearn\Categoryfaq\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateDefaultSamples implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    public function __construct(
       ModuleDataSetupInterface $moduleDataSetup

     ) {

        $this->moduleDataSetup = $moduleDataSetup;

    }
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $setup = $this->moduleDataSetup;

        $category_data = [
                    [
                        'name' => __('Default'),
                        'description' => __('When there is only 1 active category, only its questions will be displayed.'),
                        'sort_order'  => 0,
                        'status'      => 1
                    ]
                ];

         $this->moduleDataSetup->getConnection()->insertArray(
            $this->moduleDataSetup->getTable('magelearn_categoryfaq_category'),
            ['name', 'description','sort_order','status'],
             $category_data
         );     
         
         $question_data = [
             [
                 'title'  => __('What should I use this module for?'),
                 'answer' => __('Category FAQ module was designed as a simple solution to provide information for your customers'),
                 'status' => 1,
                 'sort_order'  => 0
             ]
         ];
         $this->moduleDataSetup->getConnection()->insertArray(
             $this->moduleDataSetup->getTable('magelearn_categoryfaq_question'),
             ['title', 'answer','status','sort_order'],
             $question_data
             );

         $question_category_data = [
             [
                 'category_id'  => 1,
                 'question_id' => 1,
                 'position' => 1
             ]
         ];
         $this->moduleDataSetup->getConnection()->insertArray(
             $this->moduleDataSetup->getTable('magelearn_categoryfaq_question_category'),
             ['category_id', 'question_id','position'],
             $question_category_data
             );
         
        $this->moduleDataSetup->endSetup();
    }
    public function getAliases()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        
        $this->moduleDataSetup->getConnection()->endSetup();
    }
    public static function getDependencies()
    {
        return [];
    }
}