<?php
require ('ci_board_controller.php');

class Post extends Ci_board_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        redirect('category', 'refresh');
    }

    //create
    private function create_post($config) {
        $imgdata = $this->input->post('post_image');

        if (isset($imgdata)) {
            $this->upload->do_upload("post_image");
            $upload_data = $this->upload->data();

            $imgpath = $upload_data['full_path'];

            if (!$this->is_legit_image($imgpath, $upload_data['file_ext'])) {
                $this->load->view('post/improper_image', $this->common_data());            
                return;
            }

            $config['image']     = file_get_contents($imgpath);
            $config['imgtype']   = $upload_data['file_type'];
            $config['thumbnail'] = $this->image_to_thumb($imgpath);
        }

        $this->post_model->insert_post($config);

        //optionally rm the old files
    }

    public function add($cat_id) {
        $data = $this->common_data();

        //should you even be here
        $r = $this->session->userdata('rand_token');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        $user_id = $this->session->userdata('id');            

        if (!($user_id)) {
            redirect ('user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $this->form_validation->set_rules('post_title', 'Title', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('post_text',  'Text',  'trim|required|max_length[65535]');

        $data['category_id']   = $cat_id;
        $data['category_name'] = $this->category_model->get_name_by_id($cat_id);

        if ($this->form_validation->run() == FALSE || !is_uploaded_file($_FILES['post_image']['tmp_name'])) {
            $this->load->view('post/add', $data);
        }
        else {
            $creation_config = array();
            $creation_config['category_id']    = $cat_id;
            $creation_config['author_user_id'] = $user_id;
            $creation_config['parent_post_id'] = 0;
            $creation_config['post_date']      = date('Y-m-d H:i:s');
            $creation_config['title']          = $this->input->post('post_title');
            $creation_config['text']           = $this->input->post('post_text');
            $creation_config['image']          = NULL;
            $creation_config['imgtype']        = NULL;
            $creation_config['thumbnail']      = NULL;

            $this->create_post($creation_config);

            redirect('category/view/'.$cat_id);
        }

    }

    public function addreply($parent_id) {
        $data = $this->common_data();

        $r = $this->session->userdata('rand_token');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        $user_id = $this->session->userdata('id');
        
        if ($user_id == FALSE) {
            redirect ('user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $this->form_validation->set_rules('post_text', 'Text', 'trim|required|max_length[65535]');

        $parent = $this->post_model->get_post($parent_id);

        $parent_title = $parent->title;
        $parent_cat   = $parent->category_id;
        $grandparent  = $parent->parent_post_id;

        if ($grandparent != 0) {
            redirect('post/view/'.$grandparent, 'refresh');
        }

        $data['parent']      = $parent;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('post/addreply', $data);
        }
        else {
            $creation_config = array();
            $creation_config['category_id']    = $parent_cat;
            $creation_config['author_user_id'] = $user_id;
            $creation_config['parent_post_id'] = $parent_id;
            $creation_config['post_date']      = date('Y-m-d H:i:s');
            $creation_config['title']          = "Re: ".$parent_title;
            $creation_config['text']           = $this->input->post('post_text');
            $creation_config['image']          = NULL;
            $creation_config['imgtype']        = NULL;
            $creation_config['thumbnail']      = NULL;

            $this->create_post($creation_config);

            redirect('post/view/'.$parent_id);
        }
    }

    /*
    public function improper_image() {
        $this->load->view('post/improper_image', $this->common_data());            
    }
    */

    protected function user_text($user_id) {
        //text for banning or editing user $user_id if the viewer has permission
        //for navigation, saves typing
        $user = $this->user_model->get_user($user_id);

        $str = "";
        $str .= "<p><a href = '" .site_url("user/view/".$user_id) . "'>";
        $str .= $user->name;
        $str .= "</a>";

        $sess_id = $this->session->userdata('id');

        if ($sess_id == $user_id || $this->check_privilege($sess_id) == 2) {
            $str .= " <a href = '" .site_url("user/edit/".$user_id) . "'>";
            $str .= "edit";
            $str .= "</a> ";
        }

        if ($this->check_privilege($sess_id) == 2) {
            $str .= " <a href = '" .site_url("user/ban/".$user_id) . "'>";
            $str .= "ban";
            $str .= "</a> ";
        }

        $str .= "</p>";

        return $str;

    }

    //read        
    public function view($id) {
        $data = $this->common_data();

        $board_post = $this->post_model->get_post($id);

        if (empty($board_post)) {
            show_404();
        }
        else {
            $data['id']   = $id;
            $data['post'] = $board_post;

            $data['admin_text'] = $this->admin_text($id);

            //author
            $author_id = $board_post->author_user_id;
            $author    = $this->user_model->get_user($author_id);

            $data['author']  = $author->name;
            $data['op_text'] = $this->user_text($author_id);

            //replies
            $data['replies']           = $this->post_model->get_replies($id);
            $data['reply_admin_text']  = array();
            $data['reply_author_text'] = array();

            foreach ($data['replies'] as $reply) {
                array_push( $data['reply_admin_text'],  $this->admin_text($reply->id));
                array_push( $data['reply_author_text'], $this->user_text($reply->author_user_id));
            }

            $this->load->view('post/detail', $data);
        }
    }

    public function search($term = NULL, $pagenum = 1) {
        $data = $this->common_data();
        $r = $this->session->userdata('rand_token');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        if (isset($term)) {
            //show results for term
            $num_results = 10;

            $query = $this->post_model->get_searchresults($term, $pagenum, $num_results);

            $next_results = $this->post_model->get_searchresults($term, $pagenum + 1, $num_results);
            
            if ($pagenum <= 1) {
                $prev_results = ""; 
            }
            else {
                $prev_results = $this->post_model->get_searchresults($term, $pagenum - 1, $num_results);
            }

            $data['term']          = $term;
            $data['pagenum']       = $pagenum;
            $data['page_results']  = $num_results;
            $data['total_results'] = $query->num_rows();
            $data['results']       = $query->result();

            $data['is_next'] = ($next_results->num_rows() > 0);
            $data['is_prev'] = !empty($prev_results) && ($pagenum > 0);

            $this->load->view('post/searchresults', $data);
        }

        else {
            //load form to get term
            $this->form_validation->set_rules('term', 'Search Term', 'trim|required|max_length[255]');
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('post/search', $data);
            }
            else {
                $searchterm = $this->input->post('term');
                redirect ('post/search/'.$searchterm, 'refresh');
            }
        }
    }

    public function showimage($post_id) {
        $data = $this->post_model->fetch_image($post_id);

        if (isset($data['imagedata']) && isset($data['content_type'])) {
            header("Content-type: ".$data['content_type']);
            echo pg_unescape_bytea($data['imagedata']);
        }
    }

    public function showthumb($post_id) {
        $data = $this->post_model->fetch_image($post_id);

        if (isset($data['thumbnail']) && isset($data['content_type'])) {
            header("Content-type: ".$data['content_type']);
            echo pg_unescape_bytea($data['thumbnail']);
        }
    }


    private function is_legit_image($filename, $extension) {
        //http://www.garykessler.net/library/file_sigs.html
        //http://codeaid.net/php/check-if-the-file-is-a-png-image-file-by-reading-its-signature

        $signatures = array(

            ".bmp"  => array(0x42, 0x4D),
            ".png"  => array(0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A),
            ".jpg"  => array(0xFF, 0xD8, 0xFF),
            ".jpeg" => array(0xFF, 0xD8, 0xFF),

            "gif89a"  => array(0x47, 0x49, 0x46, 0x38, 0x39, 0x61),
            "gif87a"  => array(0x47, 0x49, 0x46, 0x38, 0x37, 0x61)
        );

        $ext = strtolower($extension);

        //there are two different kinds of 'gif'
        //so handle it specially
        if ($ext == ".gif") {
            $fp = fopen($filename, 'r');
            $fp_sig = fread($fp, count($signatures["gif89a"]));
            fclose($fp);

            $fp_sig_chars = preg_split('//', $fp_sig, -1, PREG_SPLIT_NO_EMPTY);
            $fp_sig_chars = array_map('ord', $fp_sig_chars);

            $legit = (count(array_diff($signatures["gif89a"], $fp_sig_chars)) === 0);

            $legit = $legit || (count(array_diff($signatures["gif87a"], $fp_sig_chars)) === 0);

            return $legit;
        }

        $fp     = fopen($filename, 'r');
        $fp_sig = fread($fp, count($signatures[$ext]));
        fclose($fp);

        $fp_sig_chars = preg_split('//', $fp_sig, -1, PREG_SPLIT_NO_EMPTY);
        $fp_sig_chars = array_map('ord', $fp_sig_chars);

        return (count(array_diff($signatures[$ext], $fp_sig_chars)) === 0);

    }

    //update
    public function edit($post_id) {
        $data    = $this->common_data();
        $r       = $this->session->userdata('rand_token');
        $user_id = $this->session->userdata('id');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        if ($user_id == FALSE) {
            redirect('/user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $post_author_id = -1;
        $post = $this->post_model->get_post($post_id);

        $post_author_id = $post->author_user_id;

        $can_mess_with_post = ($this->check_privilege($user_id) == 2) || ($user_id == $post_author_id);

        if ($can_mess_with_post) {

            $this->form_validation->set_rules('post_title', 'Title', 'trim|max_length[255]');
            $this->form_validation->set_rules('post_text',  'Text', 'trim|max_length[65535]');

            $data['id'] = $post_id;

            if (empty($post)) {
                show_404();
                return;
            }

            $data['post'] = $post;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('post/edit', $data);
            }
            else {
                $input = array();

                if (isset($_POST['post_title'])) {
                    $input['title'] = $_POST['post_title'];
                }

                if (isset($_POST['post_text'])) {
                    $input['text'] = $_POST['post_text'];
                }

                if ($_FILES && $_FILES['post_image']['tmp_name']) {
                    $this->upload->do_upload("post_image");
                    $upload_data = $this->upload->data();

                    $imgpath = $upload_data['full_path'];
                    $imgtype = $upload_data['file_type'];

                    $input['imgtype'] = $upload_data['file_type'];

                    if (!$this->is_legit_image($imgpath, $upload_data['file_ext'])) {
                        $this->load->view('post/improper_image', $this->common_data());            
                        return;
                    }

                    $input['image']   = file_get_contents($imgpath);

                }

                if (isset($imgpath)) {
                    $input['thumbnail'] = $this->image_to_thumb($imgpath);
                }

                $this->post_model->edit_post($post_id, $input);
                $this->load->view('post/edit', $data);
            }
        }
        else {
            redirect('post/view/'.$post_id, 'refresh');
        }
    }

    private function image_to_thumb($src_filename) {
        //http://www.php.net/manual/en/function.imagecopyresampled.php#104028
        $new_width  = 210.0;
        $new_height = 210.0;
        
        $dst_filename = "/tmp/imgthumb";
        
        if(!list($old_width, $old_height) = getimagesize($src_filename)) return "";

        $type = strtolower(substr(strrchr($src_filename,"."), 1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($src_filename); break;
            case 'gif': $img = imagecreatefromgif($src_filename);  break;
            case 'jpg': $img = imagecreatefromjpeg($src_filename); break;
            case 'png': $img = imagecreatefrompng($src_filename);  break;
            default : return "";
        }

        if($old_width < $new_width and $old_height < $new_height) {
            $new_width  = $old_width;
            $new_height = $old_height;
        }

        $scale      = min($new_width / $old_width, $new_height / $old_height);
        $new_width  = $old_width  * $scale;
        $new_height = $old_height * $scale;

        $new = imagecreatetruecolor($new_width, $new_height);

        if($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

        switch($type){
            case 'bmp': imagewbmp($new, $dst_filename); break;
            case 'gif': imagegif($new, $dst_filename);  break;
            case 'jpg': imagejpeg($new, $dst_filename); break;
            case 'png': imagepng($new, $dst_filename);  break;
        }

        $img_bits_str = file_get_contents($dst_filename);

        return $img_bits_str;
    }

    //delete
    public function remove($post_id) {
        $data = $this->common_data($r);
        $r = $this->session->userdata('rand_token');

        $user_id = $this->session->userdata('id');

        if ($user_id == FALSE) {
            redirect('user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        /*
        $post_author_id = -1;
        $post           = $this->post_model->get_post($post_id);
        $post_author_id = $post->author_user_id;
        */

        $can_delete = ($this->check_privilege($user_id) == 2);

        if ($can_delete === true) {
            $this->post_model->remove_post($post_id);
        }

        redirect('category', 'refresh');
    }
}

