<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Api\Data;

interface CategorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get category list.
     * @return \Magelearn\Categoryfaq\Api\Data\CategoryInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \Magelearn\Categoryfaq\Api\Data\CategoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}

