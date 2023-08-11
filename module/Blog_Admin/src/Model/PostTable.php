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
        $select->order('category_name ASC');
        $select->where(['p.post_status' => 1]);

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

    public function savePost()
    {

    }
}
?>