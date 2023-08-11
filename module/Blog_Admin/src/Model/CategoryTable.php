<?php

namespace Blog_Admin\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class CategoryTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function category_name()
    {
        return $this->tableGateway->select('category_name');
    }
}
?>