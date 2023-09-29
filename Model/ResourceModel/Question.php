<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Model\ResourceModel;

use Magento\Backend\Model\Auth;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magelearn\Categoryfaq\Model\Question as QuestionModel;

class Question extends AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'question_id';
    /**
     * Date model
     *
     * @var DateTime
     */
    public $date;
    
    /**
     * Event Manager
     *
     * @var ManagerInterface
     */
    public $eventManager;
    
    /**
     * Question Category relation model
     *
     * @var string
     */
    public $questionCategoryTable;
    
    /**
     * @var Auth
     */
    protected $_auth;
    
    /**
     * @var RequestInterface
     */
    protected $_request;

    
    /**
     * Question constructor.
     *
     * @param Context $context
     * @param DateTime $date
     * @param ManagerInterface $eventManager
     * @param Auth $auth
     * @param RequestInterface $request
     */
    public function __construct(
        Context $context,
        DateTime $date,
        ManagerInterface $eventManager,
        Auth $auth,
        RequestInterface $request
        ) {
            $this->date           = $date;
            $this->eventManager   = $eventManager;
            $this->_auth          = $auth;
            $this->_request       = $request;
            parent::__construct($context);

            $this->questionCategoryTable = $this->getTable('magelearn_categoryfaq_question_category');
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('magelearn_categoryfaq_question', 'question_id');
    }
    
    /**
     * @param QuestionModel|AbstractModel $object
     * @return AbstractDb
     * @throws LocalizedException
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveCategoryRelation($object);
        
        return parent::_afterSave($object);
    }
    
    /**
     * @param PostModel $post
     *
     * @return $this
     * @throws LocalizedException
     */
    public function saveCategoryRelation(QuestionModel $question)
    {
        $question->setIsChangedCategoryList(false);
        $id             = $question->getId();
        $categories     = $question->getCategoriesIds();
        $oldCategoryIds = $question->getCategoryIds();
        
        if ($categories === null) {
            return $this;
        }
        
        $insert         = array_diff($categories, $oldCategoryIds);
        $delete         = array_diff($oldCategoryIds, $categories);
        $adapter        = $this->getConnection();
        
        if (!empty($delete)) {
            $condition = ['category_id IN(?)' => $delete, 'question_id=?' => $id];
            $adapter->delete($this->questionCategoryTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $categoryId) {
                $data[] = [
                    'question_id'     => (int) $id,
                    'category_id' => (int) $categoryId,
                    'position'    => 1
                ];
            }
            $adapter->insertMultiple($this->questionCategoryTable, $data);
        }
        if (!empty($insert) || !empty($delete)) {
            $categoryIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->eventManager->dispatch(
                'magelearn_categoryfaq_question_change_categories',
                ['question' => $question, 'category_ids' => $categoryIds]
                );
        }
        if (!empty($insert) || !empty($delete)) {
            $question->setIsChangedCategoryList(true);
            $categoryIds = array_keys($insert + $delete);
            $question->setAffectedCategoryIds($categoryIds);
        }
        
        return $this;
    }

    /**
     * @param QuestionModel $question
     *
     * @return array
     */
    public function getCategoryIds(QuestionModel $question)
    {
        $adapter = $this->getConnection();
        $select  = $adapter->select()->from(
            $this->questionCategoryTable,
            'category_id'
            )
            ->where(
                'question_id = ?',
                (int) $question->getId()
                );
            
        return $adapter->fetchCol($select);
    }
}

