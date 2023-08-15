<?php

namespace Blog\Controller;

// use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Blog\Form\BlogForm;
use Blog\Model\Post;
use Blog\Model\PostTable;
use Blog\Model\Category;
use Blog\Model\CategoryTable;
use Zend\File\Transfer\Adapter\Http;
use Zend\Filter\File\Rename;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    // Add this property:
    private $postTable;
    private $categoryTable;

    // Add this constructor:
    public function __construct(PostTable $postTable, CategoryTable $categoryTable)
    {
        $this->postTable = $postTable;
        $this->categoryTable = $categoryTable;
    }

    public function indexAction()
    {
        //Lay ra toan bo du lieu
        // return new ViewModel([
        //     'post' => $this->table->fetchAll(),
        // ]);

        //Lay cac bai viet khong bi an
        return new ViewModel([
            'post' => $this->postTable->select(),
        ]);
    }


    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('blog', ['controller' => 'BlogController', 'action' => 'index']);
        }
        try {
            $post = $this->postTable->findPost($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('post', ['action' => 'index']);
        }
        $post1 = $this->categoryTable->selectCategory($id);
        $name = $this->categoryTable->selectCategory($post1->parent_id);
        $blog = $this->postTable->findPost($id);
        $category = $this->postTable->findPost($id);
        $name_parent = $this->categoryTable->selectCategory($category->category_name);
        $data = new ViewModel([
            'blog' => $this->postTable->select(),
            'post_title' => $blog->post_title,
            'post_create_date' => $blog->post_create_date,
            'post_view' => $blog->post_view,
            'post_author' => $blog->post_author,
            'post_content' => $blog->post_content,
            'category_name' => $blog->category_name,
            'name' => $name->category_name,
            'parent' => $name_parent->category_name,
        ]);

        $this->postTable->addViewPost($post);
        return $data;
    }

    public function filterAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $data = $this->postTable->filterForPost($id);
        return new ViewModel([
            'postFilter' => $this->postTable->filterForPost($id),
            'post' => $this->postTable->select(),
        ]);
    }


}

?>