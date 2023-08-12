<?php

namespace Blog_Admin\Form;

use Zend\Filter\StringTrim;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\Validator\NotEmpty;
use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;
use Zend\Validator\StringLength;

class PostForm extends Form
{
    private $action;
    public function __construct($action = "add")
    {
        $this->action = $action;
        parent::__construct();
        $this->setAttributes([
            'class' => 'form-horizontal',
            'id' => 'post_form',
            'enctype' => 'multipart/form-data'
        ]);
        //them element
        $this->setElement();
        //Them validator
        $this->validatorForm();
    }

    private function setElement()
    {
        //input hidden
        $post_id = new Element\Text('post_id');
        $post_id->setAttributes([
            'type' => 'hidden'
        ]);
        $this->add($post_id);
        //input text
        $post_title = new Element\Textarea('post_title');
        $post_title->setLabel('Tiêu đề: ')
            ->setAttributes([
                'class' => 'form-label',
                'id' => 'post_title'
            ]);
        $post_title->setAttributes([
            'class' => 'form-control',
            'id' => 'post_title',
            'placeholder' => 'Nhập tiêu đề'
        ]);
        $this->add($post_title);
        //select file
        $file = new Element\File('post_icon');
        $file->setLabel('Chọn ảnh: ')
            ->setAttribute('class', 'form-label');
        $file->setAttributes([
            'type' => 'file'
        ]);
        if ($this->action == "add") {
            $file->setAttributes([
                'required' => 'required'
            ]);
        }
        $this->add($file);
        //input describe
        $post_describe = new Element\Textarea('post_describe');
        $post_describe->setLabel('Mô tả ngắn: ')
            ->setAttributes([
                'class' => 'form-label',
                'id' => 'post_describe'
            ]);
        $post_describe->setAttributes([
            'class' => 'form-control',
            'row' => 3,
            'id' => 'post_describe',
            'placeholder' => 'Nhập mô tả'
        ]);
        $this->add($post_describe);
        //input author
        $post_author = new Element\Text('post_author');
        $post_author->setLabel('Bút danh: ')
            ->setAttributes([
                'class' => 'form-label',
                'id' => 'post_author'
            ]);
        $post_author->setAttributes([
            'class' => 'form-control',
            'id' => 'post_author',
            'placeholder' => 'Nhập bút danh'
        ]);
        $this->add($post_author);
        //input content
        $post_content = new Element\Textarea('post_content');
        $post_content->setLabel('Nội dung: ')
            ->setAttributes([
                'class' => 'form-label',
                'id' => 'post_author'
            ]);
        $post_content->setAttributes([
            'class' => 'form-control',
            'row' => 3,
            'id' => 'post_content'
        ]);
        $this->add($post_content);
        //select option
        $category_name = new Element\Select('category_name');
        $category_name->setLabel('Chọn chỉ mục: ')
            ->setLabelAttributes([
                'class' => 'control-label'
            ]);
        $category_name->setAttributes([
            'class' => 'form-control'
        ]);
        $this->add($category_name);

        //button submit
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'class' => 'btn btn-primary'
            ],
        ]);
    }

    //Validator
    private function validatorForm()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'post_title',
            'required' => true,
            'filter' => [
                ['post_title' => 'StringTrim']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => "Vui lòng nhập tên tiêu đề!!!"
                        ]
                    ]
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'max' => 500,
                        'messages' => [
                            StringLength::TOO_LONG => "Nội dung quá dài rồi bạn ơi!! Không quá %max% kí tự nhé :v"
                        ]
                    ]
                ]
            ]
        ]);

        if ($this->action == "add") {
            $inputFilter->add([
                'name' => 'post_icon',
                'required' => true,
                'filter' => [
                    ['post_icon' => 'StringTrim']
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => "Vui lòng tải ảnh lên!!!"
                            ]
                        ]
                    ],
                    [
                        'name' => 'filesize',
                        'options' => [
                            'max' => 2 * 1024 * 1024,
                            'messages' => [
                                Size::TOO_BIG => 'File quá nhỏ, dung lượng ít nhất %min%'
                            ]
                        ]
                    ],
                    [
                        'name' => 'fileMimeType',
                        'options' => [
                            'mimetype' => 'image/png, image/jpeg, image/jpg, image/gif',
                            'messages' => [
                                MimeType::FALSE_TYPE => 'Kiểu file %type% không được phép chọn',
                                MimeType::NOT_DETECTED => 'MimeType không xác định',
                                MimeType::NOT_DETECTED => 'MimeType không thể đọc'
                            ]
                        ]
                    ]
                ]
            ]);
        }

        $inputFilter->add([
            'name' => 'post_content',
            'required' => true,
            'filter' => [
                ['post_content' => 'StringTrim']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => "Vui lòng nhập nội dung bài viết!!!"
                        ]
                    ]
                ]
            ]
        ]);
    }
}

?>