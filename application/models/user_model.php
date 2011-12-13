<?php
class User_model extends CI_Model {
    var $name             = NULL;
    var $password         = NULL;
    var $join_date        = NULL;
    var $email            = NULL;
    var $permission_level = NULL;
    var $userban_reason   = NULL;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_user($username, $email, $password) {
        $sql = "SELECT crypt(?, password) FROM users as res";
        $q = $this->db->query($sql, array($password));

        $pass4db = $q->row()->crypt;

        $this->name             = $username;
        $this->password         = $pass4db;
        $this->join_date        = date('Y-m-d H:i:s'); 
        $this->email            = $email;
        $this->permission_level = 1;

        $this->db->insert('users', $this);

        $this->password = "";
    }

    public function get_user($id) {
        $sql = "SELECT * FROM users WHERE id = ?";

        $query = $this->db->query($sql, array($id));
        return $query->row();
    }

    public function user_in_db($username) {
        $lowername = strtolower($username);
        $sql = "SELECT id FROM users WHERE lower(name) = ?";
        $query = $this->db->query($sql, array($lowername));

        return ($query->num_rows() > 0);
    }

    public function email_in_db($address) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $query = $this->db->query($sql, array($address));

        return ($query->num_rows() > 0);
    }

    public function is_banned($user_id) {
        $user = $this->get_user($user_id);

        if (empty($user)) {
            return 0;
        }

        return ($user->permission_level == -1);
    }

    public function get_id_by_email($address) {
        $id = -1;
        $sql = "SELECT id FROM users WHERE email = ?";

        $q   = $this->db->query($sql, array($address));

        $res = $q->row();

        if (!empty($res)) {
            $id  = $res->id;
        }

        return $id;
    }

    public function get_id_by_name($name) {
        $id = -1;
        $sql = "SELECT id FROM users WHERE name = ?";

        $q   = $this->db->query($sql, array($name));

        $res = $q->row();

        if (!empty($res)) {
            $id  = $res->id;
        }

        return $id;
    }

    public function get_password_request($request_id) {
        $sql = "SELECT * FROM password_requests WHERE id = ?";
        $q = $this->db->query($sql, array($request_id));
        return $query->row();
    }

    public function delete_password_request($request_id) {
        $sql = "DELETE FROM password_requests WHERE id = ?";
        $this->db->query($sql, array($id));
    }

    public function insert_password_request($id, $request_date, $user_id) {
        $sql = "INSERT INTO password_requests(id, request_date, user_id) 
                VALUES (?, ?, ?)";

        $this->db->query($sql, array($id, $request_date, $user_id));
    }


    public function get_id_by_credentials($username, $rawpassword) {
        $id = -1;
        $confirmed = false;

        $sql = "SELECT password = crypt(?, password) as res FROM users WHERE name = ?";
        $q = $this->db->query($sql, array($rawpassword, $username));

        $confirmed = $q->row()->res;

        if ($confirmed === 't') {

            $sql = "SELECT id FROM users WHERE name = ?";
            $q = $this->db->query($sql, array($username));
            $id = $q->row()->id;
            
        }

        return $id;
    }

    public function edit_user($id, $data) {
        $data['id'] = $id;

        if (isset($data['password'])) {
            $input_password = $data['password'];

            $sql = "SELECT crypt(?, gen_salt('bf', 5));";
            $q   = $this->db->query($sql, array($input_password));

            $data['password'] = $q->row()->crypt;
        }

        $this->db->where('id', $id);
        $this->db->set($data);
        $this->db->update('users'); 
    } 

    public function ban_user($user_id, $reason) {
        $sql = "UPDATE users SET permission_level = -1, userban_reason = ? WHERE id = ?";
        $this->db->query($sql, array($reason, $user_id));
    }

    public function remove_user($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $this->db->query($sql, array($id));
    }

}
