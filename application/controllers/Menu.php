<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    //*public function __construct()
    //*{
    //* parent::__construct();
    //*is_logged_in();
    //*}
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model('Menu_model', 'm');

        $a = $this->session->userdata('role_id');
        if ($a == null) {
            redirect('Auth', 'refresh');
        } else if ($a != 1) {
            redirect('Auth', 'refresh');
        }
    }
    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['usermenu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New menu added!</div>');
            redirect('menu');
        }
    }
    public function edit()
    {
        $this->db->update('user_menu', ['menu' => $this->input->post('menu')], ['id' => $this->input->post('id')]);
        redirect('menu','refresh');
    }
    public function delet()
    {
        $a = $this->uri->segment(3);
        $this->db->delete('user_menu', ['id' => $a]);
        redirect('menu', 'refresh');
    }
    public function submenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New sub menu added!</div>');
            redirect('menu/submenu');
        }
    }
    public function submenuedit()
    {
        $obj = array(
            'menu_id' => $this->input->post('menu_id'),
            'title' => $this->input->post('title'),
            'url' => $this->input->post('url'),
            'icon' => $this->input->post('icon'),
            'is_active' => $this->input->post('is_active')
        );
        $this->db->update('user_sub_menu', $obj, ['id' => $this->input->post('id')]);
        redirect('menu/submenu','refresh');
    }
    public function submenudelet()
    {
        $a = $this->uri->segment(3);
        $this->db->delete('user_sub_menu', ['id' => $a]);
        redirect('menu/submenu', 'refresh');
    }
    public function user()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->db->get('user')->result_array();
        $data['menu'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/user', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'name' => $this->input->post('title'),
                'role_id' => $this->input->post('menu_id'),
                'email' => $this->input->post('url'),
            ];
            $w = array('id' => $this->input->post('id'));
            $this->db->update('user', $data, $w);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New sub menu added!</div>');
            redirect('menu/user');
        }
    }
    public function deletuser()
    {
        $a = $this->uri->segment(3);
        $this->db->delete('user', ['id' => $a]);
    }
}
