<?php
require ('ci_board_controller.php');

class User extends Ci_board_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        show404();
    }

    //create
    public function register() {
        $data    = $this->common_data();
        $r       = $this->session->userdata('rand_token');
        $user_id = $this->session->userdata("id");

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[72]|matches[passconf]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/register', $data);
        }
        else {
            $username = $this->input->post('username');
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            $nametaken  = $this->user_model->user_in_db($username);
            $emailtaken = $this->user_model->email_in_db($email);

            if ($nametaken) {
                $this->load->view('user/nametaken', $data);
            }
            else if ($emailtaken) {
                $this->load->view('user/emailtaken', $data);
            }
            else {
                $this->user_model->insert_user($username, $email, $password);
                $this->load->view('user/registersuccess', $data);
            }
        }
    }

    //read
    public function view($id) {
        $data = $this->common_data();

        $data['user'] = $this->user_model->get_user($id);

        if (empty($data['user'])) {
            show_404();
            return;
        }

        $this->load->view("user/detail", $data);
    }

    public function login() {
        $data    = $this->common_data();
        $r       = $this->session->userdata('rand_token');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }
        $user_id = $this->session->userdata('id');

        if ($user_id !== FALSE) {
            $this->load->view('user/alreadylogged', $data);
            return;
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[72]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/login', $data);
        }
        else {

            $u = $this->input->post('username');
            $p = $this->input->post('password');

            $user_exists = $this->user_model->user_in_db($u);

            if (!$user_exists) {
                $this->load->view('user/userdne', $data);
                return;
            }

            $id = $this->user_model->get_id_by_credentials($u, $p);

            if ($id != -1) {

                if ($this->user_model->is_banned($id)) {
                    $data['user'] = $this->user_model->get_user($id);
                    $this->load->view('user/youarebanned', $data);
                    return;
                }

                $this->session->set_userdata('id', $id);

                redirect('homepage', 'refresh');
            }
            else {
                $this->load->view('user/loginfailure', $data);
            }
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('homepage', 'refresh');
    }

    //update
    public function edit($id) {
        $data       = $this->common_data();
        $data['id'] = $id;
        $user_id    = $this->session->userdata('id');

        if ($user_id == FALSE) {
            redirect('/user/login', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        if ($this->check_privilege($user_id) != 2 && $id != $user_id) {
            redirect('homepage', 'refresh');
            return;
        }

        $result = $this->user_model->get_user($id);

        if (empty($result)) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('name', 'Username', 'trim|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'trim|max_length[72]|matches[passconf]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');

        if ($this->check_privilege($user_id) == 2) {
            $data['priv_str'] = '
             <input type = "radio" name = "privilege" value = "-1" /> <label for = "privilege_banned">Banned</label>
             <input type = "radio" name = "privilege" value = "0" />  <label for = "privilege_guest">Guest</label>
             <input type = "radio" name = "privilege" value = "1" />  <label for = "privilege_user">User</label>
             <input type = "radio" name = "privilege" value = "2" />  <label for = "privilege_admin">Admin</label>';
            $this->form_validation->set_rules('permission', 'Permission Level', 'trim');
        }

        $username        = $result->name;
        $dbdata['name']  = $data['name']  = $result->name;
        $dbdata['email'] = $data['email'] = $result->email;

        if (isset($_POST['name'])) {
            $dbdata['name'] = $_POST['name'];
            if ($dbdata['name'] == $result->name) {
                unset($dbdata['name']);
            }
        }

        if (isset($_POST['email'])) {
            $dbdata['email'] = $data['email'] = $_POST['email'];
        }

        if (isset($_POST['password'])) {
            $dbdata['password'] = $_POST['password'];
        }

        if (isset($_POST['privilege'])) {
            $dbdata['permission_level'] = $_POST['privilege'];
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/edit', $data);
        }
        else {

            if (isset($dbdata['name'])) {
                $nameq = -1;
                $nameq = $this->user_model->get_id_by_name($dbdata['name']);

                if ($nameq != -1) {
                    $this->load->view('user/nametaken', $data);
                    return;
                }
            }

            $this->user_model->edit_user($id, $dbdata);
            $this->load->view('user/edit', $data);
        }
    }

    public function recoverpassword() {
        $data    = $this->common_data();
        $r       = $this->session->userdata('rand_token');
        $user_id = $this->session->userdata('id');

        if (isset($_POST[$r]) && strlen($_POST[$r]) > 0) {
            redirect('user/logout', 'refresh');
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[255]|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/recoverpassword', $data);
        }
        else {
            $address = $this->input->post('email');

            $user_id = $this->user_model->get_id_by_email($this->input->post('email'));

            if ( -1 == $user_id ) {
                $this->load->view('user/emaildne', $data);
                return;
            }

            $reqid = uniqid("", true);

            $this->user_model->insert_password_request($reqid, date('Y-m-d H:i:s'), $user_id);

            $sender  = "noreply@ci_board";
            $subject = "Password request";
            $message = "Follow this link to reset your password\nhttp://".$_SERVER['SERVER_NAME']."/resetpassword/".$reqid;

            mail($address, "Subject: $subject", $message, "From: $sender" );

            $this->load->view('user/sentmail', $data);
        }
    }
 
    public function resetpassword($request_id) {
        $user_id = $this->session->userdata('id');

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $password_request = $this->user_model->get_password_request($request_id);

        if (empty($password_request)) {
            show_404();
            return;
        }

        $request_date = $password_request->date;
        $user_id      = $password_request->user_id;

        $request_age = abs(strtotime (date('Y-m-d H:i:s') - $request_date ));

        if ( $request_age > 86400) { //24 hrs in seconds
            $this->user_model->delete_password_request($request_id);
            show404();
        }
        else {
            $new_pass = $this->rand_str(9);

            $dbdata = array();
            $dbdata['password'] = $new_pass;
            $this->user_model->edit_user($user_id, $dbdata);

            $sender  = "noreply@ci_board";
            $subject = "Password reset";
            $message = "Your ".$_SERVER['SERVER_NAME'] ."password is ".$new_pass;
            mail($address, "Subject: $subject", $message, "From: $sender" );

            $this->load->view('user/mailedpass', $data);
        }
    }

    //delete
    public function ban($banee_id) {
        $data    = $this->common_data();
        $user_id = $this->session->userdata('id');

        if ($user_id == FALSE) {
            redirect ('user/login', 'refresh');
        }

        if ($this->check_privilege($user_id) != 2) {
            redirect('homepage', 'refresh');
            return;
        }

        if ($this->user_model->is_banned($user_id)) {
            $data['user'] = $this->user_model->get_user($user_id);
            $this->load->view('user/youarebanned', $data);
            return;
        }

        $data['user'] = $this->user_model->get_user($banee_id);

        $this->form_validation->set_rules('banreason', 'Reason', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/ban', $data);
        }
        else { 
            $reason = $this->input->post('banreason');
            $this->user_model->ban_user($banee_id, $reason);
            $data['user'] = $this->user_model->get_user($banee_id);

            $this->load->view('user/userbanned', $data);
        }
    }
}

