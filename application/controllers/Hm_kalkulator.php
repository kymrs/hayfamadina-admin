<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hm_kalkulator extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index() {

        $data['titleview'] = "Kalkulator Angsuran Umrah";
        $data['title']     = "backend/hm_kalkulator/hm_kalkulator"; 
        $this->load->view('backend/home', $data);

    }

}