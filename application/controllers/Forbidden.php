<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forbidden extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'Forbidden page';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('forbidden/index');
        $this->load->view('templates/footer', $data);

    }
}