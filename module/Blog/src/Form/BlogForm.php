<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class BlogForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name = null);

        $this->add([
            'name' => 'post_id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'post_title',
            'type' => 'text',
            'option' => [
                'label' => '',
            ],
        ]);

        $this->add([
            'name' => 'post_content',
            'type' => 'text',
            'option' => [
                'label' => '',
            ],
        ]);

        $this->add(
            [
                'type' => 'Zend\Form\Element\Select',
                'name' => 'category_id',
                'options' => [
                    'disable_inarray_validator' => true,
                    'label' => 'Chủ đề',
                ]
            ]
        );
    }

}

?>