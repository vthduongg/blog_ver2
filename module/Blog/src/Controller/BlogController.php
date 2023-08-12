<?php

namespace Blog\Controller;

// use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Blog\Form\BlogForm;
// use Blog\Model\Blog;
// use Blog\BlogForm;
use Blog\Model\Post;
use Blog\Model\PostTable;
use Zend\File\Transfer\Adapter\Http;
use Zend\Filter\File\Rename;
use Zend\Mvc\Controller\AbstractActionController;
// use Zend\Uri\Http;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        //Lay ra toan bo du lieu
        // return new ViewModel([
        //     'post' => $this->table->fetchAll(),
        // ]);

        //Lay cac bai viet khong bi an
        return new ViewModel([
            'post' => $this->table->select(),
        ]);
    }

    public function categoryAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('blog', ['controller' => 'BlogController', 'action' => 'index']);
        }
        try {
            $post = $this->table->selectCategory($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('blog', ['action' => 'index']);
        }
        // return new ViewModel(
        //     [
        //         'post' => $this->table->selectCategory($id),
        //     ]
        // );

        $post = $this->table->selectCategory($id);

        echo '<pre>';
        print_r($post);
        echo '</pre>';
        return false;
    }

    public function detailAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('blog', ['controller' => 'BlogController', 'action' => 'index']);
        }
        try {
            $post = $this->table->findPost($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('post', ['action' => 'index']);
        }
        $blog = $this->table->findPost($id);
        $data = new ViewModel([
            'blog' => $this->table->select(),
            'post_title' => $blog->post_title,
            'post_create_date' => $blog->post_create_date,
            'post_view' => $blog->post_view,
            'post_author' => $blog->post_author,
            'post_content' => $blog->post_content,
            'category_name' => $blog->category_name,
        ]);

        $this->table->addViewPost($post);
        return $data;
    }

}

?>