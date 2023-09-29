<?php

namespace Magelearn\Categoryfaq\Model\Question\Status;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Grid display options.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Disabled')],
            ['value' => '1', 'label' => __('Enabled')]
        ];
    }
}