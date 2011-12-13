<?php
require ('ci_board_controller.php');
class Category extends Ci_board_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('category/index', $this->common_data());
    }

    //create
    public function add() {
        $data = $this->common_data();

        $r = $this->session->userdata('rand_token');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        if ($this->user_model->is_banned($this->session->userdata('id'))) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $user_id = $this->session->userdata('id');

        if ($this->check_privilege($user_id) != 2) {
            redirect('homepage', 'refresh'); 
            return;
        }

        $this->form_validation->set_rules('catname', 'Category Name', 'trim|required|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('category/addcategory', $data);
        }
        else {
            $catname   = $this->input->post('catname');
            $nametaken = $this->category_model->category_in_db( $catname );

            $data ['catname'] = $catname;

            if ($nametaken) {
                $this->load->view('category/nametaken', $data);
            }
            else {
                $this->category_model->insert_category($catname);
                $data['catname']    = $catname;
                $data['categories'] = $this->category_model->get_categories();
                $data['nav_text']   = $this->nav_text();
                $this->load->view('category/categorycreated', $data);
            }
        }
    }

    //read
    public function view($name = NULL) {
        $data = $this->common_data();

        if (!isset($name)) {
            redirect('/category', 'refresh');
        }

        $user_id = $this->session->userdata('id');

        if ($user_id !== FALSE) {

            if ($this->user_model->is_banned($this->session->userdata('id'))) {
            $data['user'] = $this->user_model->get_user($user_id);
                $this->load->view('user/youarebanned', $data);
                return;
            }
        }
        
        $category = $this->category_model->get_category_by_name($name);

        if (empty($category)) {
            redirect('/category', 'refresh');
        }

        $posts_query = $this->post_model->get_posts_with_category($category->id);    

        $data['category_id']   = $category->id;
        $data['category_name'] = $category->name;
        $data['page_results']  = 50;
        $data['total_results'] = $posts_query->num_rows();
        $data['cat_posts']     = $posts_query->result();

        $this->load->view('category/postsundercategory', $data);
    }

    //update
    public function edit($id) {
        $data = $this->common_data();

        $r = $this->session->userdata('rand_token');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        $user_id = $this->session->userdata('id');

        if ($user_id == FALSE) {
            redirect('/user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        if ($this->check_privilege($user_id) != 2) {
            redirect('homepage', 'refresh');
        }

        $category = $this->category_model->get_category($id);

        if (empty($category)) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('catname', 'Category Name', 'trim|required|max_length[255]');

        $data['catname'] = $catname = $category->name;
        $data['id'] = $id;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('category/edit', $data);
        }
        else {
            $data['name'] = $this->input->post('catname');

            $category_exists = $this->category_model->category_in_db($data['name']);

            if (! $category_exists) {
                $this->category_model->edit_category($id, $data);

                $data['categories'] = $this->category_model->get_categories();
                $data['nav_text'] = $this->nav_text();

                $this->load->view('category/edit', $data);
            }
            else {
                $this->load->view('category/nametaken', $data);
            }
        }
    }

    //delete
    public function remove($id) {
        $data = $this->common_data();

        $user_id = $this->session->userdata('id');

        if ($user_id == FALSE) {
            redirect('/user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        if ($this->check_privilege($user_id) != 2) {
            redirect('homepage', 'refresh');
            return;
        }

        $category = $this->category_model->get_category($id);

        if (empty($category)) {
            show_404();
            return;
        }

        $data['catname'] = $category->name;

        $this->category_model->remove_category($id);
        $data['categories'] = $this->category_model->get_categories();
        $data['nav_text']   = $this->nav_text();
        $this->load->view('category/deleted', $data);
    }

}

