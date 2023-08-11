<?php

namespace Blog_Admin\Controller;

use Blog_Admin\Model\PostTable;
use Blog_Admin\Model\CategoryTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog_Admin\Form\PostForm;

class Blog_AdminController extends AbstractActionController
{
    private $table;

    public function __construct(PostTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $this->layout()->setTemplate('layout/admin');
        return new ViewModel([
            'data' => $this->table->fetchAll(),
        ]);

        //test data
        // $results = $this->table->fetchAll();
        // foreach ($results as $element) {
        //     echo '<pre>';
        //     print_r($element);
        //     echo '</pre>';
        // }

        // return false;
    }

    public function addAction()
    {
        $form = new PostForm();
        $this->layout()->setTemplate('layout/admin');
        $category_name = $this->table->getName();
        //Lấy dữ liệu từ bảng khác và gán nó vào options
        $listName = [];
        foreach ($category_name as $name) {
            $listName[$name->category_id] = $name->category_name;
        }
        $form->get('category_id')->setValueOptions($listName);

        //validate
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['form' => $form]);
        }
        $data = $request->getPost()->toArray();
        $file = $request->getFiles()->toArray();
        $data = array_merge_recursive($data, $file);
        $form->setData($data);

        if (!$form->isValid()) {
            return new ViewModel(['form' => $form]);
        }
        $data = $form->getData();
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        return false;
        // return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}

?>