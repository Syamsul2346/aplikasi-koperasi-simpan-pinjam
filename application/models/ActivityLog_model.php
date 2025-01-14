<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivityLog_model extends CI_Model
{

    // menyimpan Log aktivitas ke database
    public function saveLog($user_id_edit, $name, $action, $role_user)
    { date_default_timezone_set('Asia/Jakarta');
        $data = [
            'user_id' => $user_id_edit, // Menggunakan 'id' dari user
            'name' => $name, // Menyimpan nama pengguna
            'action' => $action,
            'role' => $role_user,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('activity_log', $data);
    }

    
    //mengakses semua log aktivitas untuk admin super
    public function getAllLogs() {
        $this->db->select('
        activity_log.id AS log_id,
        user.id AS user_id,
        user.name AS user_name,
        user_role.role AS role_name,
        activity_log.action,
        DATE_FORMAT(activity_log.timestamp, "%d-%m-%Y %H:%i:%s") AS timestamp
        ');
        $this->db->from('activity_log');
        $this->db->join('user', 'activity_log.user_id = user.id');
        $this->db->join('user_role', 'user.role_id = user_role.id'); // Join dengan tabel user_role untuk mendapatkan nama peran
        $this->db->order_by('activity_log.timestamp', 'DESC');
        return $this->db->get()->result_array();
        
    }

    //mengakses log aktivitas pribadi untuk member
    public function getUserLogs($user_id)
    {
        $this->db->select('
            activity_log.*,
            user.name AS user_name,
            user_role.role AS role_name,
            DATE_FORMAT(activity_log.timestamp, "%d-%m-%Y %H:%i:%s") AS timestamp
        ');
        $this->db->from('activity_log');
        $this->db->join('user', 'activity_log.user_id = user.id');
        $this->db->where('activity_log.user_id', $user_id);
        $this->db->join('user_role', 'user.role_id = user_role.id'); // Join dengan tabel user_role untuk mendapatkan nama peran
        $this->db->order_by('activity_log.timestamp', 'DESC');
        return $this->db->get()->result_array();
    }

}