<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Api\Data;

interface QuestionInterface
{
    const QUESTION_ID = 'question_id';
    const TITLE = 'title';
    const ANSWER = 'answer';
    const STATUS = 'status';
    const SORT_ORDER = 'sort_order';
    const CATEGORY_IDS = 'category_ids';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    /**
     * Get Question id
     * @return int|null
     */
    public function getQuestionId();

    /**
     * Set Question id
     * @param int $id
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setQuestionId($questionid);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setTitle($title);

    /**
     * Get answer
     * @return string|null
     */
    public function getAnswer();

    /**
     * Set answer
     * @param string $answer
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setAnswer($answer);

    /**
     * Get status
     * @return int|null
     */
    public function getStatus();

    /**
     * Set status
     * @param int $status
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setStatus($status);

    /**
     * Get sort_order
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param int $sortOrder
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Magelearn\Categoryfaq\Question\Api\Data\QuestionInterface
     */
    public function setUpdatedAt($updatedAt);
    
    /**
     * @return int[]|null
     */
    public function getCategoryIds();
}

