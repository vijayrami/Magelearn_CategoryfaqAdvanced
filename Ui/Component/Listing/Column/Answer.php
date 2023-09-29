<?php

namespace Magelearn\Categoryfaq\Ui\Component\Listing\Column;

use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;

class Answer extends Column
{
    /**
     * Answer constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory    
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) { 
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if(isset($item['answer']) && !empty($item['answer'])) {
                    $item[$this->getData('answer')] = html_entity_decode(nl2br($item['answer']));
                }
            }
        }
        return $dataSource;
    }
}