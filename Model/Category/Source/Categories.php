<?php

namespace Magelearn\Categoryfaq\Model\Category\Source;

use Magelearn\Categoryfaq\Model\ResourceModel\Category\CollectionFactory;

class Categories implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var null|array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Return associative array with Category IDs and Names
     *
     * @return array|null
     */
    public function toOptionArray()
    {
        if (null == $this->options) {
            $this->options = $this->collectionFactory->create()
                ->addFieldToFilter('status', true)
                ->setOrder('sort_order','ASC')
                ->toOptionArray();
        }
        return $this->options;
    }
}