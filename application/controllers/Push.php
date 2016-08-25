<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Push extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (strtolower($this->router->fetch_method()) != 'get' && !isset($_SESSION['signin'])) {
            redirect('signin');
        }
        $this->load->model('push_model', 'push');
        $this->push->init_db(FALSE);
    }
    
    public function index() {
        $this->load->view('push_view');
    }

    public function ajax_list() {
        $list = $this->push->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $push) {
            $no++;
            $row = array();
            $row[] = $push['apps_name'];
            $row[] = $push['message'];
            $row[] = isset($push['message_date']) ? $push['message_date'] : "";
            $row[] = isset($push['sent_status']) ? $push['sent_status'] : "";
            $row[] = isset($push['sent_date']) ? $push['sent_date'] : "";

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="View" onclick="view_push(' . "'" . $push['_id'] . "'" . ')"><i class="glyphicon glyphicon-search"></i> View</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_push(' . "'" . $push['_id'] . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->push->count_all(),
            "recordsFiltered" => $this->push->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->push->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {
        $data = array(
            'apps_name' => $this->input->post('apps_name'),
            'device_token' => $this->input->post('device_token'),
            'message' => $this->input->post('message'),
            'message_date' => gmdate("Y-m-d H:i:s"),
            'sent_status' => 0,
            'sent_by' => defined('PUSH_SENT_BY') ? PUSH_SENT_BY : 'PUSH_SENT_BY'
        );
        $insert = $this->push->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
//        $data = array(
//            'apps_name' => $this->input->post('apps_name'),
//            'device_token' => $this->input->post('device_token'),
//            'message' => $this->input->post('message'),
//            'message_date' => gmdate("Y-m-d H:i:s")
//        );
//        $this->push->update_by_id($this->input->post('_id'), $data);
//        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->push->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

}
