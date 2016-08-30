<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model', 'admin');
    }

    public function index() {
        $data['message'] = "";
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $data = $this->admin->signin($_POST['username'], $_POST['password']);
            if (is_object($data) || is_array($data)) {
                $roles = json_decode(isset($data['roles']) ? $data['roles'] : '[]');
                if (in_array($roles, "PushAdmin") || $data['username'] == 'admin') {
                    $_SESSION['signin'] = $data;
                    redirect('apps');
                } else {
                    $data['message'] = "Sign In Failed.";
                }
            } else {
                $data['message'] = "Sign In Failed.";
            }
        }
        $this->load->view('signin_view', $data);
    }

    public function out() {
        session_destroy();
        redirect('signin');
    }

    public function info() {
        phpinfo();
    }

}
