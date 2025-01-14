<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah_model extends CI_Model {

    // Fungsi untuk mengambil data provinsi
    public function get_provinsi() {
        return $this->db->select('kode, nama')
                        ->where('CHAR_LENGTH(kode)', 2)  // Hanya memilih kode dengan panjang 2 (Provinsi)
                        ->order_by('nama', 'ASC')  // Urutkan berdasarkan nama
                        ->get('wilayah_2022')
                        ->result_array();  // Mengembalikan hasil dalam bentuk array
    }

    // Fungsi untuk mengambil data kabupaten berdasarkan provinsi_id
    public function get_kabupaten($provinsi_id) {
        return $this->db->select('kode, nama')
                        ->where("LEFT(kode, 2) =", substr($provinsi_id, 0, 2))  // Cocokkan kode provinsi
                        ->where('CHAR_LENGTH(kode)', 5)  // Hanya memilih kode dengan panjang 5 (Kabupaten)
                        ->order_by('nama', 'ASC')  // Urutkan berdasarkan nama
                        ->get('wilayah_2022')
                        ->result_array();  // Mengembalikan hasil dalam bentuk array
    }

    // Fungsi untuk mengambil data kecamatan berdasarkan kabupaten_id
    public function get_kecamatan($kabupaten_id) {
        return $this->db->select('kode, nama')
                        ->where("LEFT(kode, 5) =", substr($kabupaten_id, 0, 5))  // Cocokkan kode kabupaten
                        ->where('CHAR_LENGTH(kode)', 8)  // Hanya memilih kode dengan panjang 8 (Kecamatan)
                        ->order_by('nama', 'ASC')  // Urutkan berdasarkan nama
                        ->get('wilayah_2022')
                        ->result_array();  // Mengembalikan hasil dalam bentuk array
    }

    // Fungsi untuk mengambil data desa berdasarkan kecamatan_id
    public function get_kelurahan($kecamatan_id) {
        return $this->db->select('kode, nama')
                        ->where("LEFT(kode, 8) =", substr($kecamatan_id, 0, 8))
                        ->where('CHAR_LENGTH(kode)', 13)
                        ->order_by('nama', 'ASC')
                        ->get('wilayah_2022')
                        ->result_array();
    }

    // Fungsi umum untuk mengambil data wilayah berdasarkan ID
    public function get_wilayah_by_id($id) {
        $n = strlen($id);  // Mengambil panjang ID yang diberikan.
        $m = ($n == 2 ? 5 : ($n == 5 ? 8 : 13));  // Tentukan panjang kode berdasarkan ID

        return $this->db->select('kode, nama')
                        ->where("LEFT(kode, $n) =", $id)  // Cocokkan kode yang dimulai dengan ID yang diberikan.
                        ->where('CHAR_LENGTH(kode)', $m)  // Sesuaikan panjang kode berdasarkan tingkat wilayah.
                        ->order_by('nama', 'ASC')  // Urutkan berdasarkan nama
                        ->get('wilayah_2022')  // Ambil data dari tabel wilayah_2022
                        ->result_array();  // Kembalikan hasil dalam bentuk array
    }
}
