<?php

namespace Magelearn\Categoryfaq\Ui\Component\Listing\Column;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class CategoryIcon extends Column
{
    private $storeManager;

    /**
     * @var Repository
     */
    private $assetRepo;

    /**
     * @var UrlInterface
     */
    private $_backendUrl;

    /**
     * GroupIcon constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param Repository $assetRepo
     * @param UrlInterface $backendUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        Repository $assetRepo,
        UrlInterface $backendUrl,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
        $this->_backendUrl = $backendUrl;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $path = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . 'faq/tmp/icon/';
            $baseImage = $this->assetRepo->getUrl('Magelearn_Categoryfaq::images/faq.png');
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item[$fieldName]) {
                    $item[$fieldName . '_src'] = $path . $item['icon'];
                    $item[$fieldName . '_alt'] = $item['name'];
                    $item[$fieldName . '_orig_src'] = $path . $item['icon'];
                } else {
                    $item[$fieldName . '_src'] = $baseImage;
                    $item[$fieldName . '_alt'] = 'Category Icon';
                    $item[$fieldName . '_orig_src'] = $baseImage;
                }
                $item[$fieldName . '_link'] = $this->_backendUrl->getUrl(
                    "magelearn_categoryfaq/category/edit",
                    ['category_id' => $item['category_id']]
                );
            }
        }

        return $dataSource;
    }
}
