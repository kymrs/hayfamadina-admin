<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    
    // untuk sidebar
    function pending_notification()
    {
        $tbl_notifikasi = $this->db
            ->select('tbl_submenu.id_submenu, tbl_submenu.id_menu, tbl_submenu.link, tbl_submenu.nama_tbl, tbl_menu.nama_menu')
            ->from('tbl_submenu')
            ->join('tbl_menu', 'tbl_menu.id_menu = tbl_submenu.id_menu', 'left') // atau 'inner' sesuai kebutuhan
            ->where('tbl_submenu.nama_tbl IS NOT NULL', null, false)
            ->get()
            ->result();
        $id = $this->session->userdata('id_user');
        $username = strtolower($this->session->userdata('username') ?? '');
        $name = $this->db->select('fullname')
            ->from('tbl_user')
            ->where('id_user', $id)
            ->get()
            ->row('fullname');

        $tbl = [];
        foreach ($tbl_notifikasi as $table) {
            $tabel_nama = $table->nama_tbl;
            $sub_menu = $table->link;
            if ($tabel_nama != '' || $tabel_nama != null) {
                // Penanganan Khusus untuk mac_invoice & mac_peminjaman
                if (in_array($tabel_nama, ['mac_invoice', 'mac_peminjaman'])) {
                    $approver_khusus = ['bhakti', 'dwi'];
                    if (in_array($username, $approver_khusus)) {
                        $this->db->from($tabel_nama)
                            ->where('app_status', 'waiting');
                        // Kondisi spesifik masing-masing tabel
                        if ($tabel_nama == 'mac_invoice') {
                            $this->db->where('is_active', 1);
                        } elseif ($tabel_nama == 'mac_peminjaman') {
                            $this->db->where('status', 'aktif');
                        }
                        $total = $this->db->count_all_results();
                        $tbl[$sub_menu] = $total;
                    } else {
                        $tbl[$sub_menu] = 0;
                    }
                    continue;
                }
                // Cek kolom yang tersedia di tabel tersebut
                $fields = $this->db->list_fields($tabel_nama);
                // Tentukan nama kolom yang dipakai untuk pencarian nama
                $kolom_nama = null;
                $where = [];
                if (in_array('app4_name', $fields) && in_array('app4_status', $fields)) {
                    // app4 hanya jika statusnya waiting dan app4_name tidak null
                    $where[] = "(LOWER(app4_name) = " . $this->db->escape(strtolower($name)) . " AND app4_status = 'waiting' AND app4_name IS NOT NULL)";
                }
                // PU Notifikasi / HC Approval
                if (in_array('app_hc_name', $fields) && in_array('app_hc_status', $fields)) {
                    $where[] = "(" .
                        "LOWER(app_hc_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                        "app_hc_status = 'waiting'" .
                        ")";
                }
                if (in_array('app_name', $fields) && in_array('app_status', $fields)) {
                    if (in_array('app4_status', $fields) && in_array('app4_name', $fields)) {
                        $where[] = "(" .
                            "LOWER(app_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                            "app_status = 'waiting' AND " .
                            "(app4_name IS NULL OR app4_name = '' OR app4_status = 'approved')" .
                            ")";
                    } else {
                        $where[] = "(" .
                            "LOWER(app_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                            "app_status = 'waiting'" .
                            ")";
                    }
                }
                if (in_array('app2_name', $fields) && in_array('app2_status', $fields)) {
                    if (in_array('app_status', $fields)) {
                        // app2 hanya jika app sudah approved
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_status = 'approved')";
                    } elseif (in_array('app_hc_status', $fields)) {
                        // app2 hanya jika HC sudah approved
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_hc_status = 'approved')";
                    } else {
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting')";
                    }
                }
                if (!empty($where)) {
                    $this->db->select('COUNT(*) AS total')
                        ->from($tabel_nama)
                        ->where(implode(' OR ', $where), null, false);
                    $total = $this->db->get()->row()->total;
                    $tbl[$sub_menu] = $total;
                } else {
                    $tbl[$sub_menu] = 0;
                }
            }
        }
        $data['notif_pending'] = $tbl;
        $notif_menu = [];
        foreach ($tbl as $link => $jumlah) {
            $nama_menu = null;
            foreach ($tbl_notifikasi as $row) {
                if ($row->link == $link) {
                    $nama_menu = $row->nama_menu;
                    break;
                }
            }
            if ($nama_menu !== null) {
                if (!isset($notif_menu[$nama_menu])) {
                    $notif_menu[$nama_menu] = 0;
                }
                $notif_menu[$nama_menu] += (int)$jumlah;
            }
        }
        $data['notif_pending'] = $tbl;
        $data['notif_menu'] = $notif_menu;
        return $data;
    }

function get_pending_details($search = '', $jenis = '', $status = '', $menu_id = '')
    {
        // 1. Ambil daftar tabel notifikasi dari tbl_submenu
        $query = $this->db
            ->select('tbl_submenu.id_submenu, tbl_submenu.id_menu, tbl_submenu.link, tbl_submenu.nama_tbl, tbl_menu.nama_menu')
            ->from('tbl_submenu')
            ->join('tbl_menu', 'tbl_menu.id_menu = tbl_submenu.id_menu', 'left')
            ->where('tbl_submenu.nama_tbl IS NOT NULL', null, false)
            ->where('tbl_submenu.nama_tbl !=', '');

        $tbl_notifikasi = $query->get()->result();

        // 2. Ambil data session user yang sedang login
        $id = $this->session->userdata('id_user') ?? $this->session->userdata('user_id');

        $user_data = $this->db->select('fullname, username')
            ->from('tbl_user')
            ->where('id_user', $id)
            ->get()
            ->row();

        $current_username = strtolower(trim($user_data->username ?? $this->session->userdata('username') ?? ''));
        $user_fullname   = trim(strtolower($user_data->fullname ?? ''));

        // Daftar username yang berhak melihat mac_invoice & mac_peminjaman
        $allowed_usernames = ['bhakti', 'dwi'];
        $details = [];

        // 3. Looping setiap tabel notifikasi
        foreach ($tbl_notifikasi as $table) {
            $tabel_nama = trim($table->nama_tbl);
            $sub_menu   = $table->link;

            if (empty($tabel_nama) || !$this->db->table_exists($tabel_nama)) {
                continue;
            }

            // Filter pencarian jenis jika parameter dikirim
            if (!empty($jenis)) {
                $jenis_clean = strtolower(trim($jenis));
                $tabel_clean = strtolower($tabel_nama);
                if (stripos($tabel_clean, $jenis_clean) === false) {
                    continue;
                }
            }

            $fields = $this->db->list_fields($tabel_nama);
            $where  = [];

            // ------------------------------------------------------------------
            // ATURAN KHUSUS: MAC_INVOICE
            // ------------------------------------------------------------------
            if ($tabel_nama == 'mac_invoice') {
                if (in_array($current_username, $allowed_usernames)) {
                    if (in_array('app_status', $fields) && in_array('is_active', $fields)) {
                        $where[] = "(LOWER(TRIM(app_status)) = 'waiting' AND is_active = 1)";
                    }
                } else {
                    continue; // Lewati jika bukan bhakti/dwi
                }

            // ------------------------------------------------------------------
            // ATURAN KHUSUS: MAC_PEMINJAMAN
            // ------------------------------------------------------------------
            } elseif ($tabel_nama == 'mac_peminjaman') {
                if (in_array($current_username, $allowed_usernames)) {
                    if (in_array('app_status', $fields) && in_array('status', $fields)) {
                        $where[] = "(LOWER(TRIM(app_status)) = 'waiting' AND LOWER(TRIM(status)) = 'aktif')";
                    }
                } else {
                    continue; // Lewati jika bukan bhakti/dwi
                }

            // ------------------------------------------------------------------
            // ATURAN GENERAL (mac_reimbust, mac_prepayment, mac_deklarasi, dll)
            // ------------------------------------------------------------------
            } else {
                $escaped_user = $this->db->escape($user_fullname);

                // Level 1: Approval Pertama
                if (in_array('app_name', $fields) && in_array('app_status', $fields)) {
                    $where[] = "(LOWER(TRIM(app_name)) = " . $escaped_user . " AND LOWER(TRIM(app_status)) = 'waiting')";
                }

                // Level 2: Approval Kedua (Hanya muncul jika Approval 1 SUDAH 'approved')
                if (in_array('app2_name', $fields) && in_array('app2_status', $fields) && in_array('app_status', $fields)) {
                    $where[] = "(LOWER(TRIM(app2_name)) = " . $escaped_user . " AND LOWER(TRIM(app2_status)) = 'waiting' AND LOWER(TRIM(app_status)) = 'approved')";
                }

                // Level 3: Approval Ketiga
                if (in_array('app4_name', $fields) && in_array('app4_status', $fields) && in_array('app2_status', $fields)) {
                    $where[] = "(LOWER(TRIM(app4_name)) = " . $escaped_user . " AND LOWER(TRIM(app4_status)) = 'waiting' AND LOWER(TRIM(app2_status)) = 'approved')";
                }

                // Level Special: Approval HC
                if (in_array('app_hc_name', $fields) && in_array('app_hc_status', $fields) && in_array('app_status', $fields)) {
                    $where[] = "(LOWER(TRIM(app_hc_name)) = " . $escaped_user . " AND LOWER(TRIM(app_hc_status)) = 'waiting' AND LOWER(TRIM(app_status)) = 'approved')";
                }
            }

            // 4. Eksekusi Query jika ada klausa WHERE yang cocok
            if (!empty($where)) {
                
                // SELECT KOLOM ID & KODE
                $select_fields = 'id';
                if (in_array('kode', $fields)) {
                    $select_fields .= ', kode';
                } elseif (in_array('kode_reimbust', $fields)) {
                    $select_fields .= ', kode_reimbust AS kode';
                } elseif (in_array('kode_notifikasi', $fields)) {
                    $select_fields .= ', kode_notifikasi AS kode';
                } elseif (in_array('kode_prepayment', $fields)) {
                    $select_fields .= ', kode_prepayment AS kode';
                } elseif (in_array('kode_deklarasi', $fields)) {
                    $select_fields .= ', kode_deklarasi AS kode';
                } elseif (in_array('invoice_number', $fields)) {
                    $select_fields .= ', invoice_number AS kode';
                } elseif (in_array('kode_pinjam', $fields)) {
                    $select_fields .= ', kode_pinjam AS kode';
                } else {
                    $select_fields .= ', id AS kode';
                }

                // KHUSUS MAC_REIMBUST: Ambil kolom sifat_pelaporan jika ada
                if ($tabel_nama == 'mac_reimbust' && in_array('sifat_pelaporan', $fields)) {
                    $select_fields .= ', ' . $tabel_nama . '.sifat_pelaporan';
                }

                // SELECT KOLOM TANGGAL PENGAJUAN
                $tanggal_field = null;
                foreach (['created_at', 'tanggal_pengajuan', 'tanggal', 'created_date', 'date_created', 'tgl_pengajuan', 'input_date'] as $candidate) {
                    if (in_array($candidate, $fields)) {
                        $tanggal_field = $candidate;
                        break;
                    }
                }
                if ($tanggal_field === null) {
                    $tanggal_field = 'id';
                }

                $select_fields .= ', ' . $tabel_nama . '.' . $tanggal_field . ' AS tanggal_pengajuan';

                // ------------------------------------------------------------------
                // PERBAIKAN PENENTUAN KOLOM NAMA PENGAJU
                // ------------------------------------------------------------------
                
                // 1. Cari dulu apakah ada kolom ID User / ID Pengaju (Termasuk created_by jika bertipe ID)
                $join_field = null;
                $possible_user_fields = ['id_pengaju', 'id_user', 'user_id', 'id_karyawan', 'id_pemohon', 'id_pegawai', 'created_by'];
                
                foreach ($possible_user_fields as $ufield) {
                    if (in_array($ufield, $fields)) {
                        $join_field = $ufield;
                        break;
                    }
                }

                // 2. Cari kolom yang murni teks nama jika bukan ID
                $direct_name_field = null;
                if (!$join_field) {
                    $possible_direct_fields = ['nama_pengaju', 'nama_karyawan', 'nama_user', 'nama_pemohon'];
                    foreach ($possible_direct_fields as $dfield) {
                        if (in_array($dfield, $fields)) {
                            $direct_name_field = $dfield;
                            break;
                        }
                    }
                }

                // Pemasangan SELECT & JOIN
                if ($join_field) {
                    // Select nama dari tbl_data_user, dan juga ambil nilai mentah dari kolom tabel terkait sebagai cadangan
                    $select_fields .= ', tbl_data_user.name AS nama_pengaju_join, ' . $tabel_nama . '.' . $join_field . ' AS raw_pengaju';
                    $this->db->select($select_fields)
                            ->from($tabel_nama)
                            ->join('tbl_data_user', $tabel_nama . '.' . $join_field . ' = tbl_data_user.id_user', 'left');
                } elseif ($direct_name_field) {
                    $select_fields .= ', ' . $tabel_nama . '.' . $direct_name_field . ' AS nama_pengaju_direct';
                    $this->db->select($select_fields)
                            ->from($tabel_nama);
                } else {
                    $select_fields .= ', "Pengaju" AS nama_pengaju_direct';
                    $this->db->select($select_fields)
                            ->from($tabel_nama);
                }

                // Filter WHERE gabungan
                $this->db->where('(' . implode(' OR ', $where) . ')', null, false);

                $query_result = $this->db->get();
                $results = $query_result->result_array();

                // Mapping hasil ke array response
                foreach ($results as $row) {
                    $raw_nama = '';

                    if (isset($row['nama_pengaju_join']) && !empty($row['nama_pengaju_join'])) {
                        // Berhasil JOIN ke tbl_data_user
                        $raw_nama = $row['nama_pengaju_join'];
                    } elseif (isset($row['raw_pengaju']) && !is_numeric($row['raw_pengaju']) && !empty($row['raw_pengaju'])) {
                        // Jika JOIN gagal tapi isi kolomnya ternyata teks nama (bukan ID angka) seperti mac_invoice
                        $raw_nama = $row['raw_pengaju'];
                    } elseif (isset($row['nama_pengaju_direct']) && !empty($row['nama_pengaju_direct'])) {
                        $raw_nama = $row['nama_pengaju_direct'];
                    } else {
                        $raw_nama = 'Pengaju';
                    }

                    // Formatting nama pengaju agar KAPITAL di setiap kata
                    $formatted_nama = ucwords(strtolower(trim($raw_nama)));

                    // KHUSUS MAC_REIMBUST: Jika ada sifat_pelaporan, gunakan nilai tersebut sebagai 'form'
                    $form_value = $tabel_nama;
                    if ($tabel_nama == 'mac_reimbust' && !empty($row['sifat_pelaporan'])) {
                        $form_value = $row['sifat_pelaporan'];
                    }

                    $details[] = [
                        'nama_pengaju'      => $formatted_nama,
                        'tanggal_pengajuan' => $row['tanggal_pengajuan'] ?? '',
                        'form'              => $form_value,
                        'kode'              => $row['kode'] ?? $row['id'],
                        'link'              => base_url($sub_menu . '/read_form/' . $row['id'])
                    ];
                }
            }
        }

        return $details;
    }
}
