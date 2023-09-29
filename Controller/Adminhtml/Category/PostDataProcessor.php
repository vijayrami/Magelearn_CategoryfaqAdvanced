<?php

namespace Magelearn\Categoryfaq\Controller\Adminhtml\Category;

use Magelearn\Categoryfaq\Model\CategoryFactory;
use Magelearn\Categoryfaq\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Model\Layout\Update\ValidatorFactory;

class PostDataProcessor
{
    /**
     * Date Filter
     *
     * @var Date
     */
    protected $dateFilter;

    /**
     * Validation Factory
     *
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * Message Manager
     *
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * CollectionFactory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * PostDataProcessor Class constructor
     *
     * @param Date               $dateFilter         DateFiletr
     * @param ManagerInterface   $messageManager     MessageManager
     * @param ValidatorFactory   $validatorFactory   ValidationFactory
     * @param CollectionFactory  $collectionFactory  CollectionFactory
     */
    public function __construct(
        Date $dateFilter,
        ManagerInterface $messageManager,
        ValidatorFactory $validatorFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
        $this->validatorFactory = $validatorFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Validate post data
     *
     * @param array $data Datapost
     *
     * @return bool
     */
    public function validate($data, $sessionData = false)
    {
        $errorNo1 = $this->validateRequireEntry($data, $sessionData);
        $errorNo2 = $this->checkNameExist($data);
        $errorNo3 = true;

        if (!in_array($data['status'], [0,1]) || $data['status'] == '' || $data['status'] === null) {
            $errorNo3 = false;
            $this->messageManager->addErrorMessage(
                __("Please enter valid status.")
            );
        }

        return $errorNo1 && $errorNo2 && $errorNo3;
    }

    /**
     * Check if required fields is not empty
     *
     * @param array $data RequireFields
     *
     * @return bool
     */
    public function validateRequireEntry(array $data, $sessionData)
    {
        if($sessionData) {
            $requiredFields = [
                'name' => __('Category Name')
            ];
        } else {
            $requiredFields = [
                'name' => __('Category Name'),
                'sort_order' => __('Sort Order')
            ];
        }

        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __(
                        'To apply changes you should fill valid value to required "%1" field',
                        $requiredFields[$field]
                    )
                );
            }
        }
        return $errorNo;
    }

    /**
     * Check if name is already exist or not
     *
     * @param array $data RequireFields
     *
     * @return bool
     */
    public function checkNameExist(array $data)
    {
        $errorNo = true;
        if (isset($data['category_id'])) {
            $categoryCollection = $this->collectionFactory->create()
                ->addFieldToFilter('category_id', ['neq' => $data['category_id']]);
        } else {
            $categoryCollection = $this->collectionFactory->create();
        }
        foreach ($categoryCollection as $category) {
            $categoryName = trim(mb_strtolower(preg_replace('/\s+/', ' ', $category->getName()), 'UTF-8'));
            if (trim(preg_replace('/\s+/', ' ', mb_strtolower($data['name'], 'UTF-8'))) == $categoryName) {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __('This name is already exist.')
                );
            }
        }
        return $errorNo;
    }
}
