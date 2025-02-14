<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Wilayah_model');
    }

    public function get_provinces()
    {
        $data = $this->Wilayah_model->get_provinsi(); // Mengambil data provinsi dari model
        echo json_encode($data); // Mengembalikan data dalam format JSON
    }

    public function get_regencies()
    {
        $province_id = $this->input->get('province_id'); // Ambil province_id dengan metode GET
        $data = $this->Wilayah_model->get_kabupaten($province_id);
        echo json_encode($data);
    }

    public function get_districts()
    {
        $regency_id = $this->input->get('regency_id'); // Ambil regency_id dengan metode GET
        $data = $this->Wilayah_model->get_kecamatan($regency_id);
        echo json_encode($data);
    }

    public function get_villages()
    {
        $district_id = $this->input->get('district_id'); // Ambil district_id dengan metode GET
        $data = $this->Wilayah_model->get_desa($district_id);
        echo json_encode($data);
    }
}
