<?php

namespace Blog_Admin\Controller;

use Blog_Admin\Model\PostTable;
use Blog_Admin\Model\CategoryTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog_Admin\Form\PostForm;
use Zend\Filter\File\Rename;
use Blog_Admin\Model\Post;

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
    }

    public function addAction()
    {
        $form = new PostForm('add');
        $this->layout()->setTemplate('layout/admin');
        $category_name = $this->table->getName();
        //Lấy dữ liệu từ bảng khác và gán nó vào options
        $listName = [];
        foreach ($category_name as $name) {
            $listName[$name->category_id] = $name->category_name;
        }
        $form->get('category_name')->setValueOptions($listName);
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
        //Save img
        $newName = date('Y-m-d-h-i-s') . '-' . $file['post_icon']['name'];
        $fileFilter = new Rename([
            'target' => FILES_PATH . 'blog/' . $newName,
            'overwrite' => true
        ]);
        $fileFilter->filter($file['post_icon']);
        //post_create_update
        $data['post_create_date'] = date("Y-m-d");
        $data['post_last_modify'] = date("Y-m-d");
        $data['post_status'] = 1;
        $data['post_icon'] = $newName;
        $data['parent_id'] = 1;
        $post = new Post();
        $post->exchangeArray($data);
        $this->table->savePost($post);

        return $this->redirect()->toRoute('blog_admin');
    }

    public function editAction()
    {
        $form = new PostForm('edit');
        $this->layout()->setTemplate('layout/admin');
        $id = (int) $this->params()->fromRoute('id', 0);
        $category_name = $this->table->getName();
        //Lấy dữ liệu từ bảng khác và gán nó vào options
        $listName = [];
        foreach ($category_name as $name) {
            $listName[$name->category_id] = $name->category_name;
        }
        $form->get('category_name')->setValueOptions($listName);

        if (0 === $id) {
            return $this->redirect()->toRoute('blog_admin', ['action' => 'index']);
        }
        try {
            $post = $this->table->findPost($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('blog_admin', ['action' => 'index']);
        }

        $form->bind($post);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['post_id' => $id, 'form' => $form]);
        }
        $data = $request->getPost()->toArray();
        $file = $request->getFiles()->toArray();
        if ($file['post_icon']['error'] <= 0) {
            $data = array_merge_recursive($data, $file);
            $newName = date('Y-m-d-h-i-s') . '-' . $file['post_icon']['name'];
            $fileFilter = new Rename([
                'target' => FILES_PATH . 'blog/' . $newName,
                'overwrite' => true
            ]);
            $fileFilter->filter($file['post_icon']);
            $data['post_icon'] = $newName;
        } else {
            $data['post_icon'] = $form->get('post_icon')->getValue();
        }
        $form->setData($data);
        if (!$form->isValid()) {
            return new ViewModel(['form' => $form]);
        }
        $data = $form->getData();

        $data->post_last_modify = date("Y-m-d");
        $this->table->savePost($post);

        return $this->redirect()->toRoute('blog_admin', ['action' => 'index']);
    }

    public function hideAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('blog_admin', ['action' => 'add']);
        }
        $post = $this->table->findPost($id);

        $this->table->hidePost($post);

        // Redirect to album list
        return $this->redirect()->toRoute('blog_admin', ['action' => 'index']);
    }

    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('blog_admin', ['action' => 'add']);
        }
        $post = $this->table->findPost($id);

        $this->table->showPost($post);

        // Redirect to album list
        return $this->redirect()->toRoute('blog_admin', ['action' => 'index']);
    }

}

?>