<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Model;

use Magelearn\Categoryfaq\Api\Data\QuestionInterface;
use Magelearn\Categoryfaq\Api\Data\QuestionInterfaceFactory;
use Magelearn\Categoryfaq\Api\Data\QuestionSearchResultsInterfaceFactory;
use Magelearn\Categoryfaq\Api\QuestionRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magelearn\Categoryfaq\Model\ResourceModel\Question as ResourceQuestion;
use Magelearn\Categoryfaq\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class QuestionRepository implements QuestionRepositoryInterface
{

    /**
     * @var ResourceQuestion
     */
    protected $resource;

    /**
     * @var Question
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var QuestionInterfaceFactory
     */
    protected $questionFactory;

    /**
     * @var QuestionCollectionFactory
     */
    protected $questionCollectionFactory;
    
    /**
     * @var DateTime
     */
    protected $date;


    /**
     * @param ResourceQuestion $resource
     * @param QuestionInterfaceFactory $questionFactory
     * @param QuestionCollectionFactory $questionCollectionFactory
     * @param QuestionSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DateTime $date
     */
    public function __construct(
        ResourceQuestion $resource,
        QuestionInterfaceFactory $questionFactory,
        QuestionCollectionFactory $questionCollectionFactory,
        QuestionSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        DateTime $date
    ) {
        $this->resource = $resource;
        $this->questionFactory = $questionFactory;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->date = $date;
    }

    /**
     * @inheritDoc
     */
    public function save(QuestionInterface $question)
    {
        try {
            $this->resource->save($question);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the question: %1',
                $exception->getMessage()
            ));
        }
        return $question;
    }

    /**
     * @inheritDoc
     */
    public function get($questionId)
    {
        $question = $this->questionFactory->create();
        $this->resource->load($question, $questionId);
        if (!$question->getId()) {
            throw new NoSuchEntityException(__('question with id "%1" does not exist.', $questionId));
        }
        return $question;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->questionCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(QuestionInterface $question)
    {
        try {
            $questionModel = $this->questionFactory->create();
            $this->resource->load($questionModel, $question->getQuestionId());
            $this->resource->delete($questionModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the question: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($questionId)
    {
        return $this->delete($this->get($questionId));
    }
    /**
     * @param array $data
     */
    protected function prepareData(&$data)
    {
        if (!empty($data['categories_ids'])) {
            $data['categories_ids'] = explode(',', $data['categories_ids']);
        }
        
        if (empty($data['status'])) {
            $data['status'] = 0;
        }
        
        $data['created_at'] = $this->date->date();
    }
    
    /**
     * @inheritDoc
     */
    public function getCategoriesByQuestionId($questionId)
    {
        $question = $this->questionFactory->create()->load($questionId);
        
        return $question->getSelectedCategoriesCollection()->getItems();
    }
}

