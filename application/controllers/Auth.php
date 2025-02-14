<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Wilayah_model', 'wilayah'); // Panggil model
    }

    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if($this->form_validation->run() == FALSE) {

            $data['title'] = 'web-login login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');

        } else{
            //jika validasi sukses
            $this->_login();
        }

    }


    private function _login(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        
        //jika user ada 
        if($user) {

            //jika usernya aktif
            if($user['is_active'] == 1) {

                //cek passwordnya
                if(password_verify($password, $user['password'])) {
                    //jika benar maka masuk ke session user/admin
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];

                    $this->session->set_userdata($data);
                    if($user['role_id'] == 1) {
                        redirect('admin');

                    } else {
                        redirect('user');
                    }

                } else {
                    $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                    Password tidak valid!</div>');
                    redirect('auth');
                }

            } else {
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                Email belum diaktivasi!</div>');
                redirect('auth');
            }

        } else{
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Email belum terdaftar! </div>');
            redirect('auth');

        }
    }


    public function registration()
{
    // Ambil data provinsi langsung menggunakan query
    $query = $this->db->get('provinces'); // Gantilah dengan tabel yang sesuai
    $data['provinces'] = $query->result_array();  // Mengambil hasil query dan menyimpannya dalam $data
    // Aturan validasi form
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
        'is_unique' => 'This email has been already registered!'
    ]);
    $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
        'matches' => 'Password doesn`t match!',
        'min_length' => 'Password too short!'
    ]);
    $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
    $this->form_validation->set_rules('phone', 'Phone', 'required|trim|numeric|max_length[15]');
    $this->form_validation->set_rules('ktp', 'KTP Number', 'required|trim|numeric|max_length[16]');
    $this->form_validation->set_rules('address', 'Address', 'required|trim');
    $this->form_validation->set_rules('dob', 'Date of Birth', 'required|trim');
    $this->form_validation->set_rules('job', 'Job', 'required|trim');
    $this->form_validation->set_rules('gender', 'Gender', 'required|trim|in_list[L,P]');
    $this->form_validation->set_rules('education', 'Education', 'required|trim');



    // Periksa apakah validasi berjalan dengan baik
    if ($this->form_validation->run() == FALSE) {
        // Jika validasi gagal, tampilkan kembali halaman registrasi
        $data['title'] = 'Web Login Registration';
        // $data['daerah'] = $this->wilayah->get_provinsi();
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/registration', $data);
        $this->load->view('templates/auth_footer');
        echo validation_errors();
    } else {
        // Ambil input tanggal dari form
        $dob = $this->input->post('dob', true); // Ambil input tanggal
        if (!empty($dob)) {
            // Konversi format dd/mm/yyyy menjadi yyyy-mm-dd
            $dob_formatted = date('Y-m-d', strtotime(str_replace('/', '-', $dob)));
            // Tanggal untuk ditampilkan di form (format Indonesia)
            $dob_display = date('d/m/Y', strtotime($dob_formatted));
        } else {
            $dob_formatted = null; // Jika kosong
            $dob_display = ''; // Atau set $dob_display ke kosong jika tidak ada input
        }

        // Kirimkan $dob_display ke view
        $data['dob_display'] = $dob_display; // Menambahkan $dob_display ke data view

        // Ambil data input lainnya dan masukkan ke dalam array $data
        $data = [
            'name' => htmlspecialchars($this->input->post('name', true)),
            'email' => $this->input->post('email', true),
            'phone' => htmlspecialchars($this->input->post('phone', true)),
            'ktp' => htmlspecialchars($this->input->post('ktp', true)),
            'address' => htmlspecialchars($this->input->post('address', true)),
            'province_id' => $this->input->post('province_id'),
            'regency_id' => $this->input->post('regency_id'),
            'district_id' => $this->input->post('district_id'),
            'village_id' => $this->input->post('village_id'),
            'dob' => $dob_formatted, // Menyimpan format Y-m-d
            'job' => htmlspecialchars($this->input->post('job', true)),
            'gender' => htmlspecialchars($this->input->post('gender', true)),
            'education' => htmlspecialchars($this->input->post('education', true)),
            'image' => 'default.jpg',
            'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
            'role_id' => 2,
            'is_active' => 0,
            'date_created' => date('Y-m-d')
        ];

        // Siapkan token
        $token = base64_encode(random_bytes(32));
        $user_token = [
            'email' => $data['email'],
            'token' => $token,
            'date_created' => time()
        ];

        // Masukkan data ke database
        $this->db->insert('user', $data);
        $this->db->insert('user_token', $user_token);

        // Kirim email verifikasi
        $this->_sendEmail($token, 'verify');

        // Beri notifikasi dan alihkan ke halaman login
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Selamat! Akun Anda telah berhasil didaftarkan. Silakan aktifkan akun Anda melalui email!
        </div>');
        redirect('auth');
    }
}

    

    private function _sendEmail($token,$type) {
        //atur config di php.ini dan sendmail.ini
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_user' => 'putra.neko23@gmail.com',
            'smtp_pass' => 'afsyjlmwuzslwjnx',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        $this->load->library('email', $config);
        //tambahkan set_mailtype untuk menerima tulisan html di email
        $this->email->set_mailtype('html');
        $this->email->from('putra.neko23@gmail.com', 'Web Login');
        $this->email->to($this->input->post('email'));

        if($type == 'verify') {
            $this->email->subject('Account Verification');
            // fungsi "urlencode ()" digunakan untuk membangkitkan karakter baytes menjadi karakter persen dll yang mudah dibaca
            $this->email->message('Klik tombol ini untuk verifikasi! : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Active</a>');    
        } else if($type == 'forgot'){
            // perbedaan if else dan if else if adalah : jika if else (true/false) saja if else if itu memberikan pilihan bercabang
            $this->email->subject('Reset Password');
            // fungsi "urlencode ()" digunakan untuk membangkitkan karakter baytes menjadi karakter persen dll yang mudah dibaca
            $this->email->message('Klik tombol ini untuk mereset your password ! : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset</a>');
        }
        
       if($this->email->send()) {
           return true;
        } else {
           echo $this->email->print_debugger();
           die;
        }
    }

    public function verify(){
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        //fitur menampilkan pesan agar email tidak diubah di url google
        if($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            //fitur menampilkan pesan agar token tidak diubah dari url google
            if($user_token) {
                //fitur Waktu kalau if terpenuhi
                 if(time() - $user_token['date_created'] < (60 * 60 * 24)){
                    // mengubah is_active dari 0 menjadi 1
                    $this->db->set('is_active', 1);
                    $this->db->where('email',$email);
                    $this->db->update('user');

                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">' . $email . ' Sudah teraktivasi! silahkan login.</div>');
                    redirect('auth');

                 } else { 
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                    Aktivasi akun gagal! Token salah!</div>');
                    redirect('auth');

                 }

            } else{
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                Aktivasi akun gagal! Token salah!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Aktivasi akun gagal! Email salah!</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
        Kamu sudah logut!</div>');
        redirect('auth');
    }

    // public function blocked() 
    // {
    //     $this->load->view('auth/blocked');
    // }

    public function forgotPassword() 
    {
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/auth_footer');

        } else{ 
            // cek email apakah ada atau tidak di database
            $email = $this->input->post('email');
            $user = $this->db->get_where('user',['email' => $email, 'is_active' => 1])->row_array();

            if($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Silahkan cek email untuk rest password ! </div>');
                redirect('auth/forgotpassword');

            } else {
                // pesan kalau setelah dicek email tidak terdaftar diarahkan ke auth
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                Email Belum Terdaftar atau Belum Aktivasi!</div>');
                redirect('auth/forgotpassword');

            }
        }
    }

    public function resetPassword() 
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if($user) {
            // jika email sesuai if selanjutnya cek token
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if($user_token) {
                // memasang halaman yang ubah password pada saat menekan link reset
                $this->session->set_userdata('reset_email', $email);
                // masuk ke halaman ubah password
                $this->changePassword();

            } else{
                // pesan kalau tokennya tidak sesuai saat sesi reset password
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                Reset Password Gagal! Token Salah!</div>');
                redirect('auth');
            }
        } else {
            // pesan kalau reset emailnya tidak sesuai saat sesi reset password
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Reset Password Gagal! Email Salah!</div>');
            redirect('auth');
        }
    }

    public function changePassword() 
    {
        if(!$this->session->userdata('reset_email')) {
            redirect('auth');
        }
        
        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[6]|matches[password1]');
        
        if($this->form_validation->run() == false) {

            $data['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_footer');
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            // harus ada ini untuk menghilangkan session changepassword setelah dipost
            $this->session->unset_userdata('reset_email');

            // pesan kalau reset emailnya tidak sesuai saat sesi reset password
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Selamat Password telah berubah! Silahkan login</div>');
            redirect('auth');

        }
    }

}
