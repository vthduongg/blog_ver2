<?php

namespace Blog\Model;

use RuntimeException;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGatewayInterface;

class CategoryTable
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

    public function getParent()
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('category');
        $select->columns(['category_id', 'category_name', 'parent_id']);
        $selectString = $sql->buildSqlString($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        return $results;
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

}