<?php
class Ci_board_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('category_model'); //for navigation
        $this->load->model('post_model');     //for posts & posts under category 
        $this->load->model('user_model');     //for user administration

        //config for image uploads
        $config['upload_path']   = '/tmp';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '2048';
        $config['max_width']     = '2048';
        $config['max_height']    = '2048';

        $this->load->library('upload', $config);
        $this->load->library('form_validation');
        $this->load->library('session');

        $this->load->helper('form');
        $this->load->helper('url');
    }

    protected function admin_text($post_id) {
        /* 
            return links for logged-in users who have permission to edit/delete a post
        */

        $str = "";

        if (!$this->session->userdata("id")) { //not logged in
            return $str;
        }

        $post_author_id = -1; //no user has an id of -1

        $post = $this->post_model->get_post($post_id);
        $post_author_id = $post->author_user_id;

        $user_id = $this->session->userdata('id');

        if ($user_id == $post_author_id || $this->check_privilege($user_id) == 2) {
            $str .= "<p><a href = " . site_url('post/edit/'.$post_id)  .">Edit</a></p> ";
        }

        if ($this->check_privilege($user_id) == 2) {
            $str .= "<p><a href = " . site_url('post/remove/'.$post_id)  .">Delete</a></p> ";
        }

        return $str;
    }

    protected function authentication_text() {
        /*
            log {in | out} option in nav menu
        */
        $user_id = $this->session->userdata('id');

        if ($user_id !== FALSE) {
            $str = "<li><a href = " . site_url('user/logout') . ">Logout</a></li> ";

            if ($this->check_privilege($user_id) == 2) {
                $str .= "<li><a href = " . site_url('category/add') . ">Add Category</a></li> ";
            }
        }
        else { 
            $str  = "<li><a href = " . site_url('user/register') . ">Register</a></li> ";
            $str .= "<li><a href = " . site_url('user/login') . ">Login</a></li> ";
            $str .= "<li><a href = " . site_url('user/recoverpassword') . ">Recover Password</a></li> ";
        }

        return $str;
    }

    protected function check_privilege($id) {
        $privilege = 0;
        $result = $this->user_model->get_user($id);

        if (!empty($result)) {
            $privilege = $result->permission_level;
        }

        return $privilege;
    }

    protected function common_data() {
        if ($this->session->userdata('rand_token') == FALSE) {
            $this->set_rand_token();
        }

        $token = $this->session->userdata('rand_token');

        return array(
            "nav_text"     => $this->nav_text(),
            "categories"   => $this->category_model->get_categories(),
            "hidden_field" => $this->hidden_field_text($token)
        );
    }

    protected function nav_text() {
        if ($this->session->userdata('id') == FALSE) {
            $user_id = 0;
        }
        else {
            $user_id = $this->session->userdata('id');
        }

        $str = "";
        $str = "<ul><li><a href = '" . site_url("homepage") . "'>Home</a></li></ul> ";

        $categories = $this->category_model->get_categories();

        if (!empty($categories)) {
            $str .= "<ul>";
            foreach ($categories as $cat) {
                $str .= "<li><a href = '" . site_url("category/view/".$cat->name) . "'>".$cat->name."</a></li> \n";

                if ($this->check_privilege($user_id) == 2) {
                    $str .= "<li><sup><a href = '" . site_url("category/edit/".$cat->id) . "'>Edit</a></sup></li> \n";
                    $str .= "<li><sup><a href = '" . site_url("category/remove/".$cat->id) . "'>Delete</a></sup></li> \n";
                }
            }
            $str .= "</ul> \n";

            $str .= "<ul><li>|</li></ul> ";
        }

        $str .= "<ul> \n";
        $str .= "<li><a href = " . site_url('post/search') . ">Search</a></li> ";
        $str .= "<li>|</li> ";
        $str .= $this->authentication_text();
        $str .= "</ul> \n";

        return $str;
    }

    protected function set_rand_token() {
        $this->session->set_userdata('rand_token', $this->rand_str(12));
    }

    protected function hidden_field_text($name) {
        $str = "<p><input type = 'text' class = 'redir_field' name = '" . $name . "'/></p> ";
        return $str;
    }

    protected function rand_str($count) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $randstr = "";

        for($i = 0; $i < $count; $i++){
            $pos = mt_rand(0, strlen($chars) - 1);
            $randstr .= $chars[$pos];
        }

        return $randstr; 
    }

}
