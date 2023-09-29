<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Model;

use Magelearn\Categoryfaq\Api\Data\QuestionInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magelearn\Categoryfaq\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class Question extends AbstractModel implements QuestionInterface {
    
    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;
    
    /**
     * Faq Category Collection
     *
     * @var ResourceModel\Category\Collection
     */
    public $categoryCollection;
    
    /**
     * Faq Category Collection Factory
     *
     * @var CategoryCollectionFactory
     */
    public $categoryCollectionFactory;
    
    public function __construct(
        Context $context,
        Registry $registry,
        DateTimeFactory $dateTimeFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
        ) {
            
            parent::__construct($context, $registry, $resource, $resourceCollection, $data);
            $this->dateTimeFactory = $dateTimeFactory;
            $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Magelearn\Categoryfaq\Model\ResourceModel\Question::class);
    }

    /**
     * @inheritDoc
     */
    public function getQuestionId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setQuestionId($questionid)
    {
        return $this->setData(self::QUESTION_ID, $questionid);
    }
    
    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values             = [];
        $values['status']   = '1';
        
        return $values;
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getAnswer()
    {
        $answer = $this->getData(self::ANSWER);
        
        $maxLength = 200;
        if ($answer && strlen($answer) > $maxLength) {
            $answer = substr($answer, 0, $maxLength) . '...';
        }
        
        return $answer;
    }

    /**
     * @inheritDoc
     */
    public function setAnswer($answer)
    {
        return $this->setData(self::ANSWER, $answer);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }
    
    /**
     * @return ResourceModel\Category\Collection
     */
    public function getSelectedCategoriesCollection()
    {
        if ($this->categoryCollection === null) {
            $collection = $this->categoryCollectionFactory->create();
            $collection->join(
                $this->getResource()->getTable('magelearn_categoryfaq_question_category'),
                'main_table.category_id=' . $this->getResource()->getTable('magelearn_categoryfaq_question_category') .
                '.category_id AND ' . $this->getResource()->getTable('magelearn_categoryfaq_question_category') . '.question_id="'
                . $this->getId() . '"',
                ['position']
                );
            $this->categoryCollection = $collection;
        }
        
        return $this->categoryCollection;
    }
    /**
     * @return array
     * @throws LocalizedException
     */
    public function getCategoryIds()
    {
        if (!$this->hasData('category_ids')) {
            $ids = $this->_getResource()->getCategoryIds($this);
            $this->setData('category_ids', $ids);
        }
        
        return (array) $this->_getData('category_ids');
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
    /**
     * Set updated_at before saving
     *
     * @return AbstractModel
     */
    public function beforeSave()
    {
        if ($this->getId()) {
            $this->setUpdatedAt($this->dateTimeFactory->create()->gmtDate());
        }
        
        return parent::beforeSave();
    }
}
