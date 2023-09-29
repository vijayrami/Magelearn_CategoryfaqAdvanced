<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magelearn\Categoryfaq\Controller\Adminhtml\Category;

use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\MessageInterface;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magelearn\Categoryfaq\Model\CategoryFactory;
use Magelearn\Categoryfaq\Model\ImageUploader;

class Save extends \Magelearn\Categoryfaq\Controller\Adminhtml\Category
{
    /**
     * Result Raw Factory
     *
     * @var RawFactory
     */
    public $resultRawFactory;
    
    /**
     * Result Json Factory
     *
     * @var JsonFactory
     */
    public $resultJsonFactory;
    
    /**
     * Layout Factory
     *
     * @var LayoutFactory
     */
    public $layoutFactory;
    
    /**
     * DataProcessor
     *
     * @var PostDataProcessor
     */
    protected $dataProcessor;
    
    /**
     * Category Factory
     *
     * @var CategoryFactory
     */
    public $categoryFactory;
    
    protected $dataPersistor;
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    
    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param Registry $coreRegistry
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param RawFactory $resultRawFactory
     * @param JsonFactory $resultJsonFactory
     * @param LayoutFactory $layoutFactory
     * @param PostDataProcessor $dataProcessor,
     * @param CategoryFactory $categoryFactory,
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Registry $coreRegistry,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        RawFactory $resultRawFactory,
        JsonFactory $resultJsonFactory,
        LayoutFactory $layoutFactory,
        PostDataProcessor $dataProcessor,
        CategoryFactory $categoryFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ImageUploader $imageUploader
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->date = $date;
        $this->imageUploader = $imageUploader;
        parent::__construct($context, $coreRegistry, $categoryFactory);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->getRequest()->getPost('return_session_messages_only')) {
            $category = $this->initCategory();
            $categoryPostData = $this->getRequest()->getPostValue();
            $categoryPostData['status'] = 1;
            
            $category->addData($categoryPostData);

            try {
                if ($this->dataProcessor->validate($categoryPostData, true)) {
                    $category->save();
                    $this->messageManager->addSuccessMessage(__('You saved the category.'));
                }
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_objectManager->get(LoggerInterface::class)->critical($e);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_objectManager->get(LoggerInterface::class)->critical($e);
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the category.'));
                $this->_objectManager->get(LoggerInterface::class)->critical($e);
            }
            
            $hasError = (bool)$this->messageManager->getMessages()->getCountByType(
                MessageInterface::TYPE_ERROR
                );
            
            $category->load($category->getId());
            $category->addData([
                'level' => 1,
                'entity_id' => $category->getId(),
                'is_active' => $category->getEnabled(),
                'parent' => 0
            ]);
            
            // to obtain truncated category name
            /** @var $block Messages */
            $block = $this->layoutFactory->create()->getMessagesBlock();
            $block->setMessages($this->messageManager->getMessages(true));
            
            /** @var Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            
            return $resultJson->setData(
                [
                    'messages' => $block->getGroupedHtml(),
                    'error' => $hasError,
                    'category' => $category->toArray()
                ]
                );
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            if (!$this->dataProcessor->validate($data, false)) {
                $this->dataPersistor->set('magelearn_categoryfaq_category', $data);
                if (!empty($data['category_id'])) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'id' => $data['category_id'],
                            '_current' => true
                        ]
                        );
                } else {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            '_current' => true
                        ]
                        );
                }
            }
            
            $id = $this->getRequest()->getParam('category_id');
            
            $category = $this->categoryFactory->create();
            $model = $category->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Category no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            
            $data['updated_at'] =  $this->date->gmtDate();
            if (isset($data['icon'][0]['name'])) {
                $data['icon'] = $data['icon'][0]['name'];
            } else {
                $data['icon'] = null;
            }
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Category.'));
                $this->dataPersistor->clear('magelearn_categoryfaq_category');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Category.'));
            }
        
            $this->dataPersistor->set('magelearn_categoryfaq_category', $data);
            return $resultRedirect->setPath('*/*/edit', ['category_id' => $this->getRequest()->getParam('category_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

