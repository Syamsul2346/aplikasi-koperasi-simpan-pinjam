<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct() 
    {

        parent ::__construct();
        $this->load->model("mdb");
		$this->load->model("nasabah");
		$this->load->model("keanggotaan");
		$this->load->helper("form");
		$this->load->helper("date");
		$this->load->library('export');
		$this->load->library('form_validation');
		$this->load->model('trs');
		// $this->trs->cekSimpananPokok('001');
		if(!$this->trs->last_check_bunga()){
			$this->trs->addBunga();
		}
		// $this->output->enable_profiler(TRUE);
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

    public function nasabah($action = '', $id = '')
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    
        if ($this->input->is_ajax_request()) {
            $this->output->enable_profiler(FALSE);
            $this->load->library('datatables');
            $this->datatables->select('id, kode, nama, tgl_masuk');
            $this->datatables->from('nasabah');
            $this->datatables->edit_column('nama', anchor('admin
            /nasabah/detail/$1', '$2'), 'kode, nama');
            $this->datatables->add_column('Action_data', 
                anchor('admin/nasabah/edit/$1', 'EDIT', 'class="btn btn-warning btn-mini hidden-print"') . ' ' .
                anchor('admin/nasabah/delete/$1', 'DELETE', ['class' => 'btn btn-danger btn-mini hidden-print', 'onClick' => 'return confirm(\'Apakah Anda benar-benar akan menghapus data ini?\')']), 
            'id');
            
            $this->datatables->add_column('Action_Simpan/pinjam',
                anchor('admin
                /simpanan/add?kode=$1', 'SIMPAN', 'class="btn btn-success btn-mini hidden-print"') . ' ' .
                anchor('admin
                /simpanan/ambil?kode=$1', 'AMBIL', 'class="btn btn-info btn-mini hidden-print"') . ' ' .
                anchor('admin
                /pinjaman/add?kode=$1', 'PINJAM', 'class="btn btn-default btn-mini hidden-print"') . ' ' .
                anchor('admin
                /pinjaman/bayar?kode=$1', 'BAYAR', 'class="btn btn-inverse btn-mini hidden-print"'), 
            'kode');
            
            echo $this->datatables->generate();
        } else {
            $this->form_validation->set_rules('nama', 'Nama anggota', 'trim|required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'trim');
            $this->form_validation->set_rules('hp', 'HP', 'trim');
            $this->form_validation->set_rules('keanggotaan_id', 'Keanggotaan', 'trim|required');
            $this->form_validation->set_rules('tgl_masuk', 'Tanggal masuk', 'trim|required');
            $this->form_validation->set_message('required', 'Harus diisi.');
            $this->form_validation->set_message('is_unique', 'Sudah ada di database.');
            
            $data['title'] = 'Nasabah';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            
            switch ($action) {
                case 'add':
                    $this->form_validation->set_rules('kode', 'Kode anggota', 'trim|required|is_unique[nasabah.kode]');
                    if ($this->form_validation->run() == FALSE) {
                        $this->_add('nasabah');
                    } else {
                        $this->mdb->add_nasabah();
                        redirect('admin
                        /nasabah');
                    }
                    break;
                case 'edit':
                    $this->form_validation->set_rules('kode', 'Kode anggota', 'trim|required|is_unique[nasabah.kode.id.' . $id . ']');
                    if ($this->form_validation->run() == FALSE) {
                        $this->_edit('nasabah', $id);
                    } else {
                        $this->mdb->edit_nasabah($id);
                        redirect('admin
                        /nasabah');
                    }
                    break;
                case 'delete':
                    $this->_delete('nasabah', $id);
                    break;
                case 'detail':
                    $data['data'] = $id;
                    $this->_template('nasabah/detail_nasabah', $data);
                    break;
                default:
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/sidebar', $data);
                    $this->load->view('templates/topbar', $data);
                    $this->load->view('admin/nasabah', $data);
                    $this->load->view('templates/footer');
                    break;
            }
        }
    }
    public function keanggotaan($action='', $id='')
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		if($this->input->is_ajax_request()/*||$this->input->get('data')*/)
		{
			$this->output->enable_profiler(FALSE);
			$this->load->library('datatables');
	        $this->datatables->select('id, jenis,simpanan_pokok, simpanan_wajib, bunga_simpanan, denda_pinjaman, keterangan');
	        $this->datatables->from('keanggotaan');
	        $this->datatables->add_column('Action_data', anchor('admin
            /keanggotaan/edit/$1','EDIT','class="btn btn-warning btn-mini hidden-print"').
	        	anchor('admin
                /keanggotaan/delete/$1','DELETE',array('class'=>'btn btn-danger btn-mini hidden-print', 'onClick'=>'return confirm(\'Apakah Anda benar-benar akan menghapus data ini?\')')), 'id');
	        echo $this->datatables->generate();
		}
		else
		{
			$this->form_validation->set_rules('nama', 'Nama anggota', 'trim|required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'trim');
			$this->form_validation->set_rules('hp', 'HP', 'trim');
			$this->form_validation->set_rules('keanggotaan_id', 'Keanggotaan', 'trim|required');
			$this->form_validation->set_rules('tgl_masuk', 'Tanggal masuk', 'trim|required');
			$this->form_validation->set_message('required', 'Harus diisi.');
			$this->form_validation->set_message('is_unique', 'Sudah ada didatabase.');

			switch ($action) 
			{
				case 'add':
					$this->form_validation->set_rules('kode', 'Kode anggota', 'trim|required|is_unique[keanggotaan.kode]');
					if ($this->form_validation->run() == FALSE)
					{
						$this->_add('keanggotaan');
					}
					else
					{
						$this->mdb->add_nasabah();
						redirect('admin
                        /keanggotaan');
					}
					break;
				case 'edit':
					$this->form_validation->set_rules('kode', 'Kode anggota', 'trim|required|is_unique[keanggotaan.kode.id.'.$id.']');
					if ($this->form_validation->run() == FALSE)
					{
						$this->_edit('keanggotaan',$id);
					}
					else
					{
						$this->mdb->edit_nasabah($id);
						redirect('admin
                        /keanggotaan');
					}
					break;
				case 'delete':
					$this->_delete('keanggotaan',$id);
					break;
				default:
					$this->_template('keanggotaan/keanggotaan');
					break;
			}
		}
	}

	public function simpanan($action='', $id='')
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		if($this->input->is_ajax_request())
		{
			$this->output->enable_profiler(FALSE);
			$this->load->library('datatables');

	        if($this->input->get('jenis')) $this->datatables->where('simpanan.jenis', $this->input->get('jenis'));
	        if($this->input->get('per')) $this->datatables->where('DATE_FORMAT(simpanan.tanggal, "%Y-%m") =', $this->input->get('per'));

	        $this->datatables->select('nasabah.kode, nasabah.nama, simpanan.tanggal, simpanan.jumlah, simpanan.id, FORMAT(sum(simpanan.jumlah), 0) as jumlah', FALSE);
	        $this->datatables->from('nasabah');

	        $this->datatables->join('(select * from simpanan order by tanggal desc) as simpanan','simpanan.kode_nasabah=nasabah.kode');
	        $this->datatables->group_by('simpanan.kode_nasabah');
	        // $this->datatables->where('simpanan.kode_nasabah');
	        $this->datatables->edit_column('nasabah.nama', anchor('admin
            /simpanan/detail/$1','$2'), 'nasabah.kode, nasabah.nama');
	        $this->datatables->edit_column('simpanan.jumlah', '<div style="text-align:right;">$1</div>', 'simpanan.jumlah');
	        $this->datatables->edit_column('simpanan.id', anchor('admin
            /simpanan/add?kode=$1','SIMPAN','class="btn btn-success btn-mini hidden-print"').' '.anchor('admin
            /simpanan/ambil?kode=$1','AMBIL','class="btn btn-info btn-mini hidden-print"'), 'nasabah.kode');
	        echo $this->datatables->generate();
		}
		else
		{

			switch ($action) 
			{
				case 'add':
					$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
					$this->form_validation->set_rules('jenis', 'Jenis Simpanan', 'trim|required');
					$this->form_validation->set_rules('nominal', 'Nominal', 'trim|required');
					if ($this->form_validation->run() == FALSE)
					{
						$this->_add('simpanan');
					}
					else
					{
						$kode = $this->input->post('kode_nasabah');
						$this->mdb->add_simpanan();
						redirect('admin
                        /simpanan/detail/'.$kode);
					}
					break;
				case 'detail':
					$data['kode'] = $id;
					$this->_template('simpanan/detail_simpanan',$data);
					break;
				case 'ambil':
					$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
					$this->form_validation->set_rules('nominal', 'Nominal', 'trim|required');
					if ($this->form_validation->run() == FALSE)
					{
						$this->_template('simpanan/ambil_simpanan');
					}
					else
					{
						$this->mdb->ambil_simpanan();
						redirect('admin
                        /simpanan');
					}
					break;
				case 'laporan':
					if($this->input->get('export')){
						header("Content-type: application/vnd.ms-excel");
						header("Content-Disposition: attachment; filename=Laporan-Simpanan.xls");
						$data['simpanan'] = $this->mdb->getLaporanSimpanan();
						$this->load->view('simpanan/export',$data);
					}else{
						$this->_template('simpanan/laporan_simpanan');
					}
					break;
				case 'delete':
					$this->_delete('simpanan',$id);
					break;
				default:
					$this->_template('simpanan/simpanan');
					break;
			}
		}
	}
	
	public function pinjaman($action='', $id='')
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		if($this->input->is_ajax_request())
		{
			$this->output->enable_profiler(FALSE);
			$this->load->library('datatables');
			if($this->input->get('jenis')) if($this->input->get('jenis')) $this->db->where('pinjaman.jenis', $this->input->get('jenis'));
			if($this->input->get('per')) $this->datatables->where('DATE_FORMAT(pinjaman.tanggal, "%Y-%m") =', $this->input->get('per'));
	        $this->datatables->select('nasabah.kode, nasabah.nama, pinjaman.tanggal, pinjaman.jenis, FORMAT(pinjaman.jumlah, 0) as jumlah, pinjaman.lama, pinjaman.status, pinjaman.id, nasabah.kode', FALSE);
	        $this->datatables->from('nasabah');
	        $this->datatables->join('pinjaman','pinjaman.kode_nasabah=nasabah.kode');
	        $this->datatables->edit_column('nasabah.nama', anchor('admin
            /pinjaman/detail/$1','$2'), 'nasabah.kode, nasabah.nama');
	        $this->datatables->edit_column('jumlah', '<div style="text-align:right;">$1</div>', 'jumlah');
	        $this->datatables->edit_column('pinjaman.id', anchor('admin
            /pinjaman/bayar?kode=$1','BAYAR','class="btn btn-info btn-mini hidden-print"'), 'nasabah.kode');
	        echo $this->datatables->generate();
		}
		else
		{
			switch ($action) 
			{
				case 'add':
					$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
					// $this->form_validation->set_rules('jenis', 'Jenis Simpanan', 'trim|required');
					$this->form_validation->set_rules('bunga', 'Bunga Simpanan', 'trim|required');
					$this->form_validation->set_rules('nominal', 'Nominal', 'trim|required');
					$this->form_validation->set_rules('lama', 'Waktu angsuran', 'trim|required');
					if ($this->form_validation->run() == FALSE)
					{
						$this->_add('pinjaman');
					}
					else
					{
						$this->mdb->add_pinjaman();
						redirect('admin
                        /pinjaman');
					}
					break;
				case 'edit':
					$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
					// $this->form_validation->set_rules('jenis', 'Jenis Simpanan', 'trim|required');
					$this->form_validation->set_rules('bunga', 'Bunga Simpanan', 'trim|required');
					$this->form_validation->set_rules('nominal', 'Nominal', 'trim|required');
					$this->form_validation->set_rules('lama', 'Waktu angsuran', 'trim|required');
					if ($this->form_validation->run() == FALSE)
					{
						$data['pinjaman']=$this->mdb->formPinjam($id);
						$this->_template('pinjaman/edit_pinjaman',$data);
					}
					else
					{
						$this->mdb->edit_pinjaman();
						redirect('admin
                        /pinjaman');
					}
					break;
				case 'detail':
					$data['kode'] = $id;
					$this->_template('pinjaman/detail_pinjaman',$data);
					break;
				case 'bayar':
					$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
					$this->form_validation->set_rules('cicilan_ke', 'Nominal', 'trim|required');
					$this->form_validation->set_rules('nominal', 'Nominal', 'trim|required');
					if ($this->form_validation->run() == FALSE)
					{
						$this->_template('pinjaman/bayar_pinjaman');
					}
					else
					{
						$this->mdb->bayarPinjaman();
						redirect('admin
                        /pinjaman');
					}
					break;
				case 'delete':
					$this->_delete('pinjaman',$id);
					break;
				case 'laporan':
				if($this->input->get('export')){
						header("Content-type: application/vnd.ms-excel");
						header("Content-Disposition: attachment; filename=Laporan-Pinjaman.xls");
						$data['pinjaman'] = $this->mdb->getLaporanPinjaman();
						$this->load->view('pinjaman/export',$data);
				}else{
					$this->_template('pinjaman/laporan_pinjaman');
				}
					break;
				default:
					$this->_template('pinjaman/pinjaman');
					break;
			}
		}
	}

	public function payroll($action='', $id='')
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		if($this->input->is_ajax_request())
		{
			$this->output->enable_profiler(FALSE);
			$this->load->library('datatables');
	        $this->datatables->select('nasabah.nama,nasabah.kode, FORMAT(sukarela.jumlah, 0) as sukarela, FORMAT(srplus.jumlah, 0) as srplus, FORMAT(import_temp.amount, 0) as jkt', FALSE);
	        $this->datatables->from('nasabah');
	        $this->datatables->join('(SELECT kode_nasabah, sum(jumlah) as jumlah FROM `simpanan` where jenis = "Sukarela" group by kode_nasabah) as sukarela', 'sukarela.kode_nasabah=nasabah.kode');
	        $this->datatables->join('(SELECT kode_nasabah, sum(jumlah) as jumlah FROM `simpanan` where jenis = "Surplus" group by kode_nasabah) as srplus', 'srplus.kode_nasabah=nasabah.kode');
	        $this->datatables->join('import_temp', 'import_temp.kode=nasabah.kode', 'left');
	        echo $this->datatables->generate();
		}
		else
		{

			switch ($action) 
			{
				case 'import':
					$this->_add('payroll');
					break;
				case 'upload':
					$config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"])."/assets/";
					$config['overwrite'] = true;
					$config['allowed_types'] = '*';
					$config['max_size']	= '1000000';

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('payroll'))
					{
						$error = array('error' => $this->upload->display_errors());
						print_r($error);
						// $this->_add('payroll');
					}
					else
					{
						$data['file'] = $this->upload->data();
						$this->_template('payroll/overview', $data);
					}
					break;
				default:
					$this->_template('payroll/payroll');
					break;
			}
		}
	}

}