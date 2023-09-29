<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionRepositoryInterface
{

    /**
     * Save question
     * @param \Magelearn\Categoryfaq\Api\Data\QuestionInterface $question
     * @return \Magelearn\Categoryfaq\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Magelearn\Categoryfaq\Api\Data\QuestionInterface $question
    );

    /**
     * Retrieve question
     * @param string $questionId
     * @return \Magelearn\Categoryfaq\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($questionId);

    /**
     * Retrieve question matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magelearn\Categoryfaq\Api\Data\QuestionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete question
     * @param \Magelearn\Categoryfaq\Api\Data\QuestionInterface $question
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Magelearn\Categoryfaq\Api\Data\QuestionInterface $question
    );

    /**
     * Delete question by ID
     * @param string $questionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($questionId);
    
    /**
     * @param string $questionId
     *
     * @return \Magelearn\Categoryfaq\Api\Data\CategoryInterface[]
     */
    public function getCategoriesByQuestionId($questionId);
}

