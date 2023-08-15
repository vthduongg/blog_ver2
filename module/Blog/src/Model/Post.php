<?php
namespace Blog\Model;

class Post
{
    public $post_id;
    public $post_title;
    public $post_icon;
    public $post_describe;
    public $post_create_date;
    public $post_author;
    public $post_view;
    public $post_content;
    public $post_last_modify;
    public $category_name;
    public $post_status;

    public function exchangeArray(array $data)
    {
        $this->post_id = !empty($data['post_id']) ? $data['post_id'] : null;
        $this->post_title = !empty($data['post_title']) ? $data['post_title'] : null;
        $this->post_icon = !empty($data['post_icon']) ? $data['post_icon'] : null;
        $this->post_describe = !empty($data['post_describe']) ? $data['post_describe'] : null;
        $this->post_create_date = !empty($data['post_create_date']) ? $data['post_create_date'] : null;
        $this->post_author = !empty($data['post_author']) ? $data['post_author'] : null;
        $this->post_view = !empty($data['post_view']) ? $data['post_view'] : null;
        $this->post_content = !empty($data['post_content']) ? $data['post_content'] : null;
        $this->post_last_modify = !empty($data['post_last_modify']) ? $data['post_last_modify'] : null;
        $this->category_name = !empty($data['category_name']) ? $data['category_name'] : null;
        $this->post_status = !empty($data['post_status']) ? $data['post_status'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'post_id' => $this->post_id,
            'post_title' => $this->post_title,
            'post_icon' => $this->post_icon,
            'post_describe' => $this->post_describe,
            'post_create_date' => $this->post_create_date,
            'post_author' => $this->post_author,
            'post_view' => $this->post_view,
            'post_content' => $this->post_content,
            'post_last_modify' => $this->post_last_modify,
            'category_name' => $this->category_name,
            'post_status' => $this->post_status
        ];
    }
}

?>