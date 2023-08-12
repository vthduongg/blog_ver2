<?php

namespace Blog\Model;

use RuntimeException;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGatewayInterface;

class PostTable
{
    private $tableGateway;
    //Phuong thuc de truyen du lieu vao tablegateway
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        //tro den TableGateWay de lay du lieu tu TableGateWay
        return $this->tableGateway->select();

        // lay ra cac cot theo nhu cau muon hien thi
        // $adapter = $this->tableGateway->getAdapter();
        // $sql = new Sql($adapter);
        // $select->from('p'=>'post');

    }

    public function select()
    {
        // lay ra cac cot theo nhu cau muon hien thi
        return $this->tableGateway->select(['post_status' => 1]);
    }

    public function selectCategory($id)
    {
        $category_id = $id;
        $rowset = $this->tableGateway->select(['category_id' => $id]);
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

    public function findPost($id)
    {
        $post_id = (int) $id;
        $rowset = $this->tableGateway->select(['post_id' => $id]);
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
    public function savePost(Post $post)
    {
        $data = [
            'post_id' => $post->post_id,
            'post_title' => $post->post_title,
            'post_icon' => $post->post_icon,
            'post_describe' => $post->post_describe,
            'post_create_date' => $post->post_create_date = date("Y-m-d"),
            'post_content' => $post->post_content,
            'post_last_modify' => $post->post_last_modify = date("Y-m-d"),
            'category_id' => $post->category_id,
            'post_status' => $post->post_status = 1
        ];

        $id = (int) $post->post_id;

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

    public function addViewPost(Post $post)
    {
        $data = [
            'post_view' => $post->post_view + 1,
        ];
        $id = (int) $post->post_id;

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