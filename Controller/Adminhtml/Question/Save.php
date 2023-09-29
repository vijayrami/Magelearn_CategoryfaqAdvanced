<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Controller\Adminhtml\Question;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magelearn\Categoryfaq\Model\Question as QuestionModel;
use Magelearn\Categoryfaq\Model\QuestionFactory;
use Magelearn\Categoryfaq\Controller\Adminhtml\Question;
use RuntimeException;

/**
 * Class Save
 * @package Magelearn\Categoryfaq\Controller\Adminhtml\Question
 */
class Save extends Question
{
    /**
     * JS helper
     *
     * @var Js
     */
    public $jsHelper;
    
    /**
     * @var DateTime
     */
    public $date;

    protected $dataPersistor;
    
    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param QuestionFactory $questionFactory
     * @param DateTime $date
     * @param TimezoneInterface $timezone
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        QuestionFactory $questionFactory,
        Js $jsHelper,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        DateTime $date,
        TimezoneInterface $timezone
    ) {
        $this->jsHelper     = $jsHelper;
        $this->dataPersistor = $dataPersistor;
        $this->date         = $date;
        $this->timezone     = $timezone;
        parent::__construct($questionFactory, $context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $action         = $this->getRequest()->getParam('action');
        
        if ($data = $this->getRequest()->getPost('question')) {
            /** @var QuestionModel $question */
            $question = $this->initQuestion(false, true);

            $this->prepareData($question, $data);
        
            $this->_eventManager->dispatch(
                'magelearn_categoryfaq_question_prepare_save',
                ['question' => $question, 'request' => $this->getRequest()]
                );

            try {
                if (empty($action) || $action === 'add') {
                    $question->save();
                    $this->messageManager->addSuccessMessage(__('The question has been saved.'));
                }
                
                $this->_getSession()->setData('magelearn_item_post_data', false);
                $this->dataPersistor->clear('magelearn_categoryfaq_question');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['question_id' => $question->getId()]);
                } else {
                    return $resultRedirect->setPath('magelearn_categoryfaq/*/');
                }
                
                return $resultRedirect->setPath('*/*/');
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Question.'));
            }
        
            $this->dataPersistor->set('magelearn_categoryfaq_question', $data);
            return $resultRedirect->setPath('magelearn_categoryfaq/*/edit', ['question_id' => $this->getRequest()->getParam('question_id'), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    /**
     * @param QuestionModel $question
     * @param array $data
     *
     * @return $this
     * @throws LocalizedException
     */
    protected function prepareData($question, $data = [])
    {
        
        /** Set specify field data */
        $data['categories_ids'] = (isset($data['categories_ids']) && $data['categories_ids']) ? explode(
            ',',
        $data['categories_ids'] ?? ''
        ) : [];
        
        if ($question->getCreatedAt() == null) {
            $data['created_at'] = $this->date->date();
        }
        $data['updated_at'] = $this->date->date();

        $question->addData($data);
        
        return $this;
    }
}

