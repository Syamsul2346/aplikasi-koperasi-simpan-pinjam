<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct() 
    {

        parent ::__construct();
        $this->load->model('ActivityLog_model', 'log_activity'); // Tambahkan ini
        is_logged_in();
    }
    
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Accsess';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get_where('user_role',['id' => $role_id ])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
        Akses telah diubah! </div>');
    }

    public function members() 
    {
        if($this->form_validation->run() == false) {

            $data['title'] = 'Members';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            
            $this->db->select('user.*, user_role.role as role_name');
            $this->db->from('user');
            $this->db->join('user_role', 'user.role_id = user_role.id');
            $data['members'] = $this->db->get()->result_array();
    
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/members', $data);
            $this->load->view('templates/footer');
        }
    }

    public function memberEdit($id) 
    {
        $data['title'] = 'Edit Data Member';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Dapatkan data role untuk dropdown
        $data['roles'] = $this->db->get('user_role')->result_array();

        // Gabungkan role_name ke dalam data user
        $this->db->select('user.*, user_role.role as role_name');
        $this->db->join('user_role', 'user.role_id = user_role.id');
        $data['data_user'] = $this->db->get_where('user', ['user.id' => $id])->row_array();

        // Validasi Nama
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            // Tampilkan halaman jika validasi gagal
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/memberedit', $data);
            $this->load->view('templates/footer');
        } else {
            // Ambil data dari form
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $role_id = $this->input->post('role_id');
            $is_active = (int) $this->input->post('is_active'); // Ubah ke integer agar cocok dengan nilai di DB

            // Inisialisasi daftar aksi yang dilakukan
            $actions = [];
            $update_data = [];

            // Cek jika ada gambar yang akan diupload
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['data_user']['image'];
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

            // Cek perubahan pada nama
            if ($data['data_user']['name'] !== $name) {
                $update_data['name'] = $name;
                $actions[] = 'Nama';
            }

            // Cek perubahan pada role
            if ($data['data_user']['role_id'] != $role_id) { // Perbandingan tanpa tipe data agar sesuai dengan DB
                $update_data['role_id'] = $role_id;
                $actions[] = 'Role';
            }

            // Cek perubahan pada status aktif
            if ((int) $data['data_user']['is_active'] !== $is_active) { // Pastikan tipe data integer
                $update_data['is_active'] = $is_active;
                $actions[] = 'Aktivasi';
            }

            // Jika ada perubahan, lakukan update
            if (!empty($actions)) {
                $this->db->where('id', $id);
                $this->db->update('user', $update_data); // Mengupdate data yang sudah dicatat dalam array
                
                // Simpan log aktivitas
                $user_id_edit = $data['user']['id'];
                $role_user = $data['user']['role_id'];
                $admin_name = $data['user']['name'];
                $member_name = $data['data_user']['name'];
                $action_string = implode(', ', $actions); // Gabungkan semua aksi yang dilakukan
                $action_message = "$admin_name telah mengubah $action_string dari member $member_name";

                $this->log_activity->saveLog($user_id_edit, $name, $action_message, $role_user);

                // Set pesan flashdata dan redirect ke halaman yang sesuai
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $action_message . ' berhasil diperbarui!</div>');
            } else {
                // Jika tidak ada perubahan
                $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Tidak ada perubahan yang dilakukan.</div>');
            }

            redirect('admin/members'); // redirect kembali ke halaman edit
        }
    }




    public function deleteMember($id)
    {
        // Hapus data member berdasarkan id
        $this->db->delete('user', ['id' => $id]);

        // Pesan sukses
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User berhasil dihapus!</div>');
        redirect('admin/members');
    }



}