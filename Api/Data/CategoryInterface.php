<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Api\Data;

interface CategoryInterface
{
    const STATUS = 'status';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const ICON = 'icon';
    const SORT_ORDER = 'sort_order';
    const CATEGORY_ID = 'category_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get id
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     * @param int $id
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
     */
    public function setId($id);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
     */
    public function setName($name);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
     */
    public function setDescription($description);
    
    /**
     * Get icon
     * @return string|null
     */
    public function getIcon();
    
    /**
     * Set icon
     * @param string $icon
     * @return \Magelearn\Categoryfaq\Api\Data\CategoryInterface
     */
    public function setIcon($icon);
    
    /**
     * Get sort_order
     * @return int/null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param int $sortOrder
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get status
     * @return int/null
     */
    public function getStatus();

    /**
     * Set status
     * @param int $status
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
     */
    public function setStatus($status);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
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
     * @return \Magelearn\Categoryfaq\Category\Api\Data\CategoryInterface
     */
    public function setUpdatedAt($updatedAt);
}

