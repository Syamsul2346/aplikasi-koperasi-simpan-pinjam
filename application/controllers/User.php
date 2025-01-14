<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct() 
    {

        parent ::__construct();
        $this->load->model('ActivityLog_model', 'log_activity'); // Tambahkan ini
        is_logged_in();
    }
    
    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');


    }

    public function edit()
{
    $data['title'] = 'Edit Profile';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

    if ($this->form_validation->run() == false) {
        // Tampilkan halaman edit
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/edit', $data);
        $this->load->view('templates/footer');
    } else {
        // Ambil data dari form
        $name = $this->input->post('name');
        $email = $this->input->post('email');

        // Inisialisasi daftar aksi yang dilakukan
        $actions = [];
        $update_data = [];

        // Periksa apakah ada perubahan pada nama
        if ($data['user']['name'] !== $name) {
            $update_data['name'] = $name;
            $actions[] = 'Nama';
        }

        // Cek jika ada gambar yang akan diupload
        $upload_image = $_FILES['image']['name'];
        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['upload_path'] = './assets/profile/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $old_image = $data['user']['image'];
                // Hapus gambar lama jika bukan default.jpg
                if ($old_image != 'default.jpg') {
                    unlink(FCPATH . 'assets/profile/' . $old_image);
                }
                $new_image = $this->upload->data('file_name');
                $update_data['image'] = $new_image;
                $actions[] = 'Gambar';
            } else {
                echo $this->upload->display_errors();
            }
        }

        // Jika ada perubahan, lakukan update
        if (!empty($actions)) {
            $this->db->where('email', $email);
            $this->db->update('user', $update_data);

            // Simpan log aktivitas menggunakan saveLog()
            $user_id = $data['user']['id'];
            $role_id = $data['user']['role_id']; // Asumsikan `role_id` ada di data user
            $admin_name = $data['user']['name'];
            $action_string = implode(', ', $actions); // Gabungkan semua aksi yang dilakukan
            $action_message = "$admin_name telah mengubah $action_string pada profil mereka";

            $this->log_activity->saveLog($user_id, $name, $action_message, $role_id);

            // Set pesan flashdata
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $action_message . '!</div>');
        } else {
            // Jika tidak ada perubahan
            $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Tidak ada perubahan yang dilakukan.</div>');
        }

        redirect('user/edit');
    }
}


    public function changePassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $user_id = $data['user']['id'];
        $role_id = $data['user']['role_id'];

        var_dump('user_id',$user_id);
        var_dump('role_id',$role_id);

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[6]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[6]|matches[new_password1]');
        
        if($this->form_validation->run() == false ) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            if(!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                    Password lama tidak cocok!</div>');
                    redirect('user/changepassword');
            } else {
                if($current_password == $new_password){
                    $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                    Password tidak boleh sama dengan password sebelumnya!</div>');
                    redirect('user/changepassword');
                } else {
                    //password sudah ok
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                    Selamat password telah diubah! </div>');
                    redirect('user/changepassword');
                }
            }
        }


    }
}