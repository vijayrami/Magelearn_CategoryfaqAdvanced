<?php

namespace Magelearn\Categoryfaq\Block\Adminhtml\Question\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Button "Create Category" in "New Category" slide-out panel of a Question page
 * Class CreateCategory
 * @package Magelearn\Categoryfaq\Block\Adminhtml\Question\Edit\Button
 */
class CreateCategory implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Create Category'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 10
        ];
    }
}
