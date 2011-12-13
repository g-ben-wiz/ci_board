<?php
class Post_model extends CI_Model {

    var $category_id    = NULL;
    var $author_user_id = NULL;
    var $parent_post_id = NULL;

    var $post_date = "";
    var $title     = "";
    var $text      = "";

    var $image     = NULL;
    var $imgtype   = NULL;
    var $thumbnail = NULL;


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_searchresults($term, $pagenum, $num_results) {
        $sql = "SELECT id, title, post_date FROM posts 
                WHERE to_tsvector('english', coalesce(title,'') || ' ' || coalesce(text,'')) @@ to_tsquery(?) 
                ORDER BY post_date DESC LIMIT ? OFFSET ?";

        $limit = $pagenum * $num_results;

        $offset = ($pagenum - 1) * $num_results;

        $q = $this->db->query($sql, array($term, $limit, $offset));
        
        return $q;
    }

    public function insert_post($data) {
        $imgedited = isset($data['image']);
        
        if ($imgedited) {
            //http://www.php.net/manual/en/function.pg-escape-bytea.php#89036
            $data['image']     = str_replace(array("\\\\", "''"), array("\\", "'"), pg_escape_bytea($data['image'])); 
            $data['thumbnail'] = str_replace(array("\\\\", "''"), array("\\", "'"), pg_escape_bytea($data['thumbnail'])); 
        }

        $this->db->set($data);
        $this->db->insert('posts');
    }

    public function get_post($id) {
        $sql = "SELECT * FROM posts WHERE id = ?";

        $q = $this->db->query($sql, array($id));
        return $q->row();
    }

    public function get_replies($parentid) {
        $sql = "SELECT * FROM posts WHERE parent_post_id = ?";
        
        $q = $this->db->query($sql, array($parentid));
        return $q->result();
    }

    public function fetch_image($postid) {
        $sql = "SELECT imgtype, image, thumbnail FROM posts WHERE id = ?";
        $q = $this->db->query($sql, array($postid));

        $imagedata    = NULL;
        $thumbnail    = NULL;
        $content_type = NULL;

        $res = $q->row();

        if (!empty($res)) {
            $content_type = $res->imgtype;
            $imagedata    = $res->image;
            $thumbnail    = $res->thumbnail;
        }

        $d = array();
        $d['content_type'] = $content_type;
        $d['imagedata']    = $imagedata;
        $d['thumbnail']    = $thumbnail;

        return $d;

    }

    public function get_posts_with_category($cat_id) {
        $num_results = 500; 
        
        $sql = "SELECT * FROM posts
                WHERE category_id = ? AND parent_post_id = 0
                ORDER BY post_date DESC LIMIT ?";

        $q = $this->db->query($sql, array($cat_id, $num_results));

        return $q;
    }

    public function edit_post($id, $input) {
        $imgedited = isset($input['image']);
        
        if ($imgedited) {
            //http://www.php.net/manual/en/function.pg-escape-bytea.php#89036
            $input['image']     = str_replace(array("\\\\", "''"), array("\\", "'"), pg_escape_bytea($input['image'])); 
            $input['thumbnail'] = str_replace(array("\\\\", "''"), array("\\", "'"), pg_escape_bytea($input['thumbnail'])); 
        }

        $this->db->where('id', $id);
        $this->db->set($input);
        $this->db->update('posts'); 

    }

    public function remove_post($id) {
        $sql = "DELETE FROM posts WHERE id = ?";
        $this->db->query($sql, array($id));
    }
}

