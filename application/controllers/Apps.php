<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apps extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['signin'])) {
            redirect('signin');
        }
        $this->load->model('apps_model', 'apps');
        $this->apps->init_db(false);
    }

    public function index() {
        $this->load->view('apps_view');
    }
    
    public function ajax_list() {
        $list = $this->apps->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $apps) {
            $no++;
            $row = array();
            $row[] = $apps['apps_name'];
            $row[] = $apps['apps_url'];
            $row[] = $apps['bundle_id'];
            $row[] = $apps['pem_file'];

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="edit_apps(' . "'" . $apps['_id'] . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_apps(' . "'" . $apps['_id'] . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->apps->count_all(),
            "recordsFiltered" => $this->apps->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->apps->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {
        $pem_file = isset($_FILES['pem_file']['name']) ? $_FILES['pem_file']['name'] : "";
        $pem_content = isset($_FILES['pem_file']['tmp_name']) ? file_get_contents($_FILES['pem_file']['tmp_name']) : "";
        $data = array(
            'apps_name' => $this->input->post('apps_name'),
            'apps_url' => $this->input->post('apps_url'),
            'bundle_id' => $this->input->post('bundle_id'),
            'pem_file' => $pem_file,
            'pem_content' => $pem_content
        );
        $insert = $this->apps->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        $pem_file = isset($_FILES['pem_file']['name']) ? $_FILES['pem_file']['name'] : "";
        $pem_content = isset($_FILES['pem_file']['tmp_name']) ? file_get_contents($_FILES['pem_file']['tmp_name']) : "";
        $data = array(
            'apps_name' => $this->input->post('apps_name'),
            'apps_url' => $this->input->post('apps_url'),
            'bundle_id' => $this->input->post('bundle_id'),
            'pem_file' => $pem_file,
            'pem_content' => $pem_content
        );
        $this->apps->update_by_id($this->input->post('_id'), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->apps->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
}
