<?php
namespace Magelearn\Categoryfaq\Block\Adminhtml\Question;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Magelearn\Categoryfaq\Model\Question;

/**
 * Class Edit
 * @package Magelearn\Categoryfaq\Block\Adminhtml\Question
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;
    
    /**
     * constructor
     *
     * @param Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        Context $context,
        array $data = []
        ) {
            $this->coreRegistry = $coreRegistry;
            
            parent::__construct($context, $data);
    }
    /**
     * Initialize Question edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'question_id';
        $this->_blockGroup = 'Magelearn_Categoryfaq';
        $this->_controller = 'adminhtml_question';
        parent::_construct();
        //$this->buttonList->remove('delete');
        $question = $this->coreRegistry->registry('magelearn_categoryfaq_question');
        //$this->buttonList->remove('reset');
        $this->buttonList->update('save', 'label', __('Save Question'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
            );
        if ($question->getId() && $this->_request->getParam('question_id')) {
            $this->buttonList->update('delete', 'label', __('Delete Question'));
        }
    }
    
    /**
     * Retrieve text for header element depending on loaded Question
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var Question $question */
        $question = $this->coreRegistry->registry('magelearn_categoryfaq_question');
        
        if ($question->getId()) {
            return __("Edit Question '%1'", $this->escapeHtml($question->getTitle()));
        }
        
        return __('New Question');
    }
    
    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        /** @var Question $question */
        $question = $this->coreRegistry->registry('magelearn_categoryfaq_question');
        if ($question->getId()) {

            $ar = ['id' => $question->getId()];
            
            return $this->getUrl('*/*/save', $ar);
        }

        return parent::getFormActionUrl();
    }
}