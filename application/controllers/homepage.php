<?php
require ('ci_board_controller.php');
class Homepage extends Ci_board_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        //no input field in this view
        $data = $this->common_data(0);

        $this->load->view('homepage', $data);
    }

}

