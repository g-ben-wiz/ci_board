<?php
class Category_model extends CI_Model {
    var $name = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_category($cat_name) {
        $sql = "INSERT INTO categories (name) VALUES (?);";
        $this->db->query($sql, array($cat_name)); 
    }

    public function get_name_by_id($cat_id) {
        $catname = "";
        $sql = "SELECT name FROM categories WHERE id = ?";

        $q = $this->db->query($sql, array($cat_id)); 
        $catname = $q->row()->name;

        return $catname;
    }

    public function get_categories() {
        $sql = "SELECT * FROM categories";

        $q = $this->db->query($sql);
        return $q->result();
    }

    public function get_category($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";

        $q = $this->db->query($sql, array($id));
        return $q->row();
    }

    public function get_category_by_name($name) {
        $sql = "SELECT * FROM categories WHERE name = ?";

        $q = $this->db->query($sql, array($name));
        return $q->row();
    }

    public function category_in_db($name) {
        $sql = "SELECT * FROM categories WHERE name = ?";
        $q = $this->db->query($sql, array($name));

        $res = $q->result();
        return !(empty($res));
    }

    public function edit_category($id, $data) {
        $sql = "UPDATE categories SET name = ? WHERE id = ?";
        $q = $this->db->query($sql, array($data['name'], $id));
    }

    public function remove_category($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        $q = $this->db->query($sql, array($id));

        //change posts of this category to have category id 0 (a default)
        $sql = "UPDATE posts SET category_id = 0 WHERE category_id = ?";
        $q = $this->db->query($sql, array($id));
    }

}

