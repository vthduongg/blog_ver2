<?php
namespace Blog\Model;

class Category
{
    public $category_id;
    public $category_name;
    public $category_describe;
    public $parent_id;

    public function exchangeArray(array $data)
    {
        $this->category_id = !empty($data['category_id']) ? $data['category_id'] : null;
        $this->category_name = !empty($data['category_name']) ? $data['category_name'] : null;
        $this->category_describe = !empty($data['category_describe']) ? $data['category_describe'] : null;
        $this->parent_id = !empty($data['parent_id']) ? $data['parent_id'] : null;
    }
}

?>