<?php

declare(strict_types=1);
 
namespace Magelearn\Categoryfaq\ViewModel;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magelearn\Categoryfaq\Model\ResourceModel\Question\CollectionFactory;
use Magelearn\Categoryfaq\Model\CategoryFactory;
 
class Questions extends DataObject implements ArgumentInterface
{
    /*
     * This label won't be displayed in the frontend block
     */
    public const MAIN_LABEL = 'Default';
    
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    
    /**
     * @var CategoryFactory
     */
    public $categoryFactory;
    
    public function __construct(
        CollectionFactory $collectionFactory,
        CategoryFactory $categoryFactory
        ) {
            $this->collectionFactory = $collectionFactory;
            $this->categoryFactory    = $categoryFactory;
            parent::__construct();
    }
    
    /**
     * Get All Questions
     *
     * @return \Magento\Framework\DataObject[]
     */
    public function getItems()
    {
        $questionCollection = $this->collectionFactory->create();
        $questionCollection->addFieldToFilter('main_table.status', 1);
        $questionCollection->setOrder('sort_order', 'DESC');
        
        return $questionCollection->getItems();
    }
    /**
     * Get list category html of question
     *
     * @param Question $question
     *
     * @return string|null
     */
    public function getQuestionCategoryHtml($question)
    {
        $categoryHtml = [];
        
        try {
            if (!$question->getCategoryIds()) {
                return null;
            }
            
            $categories = $this->getCategoryCollection($question->getCategoryIds());
            foreach ($categories as $_cat) {
                $categoryHtml[] = $_cat->getName();
            }
        } catch (Exception $e) {
            return null;
        }
        
        return implode(', ', $categoryHtml);
    }
    /**
     * @param $array
     *
     * @return \Magento\Sales\Model\ResourceModel\Collection\AbstractCollection
     */
    public function getCategoryCollection($array)
    {
        try {
            $collection = $this->getObjectList()
            ->addFieldToFilter('category_id', ['in' => $array]);
            
            return $collection;
        } catch (Exception $exception) {
            $this->_logger->error($exception->getMessage());
        }
        
        return null;
    }
    
    public function getObjectList($storeId = null)
    {
        $collection = $this->categoryFactory
        ->create()
        ->getCollection()
        ->addFieldToFilter('status', 1);
        
        return $collection;
    }
}