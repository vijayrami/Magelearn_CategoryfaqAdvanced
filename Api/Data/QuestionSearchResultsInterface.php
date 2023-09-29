<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Api\Data;

interface QuestionSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get question list.
     * @return \Magelearn\Categoryfaq\Api\Data\QuestionInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \Magelearn\Categoryfaq\Api\Data\QuestionInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}

