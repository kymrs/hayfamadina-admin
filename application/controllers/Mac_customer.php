<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_customer extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_customer');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');

        // if (empty($this->session->userdata('cabang_id'))) {
        //     echo "
        //     <script>
        //         alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
        //         window.location.href = '" . site_url('dashboard') . "';
        //     </script>";
        //     exit;
        // }

        $data['add'] = $akses->add_level;
        $data['title']     = "backend/mac_customer/mac_customer_list";
        $data['titleview'] = "Data Customer";
        $this->load->view('backend/home', $data);
    }

    // ========== DATATABLES LIST ==========
    function get_list()
    {
        $list = $this->M_mac_customer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            $action_edit   = ($edit == 'Y')   ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_edit . $action_delete;

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->customer_name;
            $row[] = $field->type_customer;
            $row[] = $field->no_telp ?: '-';
            $row[] = $field->address;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_customer->count_all(),
            "recordsFiltered" => $this->M_mac_customer->count_filtered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    // ========== GET BY ID ==========
    function get_id($id)
    {
        $data = $this->M_mac_customer->get_by_id($id);
        echo json_encode($data);
    }

    // ========== ADD ==========
    public function add()
    {
        $title         = trim($this->input->post('title'));
        $customer_name = trim($this->input->post('customer_name'));
        $no_telp       = $this->input->post('no_telp');

        // Jika title "-" maka kosongkan
        if ($title === '-') {
            $title = '';
        }

        // Format nama customer
        $customer_name = ucwords(strtolower($customer_name));

        // Pastikan PT / CV tetap uppercase
        $customer_name = preg_replace_callback(
            '/\b(pt|cv)\b/i',
            function ($matches) {
                return strtoupper($matches[0]);
            },
            $customer_name
        );

        // Gabungkan title dengan nama customer
        $customer_name = trim($title . ' ' . $customer_name);

        $data = array(
            'type_customer' => $this->input->post('type_customer'),
            'customer_name' => $customer_name,
            'no_telp'       => $no_telp,
            'address'       => ucwords(strtolower($this->input->post('address'))),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $this->M_mac_customer->save($data);

        echo json_encode(array(
            "status"  => TRUE,
            "message" => "Data saved successfully"
        ));
    }

    // ========== UPDATE ==========
    public function update()
    {
        $customer_name = ucwords(strtolower($this->input->post('customer_name')));

        $customer_name = preg_replace_callback(
            '/\b(pt|cv)\b/i',
            function ($matches) {
                return strtoupper($matches[0]);
            },
            $customer_name
        );

        $no_telp = $this->input->post('no_telp');

        $data = array(
            'type_customer' => $this->input->post('type_customer'),
            'customer_name' => $customer_name,
            'no_telp'       => $no_telp,
            'address'       => ucwords(strtolower($this->input->post('address'))),
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mac_customer', $data);

        echo json_encode(array(
            "status"  => TRUE,
            "message" => "Data updated successfully"
        ));
    }

    // ========== DELETE ==========
    function delete($id)
    {
        $this->M_mac_customer->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}