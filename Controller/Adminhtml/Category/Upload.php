<?php

namespace Magelearn\Categoryfaq\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magelearn\Categoryfaq\Model\CategoryFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magelearn\Categoryfaq\Model\ImageUploader;

class Upload extends \Magelearn\Categoryfaq\Controller\Adminhtml\Category
{
    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    public $imageUploader;
    
    /**
     * Upload constructor.
     *
     * @param Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param CategoryFactory $categoryFactory
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        CategoryFactory $categoryFactory,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context, $coreRegistry, $categoryFactory);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Upload file controller action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('icon');

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
