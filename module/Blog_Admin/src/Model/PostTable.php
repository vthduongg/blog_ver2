<?php

namespace Blog_Admin\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class PostTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $adapter = $this->tableGateway->getAdapter();

        $sql = new Sql($adapter);
        $select = $sql->select();
        //dat ten cho bang post la p
        // $select->from(['p' => 'post']);

        //Lay ra cac cot, dat lai ten cac cot
        // $select->columns(['mapost' => 'post_id', 'tenpost' => 'post_title', 'noidung' => 'post_content']);

        //Lien ket bang
        $select->from(['p' => 'post']);
        $select->join(['c' => 'category'], 'p.category_name = c.category_id', ['category_describe' => 'category_name'], $select::JOIN_LEFT);
        $select->order('post_create_date DESC');

        $selectString = $sql->buildSqlString($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        return $results;
    }

    public function getName()
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('category');
        $select->columns(['category_id', 'category_name']);
        $select->where->greaterThan('category.category_id', 5);
        $selectString = $sql->buildSqlString($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        return $results;
    }

    public function savePost(Post $post)
    {
        $data = [
            'post_id' => $post->post_id,
            'post_title' => $post->post_title,
            'post_icon' => $post->post_icon,
            'post_describe' => $post->post_describe,
            'post_create_date' => $post->post_create_date,
            'post_author' => $post->post_author,
            'post_content' => $post->post_content,
            'post_last_modify' => $post->post_last_modify,
            'category_name' => $post->category_name,
            'post_status' => $post->post_status
        ];
        $id = (int) $post->post_id;
        if ($id <= 0) {
            $this->tableGateway->insert($data);
        } elseif (!$this->findPost($id)) {
            throw new RuntimeException("Cập nhật thông tin không thành công!!!");
        } else {
            $this->tableGateway->update($data, ['post_id' => $id]);
        }
        return;
    }

    public function findPost($id)
    {
        $post_id = (int) $id;
        $rowset = $this->tableGateway->select(['post_id' => $id]);
        //Đưa nội dung thành 1 mảng duy nhất
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(
                sprintf(
                    'Could not find row with identifier %d',
                    $id
                )
            );
        }

        return $row;
    }

    public function hidePost(Post $post)
    {
        $id = (int) $post->post_id;
        $data = [
            'post_status' => $post->post_status = 0,
        ];
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->findPost($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(
                sprintf(
                    'Cannot update album with identifier %d; does not exist',
                    $id
                )
            );
        }

        $this->tableGateway->update($data, ['post_id' => $id]);
    }

    public function showPost(Post $post)
    {
        $id = (int) $post->post_id;
        $data = [
            'post_status' => $post->post_status = 1,
        ];
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->findPost($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(
                sprintf(
                    'Cannot update album with identifier %d; does not exist',
                    $id
                )
            );
        }

        $this->tableGateway->update($data, ['post_id' => $id]);
    }
}
?>