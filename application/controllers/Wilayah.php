<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Wilayah_model');
    }

    public function get_provinsi() {
        $data = $this->Wilayah_model->get_provinsi();
        echo json_encode($data);
    }

    public function get_kabupaten($provinsi_id) {
        $data = $this->Wilayah_model->get_kabupaten($provinsi_id);
        echo json_encode($data);
    }

    public function get_kecamatan($kabupaten_id) {
        $data = $this->Wilayah_model->get_kecamatan($kabupaten_id);
        echo json_encode($data);
    }

    public function get_kelurahan($kecamatan_id) {
        $data = $this->Wilayah_model->get_kelurahan($kecamatan_id);
        echo json_encode($data);
    }
}
