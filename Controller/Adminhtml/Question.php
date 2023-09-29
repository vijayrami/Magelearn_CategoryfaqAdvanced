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
use Magelearn\Categoryfaq\Model\QuestionFactory;

abstract class Question extends Action
{
    const ADMIN_RESOURCE = 'Magelearn_Categoryfaq::management';
    
    /**
     * Question Factory
     *
     * @var QuestionFactory
     */
    public $questionFactory;
    
    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * @param QuestionFactory $questionFactory
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        QuestionFactory $questionFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->questionFactory = $questionFactory;
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
            ->addBreadcrumb(__('Question'), __('Question'));
        return $resultPage;
    }
    
    /**
     * @param bool $register
     * @param bool $isSave
     *
     * @return bool|\Magelearn\Categoryfaq\Model\Question
     */
    protected function initQuestion($register = false, $isSave = false)
    {
        $questionId = (int)$this->getRequest()->getParam('id');

        /** @var \Magelearn\Categoryfaq\Model\Question $question */
        $question = $this->questionFactory->create();
        if ($questionId) {
            if (!$isSave) {
                $question->load($questionId);
                if (!$question->getId()) {
                    $this->messageManager->addErrorMessage(__('This question no longer exists.'));
                    
                    return false;
                }
            }
        }
        
        if ($register) {
            $this->coreRegistry->register('magelearn_categoryfaq_question', $question);
        }
        
        return $question;
    }
}

