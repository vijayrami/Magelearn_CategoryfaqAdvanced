<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magelearn\Categoryfaq\Model\CategoryFactory;

abstract class Category extends Action
{

    const ADMIN_RESOURCE = 'Magelearn_Categoryfaq::management';
    
    /**
     * Category Factory
     *
     * @var CategoryFactory
     */
    public $categoryFactory;
    
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Magelearn'), __('Magelearn'))
            ->addBreadcrumb(__('Category'), __('Category'));
        return $resultPage;
    }
    
    /**
     * @param bool $register
     * @param bool $isSave
     *
     * @return bool|\Magelearn\ProductsGrid\Model\Category
     */
    public function initCategory($register = false)
    {
        $categoryId = (int)$this->getRequest()->getParam('id');
        
        /** @var \Magelearn\Categoryfaq\Model\Category $category */
        //Model/Category
        $category = $this->categoryFactory->create();
        if ($categoryId) {
            $category->load($categoryId);
            if (!$category->getId()) {
                $this->messageManager->addErrorMessage(__('This category no longer exists.'));
                
                return false;
            }
        }
        
        if ($register) {
            $this->coreRegistry->register('magelearn_categoryfaq_category', $category);
        }
        
        return $category;
    }
}

