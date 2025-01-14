<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivityLog extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ActivityLog_model');
    }

    // untuk admin yang dapat melihat semua aktivitas member dan admin lain
    public function index() 
    {
        $data['title'] = 'Log Aktivitas';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['logs'] = $this->ActivityLog_model->getAllLogs();

        if($this->form_validation->run() == false)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('activity_log/index', $data);
            $this->load->view('templates/footer');
        } else {

        }
    }

    public function myHistory() 
    {
        $data['title'] = 'My History';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Ambil user_id dari data user
        $user_id = $data['user']['id'];

        $data['logs'] = $this->ActivityLog_model->getUserLogs($user_id);

        if($this->form_validation->run() == false)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('activity_log/my-history', $data);
            $this->load->view('templates/footer');
        } else {

        }
    }
}