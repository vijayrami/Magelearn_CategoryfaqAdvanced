<?php

namespace Magelearn\Categoryfaq\Block\Adminhtml\Question\Edit;

use Exception;
use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Cms\Model\Wysiwyg\Config;
use Magelearn\Categoryfaq\Block\Adminhtml\Question\Edit\Renderer\Category;

/**
 * Class Form
 * @package Magelearn\Categoryfaq\Block\Adminhtml\Question\Edit
 */
class Form extends Generic
{
    /**
     * Wysiwyg config
     *
     * @var Config
     */
    public $wysiwygConfig;
    
    /**
     * Question constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
        ) {
            $this->wysiwygConfig = $wysiwygConfig;
            
            parent::__construct($context, $registry, $formFactory, $data);
    }
    /**
     * @inheritdoc
     */
    protected function _prepareForm()
    {
        $question = $this->_coreRegistry->registry('magelearn_categoryfaq_question');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]
        ]);
        
        $form->setHtmlIdPrefix('question_');
        $form->setFieldNameSuffix('question');
        
        if ($question->getQuestionId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Question Data'), 'class' => 'fieldset-wide']
                );
            $fieldset->addField('question_id', 'hidden', ['name' => 'question_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add New Question'), 'class' => 'fieldset-wide']
                );
        }
        
        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'options' => [0=>__('Disabled'), 1=>__('Enabled')],
            'id' => 'status',
            'title' => __('Status'),
            'required' => false,
        ]);
        if (!$question->hasData('status')) {
            $question->setStatus(1);
        }
        
        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'id' => 'title',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
            );
        
        $fieldset->addField(
            'answer',
            'editor',
            [
                'name' => 'answer',
                'label' => __('Answer'),
                'id' => 'answer',
                'title' => __('Answer'),
                'config' => $this->wysiwygConfig->getConfig([
                    'add_variables' => false,
                    'add_widgets' => true,
                    'add_directives' => true
                ])
            ]
            );
        
        $fieldset->addField('categories_ids', Category::class, [
            'name' => 'categories_ids',
            'label' => __('Categories'),
            'title' => __('Categories'),
        ]);
        if (!$question->getCategoriesIds()) {
            $question->setCategoriesIds($question->getCategoryIds());
        }
        
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'id' => 'sort_order',
                'title' => __('Sort Order'),
                'class' => 'required-entry',
                'required' => true,
            ]
            );

        $form->addValues($question->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
