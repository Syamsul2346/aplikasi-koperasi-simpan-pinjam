<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah_model extends CI_Model
{
    public function get_provinsi()
    {
        return $this->db->get('provinces')->result_array();
    }

    public function get_kabupaten($province_id)
    {
        return $this->db->get_where('regencies', ['province_id' => $province_id])->result_array();
    }

    public function get_kecamatan($regency_id)
    {
        return $this->db->get_where('districts', ['regency_id' => $regency_id])->result_array();
    }

    public function get_desa($district_id)
    {
        return $this->db->get_where('villages', ['district_id' => $district_id])->result_array();
    }
}
