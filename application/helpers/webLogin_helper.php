<?php 



function is_logged_in() 
{
    $help = get_instance();

    //tanda (!) digunakan bermakna jika tidak ada -> diarahkan ke auth 
    if(!$help->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $help->session->userdata('role_id');
        $menu = $help->uri->segment(1);

        $queryMenu = $help->db->get_where('user_menu', ['menu' => $menu])->
        row_array();
        $menu_id = $queryMenu['id'];

        $userAccess = $help->db->get_where('user_access_menu',[
            'role_id' => $role_id, 
            'menu_id' => $menu_id 
        ]);

        if($userAccess->num_rows() < 1) {
           redirect('forbidden');
        }
    }
}

function check_access($role_id, $menu_id) 
{
    $help = get_instance();

    $help->db->where('role_id', $role_id);
    $help->db->where('menu_id', $menu_id);
    $result = $help->db->get('user_access_menu');

    if($result->num_rows() > 0) {
        return "checked='checked'";
    }
    
}
