<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Antrean extends \Restserver\Libraries\REST_Controller {
    public function __construct() {
        parent::__construct();
    }

    private function tomiliseconds($tanggal)
    {
        date_default_timezone_set('Asia/Jakarta');
        return strtotime($tanggal) * 1000;
    }

    private function get_estimasi($kodepoli, $tanggalperiksa)
    {
        /* perhitungan estimasi disesuaikan sendiri dengan sistem antrian RS */
        date_default_timezone_set('Asia/Jakarta');
        $stamp = strtotime($tanggalperiksa);
        $time_in_ms = $stamp * 1000;
        return $time_in_ms;
    }

    function day_to_hari ($hariInggris) {
        switch ($hariInggris) {
          case 'Sunday':
            return 'Minggu';
          case 'Monday':
            return 'Senin';
          case 'Tuesday':
            return 'Selasa';
          case 'Wednesday':
            return 'Rabu';
          case 'Thursday':
            return 'Kamis';
          case 'Friday':
            return 'Jumat';
          case 'Saturday':
            return 'Sabtu';
          default:
            return 'hari tidak valid';
        }
      }
      

    /**
     * Get no Antrean API
     * --------------------
     * @param: "{
     * nomorkartu"": ""0000 0000 00123"",
     * nik"": ""3506 1413 0895 0002"",
     * nohp"": ""081123456778"",
     * tanggalperiksa"": ""2019-12-11"",
     * kodepoli"": ""001"",
     * nomorreferensi"": ""0001R0040116A000001"",
     * jenisreferensi"": 1,
     * jenisrequest"": 2,
     * polieksekutif"": 0
     * }"
     * --------------------------
     * @method : POST
     * @link: antrean/getantrean
     */
    public function create_post() {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            # Create
            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = json_decode(file_get_contents('php://input'),true); 
            $_POST = $this->security->xss_clean($_POST);
            # Form Validation
            $this->form_validation->set_rules('nomorkartu', 'Nomorkartu', 'trim|max_length[13]');
            $this->form_validation->set_rules('nik', 'Nik', 'trim|required|max_length[16]');
            $this->form_validation->set_rules('nohp', 'nohp', 'trim|required|max_length[12]');
            $this->form_validation->set_rules('kodepoli', 'kodepoli', 'trim|required');
            $this->form_validation->set_rules('norm', 'norm', 'trim|required');
            $this->form_validation->set_rules('tanggalperiksa', 'tanggalperiksa', 'trim|required');
            $this->form_validation->set_rules('kodedokter', 'kodedokter', 'trim|required');
            $this->form_validation->set_rules('jampraktek', 'jampraktek', 'trim|required');
            $this->form_validation->set_rules('jeniskunjungan', 'jeniskunjungan', 'trim|required');
            $this->form_validation->set_rules('nomorreferensi', 'nomorreferensi', 'trim');
            if ($this->form_validation->run() == FALSE){
                // Form Validation Errors
                // var_dump(count($this->form_validation->error_array()));
                
                if (count($this->form_validation->error_array()) == 1){
                    if(@$this->form_validation->error_array()['nomorkartu']){
                        $messages = "Nomor kartu bukan angka atau kurang dari 13 digit";
                    }
                    if(@$this->form_validation->error_array()['tanggalperiksa']){
                        $messages = "Anda belum memilih tanggal periksa";
                    }
                    if(@$this->form_validation->error_array()['nik']){
                        $messages = "NIK bukan angka atau kurang dari 16 digit";
                    }
                    if(@$this->form_validation->error_array()['kodepoli']){
                        $messages = "Data tidak lengkap";
                    }
                }else{
                    $messages = "Data tidak lengkap";
                }
                $return_metadata = [
                    'message' => $messages,
                    'code' => 201
                ];
                $message = [
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_CREATED);
                
            } else {
                $this->load->model('Antrean_model', 'AntreanModel');
                // Validasi tambahan
                $kirim = 'TRUE';
                if (@$this->AntreanModel->checkreferensi($this->input->post('nomorreferensi'))) { 
                    $messages = "No.Referensi / No. Rujukan Sudah Pernah Digunakan!";
                    $kirim = 'FALSE';
                }else if (!DateTime::createFromFormat('Y-m-d', $this->input->post('tanggalperiksa', TRUE))) { 
                    $messages = "Format Tanggal Periksa Harus YYYY-MM-DD";
                    $kirim = 'FALSE';
                }else if(strtotime($this->input->post('tanggalperiksa', TRUE)) < strtotime(date('Y-m-d'))){
                    $messages = "Tanggal Periksa Kurang Dari Tanggal Hari ini";
                    $kirim = 'FALSE';
                }else if ($this->input->post('kodepoli')) {
                    $kodepoli = $this->input->post('kodepoli');
                    $kodedokter = $this->input->post('kodedokter');
                    $jampraktek = $this->input->post('jampraktek');
                    $day = date('l', strtotime($this->input->post('tanggalperiksa')));
                    $day = $this->day_to_hari($day);

                    if (@$this->AntreanModel->checkreferensi_nik_day_poli($this->input->post('nik'),$this->input->post('tanggalperiksa'),$kodepoli,$kodedokter)) { 
                        $messages = "Nomor Antrean Hanya Dapat Diambil 1 kali Pada Tanggal Yang Sama";
                        $kirim = 'FALSE';
                    } else {
                        if(@$this->AntreanModel->get_nm_poli($kodepoli)){
                            $poli = @$this->AntreanModel->get_jadwal_poli($kodepoli);
                            $dokter = @$this->AntreanModel->get_dokter($kodedokter);
                            $jadwal = @$this->AntreanModel->get_jadwal_dokter_poli($poli->id_mst_unit_kerja_sub,$day,$kodedokter);
                            // var_dump($jadwal);
                            if ($jadwal) {
                                if ($jadwal->open == 0) {
                                    $messages = "Pendaftaran ke Poli Ini Sedang Tutup";
                                    $kirim = 'FALSE';
                                } else {
                                    $jam_buka_db = (int)$jadwal->time_start;
                                    $jam_tutup_db = (int)$jadwal->time_end;
                                    $jampraktek = explode('-', $jampraktek);
                                    $jam_buka = (int)$jampraktek[0];
                                    $jam_tutup = (int)$jampraktek[1];
                                    // echo 'jam buka db '.$jam_buka_db.'<br>';
                                    // echo 'jam tutup db '.$jam_tutup_db.'<br>';
                                    // echo 'jam buka '.$jam_buka.'<br>';
                                    // echo 'jam tutup '.$jam_tutup.'<br>';
                                    if ($jam_tutup > $jam_tutup_db) {
                                        $messages = "Pendaftaran Ke Poli ".$poli->nm_poli_bpjs." Sudah Tutup Jam ".$jadwal->time_end;
                                        $kirim = 'FALSE';
                                    } else if ($jam_buka_db > $jam_buka) {
                                        $messages = "Pendaftaran Ke Poli ".$poli->nm_poli_bpjs." Buka Jam ".$jadwal->time_start;
                                        $kirim = 'FALSE';
                                    } else {
                                        $kirim = 'TRUE';
                                    }
                                }
                            } else {
                                $kirim = 'FALSE';
                                $messages = "Jadwal Dokter ".$dokter->nm_mst_pegawai_lengkap." Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya";
                            }
                        }else{
                            $messages = "Kode Poli Belum di Maping!";
                            $kirim = 'FALSE';
                        }
                    }

                }
                // die();
                if($kirim == 'TRUE'){
                    // echo $kirim;
                    // die();
                    // Load Antrean Model
                    
                    // - NOMOR ANTRIAN
                    // - JENIS ANTRIAN (PENDAFTARAN [1] / POLI [2])
                    // - ESTIMASI DILAYANI (TIMESTAMP DLM MILISECOND)
                    // - KODEBOOKING
                    // - NAMADOKTER (OPSIONAL)

                    $angkaantrean = $this->AntreanModel->gen_antrean($this->input->post('kodepoli'),$this->input->post('kodedokter'),$jadwal->id_trx_schedule_item);
                    $nomorantrean = $jadwal->kodeantrean.'-'.$angkaantrean;
                    $kodebooking = $this->AntreanModel->gen_booking($this->input->post('kodepoli'));
                    $jenisantrean = 1;
                    // $estimasidilayani = 1576040301000;
                    $namapoli = $this->AntreanModel->get_nm_poli($this->input->post('kodepoli'));
                    //check no bpjs
                    @$no_rm = $this->AntreanModel->check_no_rm_bynomorkartu_bride($this->input->post('nomorkartu'))[0];
                    //check no bpjs tidak ada
                    if(!$no_rm){
                        //check nik
                        @$no_rm = $this->AntreanModel->check_no_rm_bynik_bride($this->input->post('nik'))[0];
                        // $idmstpasien = 1;
                        //check nik tidak ada
                        if(!$no_rm){

                            $return_metadata = [
                                'message' => 'Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru',
                                'code' => 202
                            ];
                            $message = [
                                'metadata' => $return_metadata,
                            ];
                            $this->response($message, REST_Controller::HTTP_ACCEPTED);
                            return;
                            //create rm baru dan data pasien
                            // $no_rm = $this->AntreanModel->gen_no_rm_bride();
                            // $data_pasien = [
                            //     'no_rm'=> $no_rm,
                            //     'no_peserta'=> $this->input->post('nomorkartu', TRUE),
                            //     'no_ktp'=> $this->input->post('nik', TRUE),
                            //     'no_telepon'=> $this->input->post('nohp', TRUE)
                            // ];
                            // $idmstpasien = $this->AntreanModel->create_pasien($data_pasien);
                        }else{
                            //get rm lama
                            // var_dump($no_rm);die();
                            $no_rm = $no_rm->no_rm;
                            // $idmstpasien = 1;
                        }
                    }else{
                        //get rm lama
                        $no_rm = $no_rm->no_rm;
                        // $idmstpasien = 1;
                    }
                    // var_dump($no_rm);die();
                    $namadokter = $dokter->nm_mst_pegawai_lengkap;
                    // $dateestimate = date('Y-m-d H:i:s', strtotime($this->input->post('tanggalperiksa', TRUE).'07:00:00') + (60 * 60));
                    // // $dateestimate = date('Y-m-d H:i:s', strtotime($this->input->post('tanggalperiksa', TRUE).'07:00:00') + (60 * 60));
                    // // $dateestimate = date('Y-m-d H:i:s', time() + (60 * 60));
                    // $stamp = strtotime($dateestimate); // get unix timestamp
                    // $estimasidilayani = $stamp*1000;
                    $estimasidilayani = $this->tomiliseconds($this->input->post('tanggalperiksa', TRUE));
                    if($no_rm){
                        $insert_data = [
                            'nomorkartu' => $this->input->post('nomorkartu', TRUE),
                            'nik' => $this->input->post('nik', TRUE),
                            'notelp' => $this->input->post('nohp', TRUE),
                            'tanggalperiksa' => $this->input->post('tanggalperiksa', TRUE),
                            'jampraktek' => $this->input->post('jampraktek', TRUE),
                            'kodepoli' => $this->input->post('kodepoli', TRUE),
                            'kodedokter' => $this->input->post('kodedokter', TRUE),
                            'id_trx_schedule_item' => $jadwal->id_trx_schedule_item,
                            'nomorreferensi' => $this->input->post('nomorreferensi', TRUE),
                            'jenisreferensi' => $this->input->post('jenisreferensi', TRUE),
                            'jeniskunjungan' => $this->input->post('jeniskunjungan', TRUE),
                            'nomorantrean'=> $nomorantrean,
                            'kodebooking'=> $kodebooking,
                            'jenisantrean'=> $jenisantrean,
                            'estimasidilayani'=> $this->get_estimasi($this->input->post('kodepoli', TRUE),$this->input->post('tanggalperiksa', TRUE)),
                            'namapoli'=> $namapoli,
                            'namadokter'=> $namadokter,
                            'user_added' => $is_valid_token['data']->id
                        ];
        
                        $insert_data_bride = [
                            'kodebooking'=> $kodebooking,
                            'nomorantrean'=> $nomorantrean,
                            'kd_poli_bpjs'=> $this->input->post('kodepoli', TRUE),
                            'id_trx_schedule_item'=> $jadwal->id_trx_schedule_item,
                            'kodedokter'=> $this->input->post('kodedokter', TRUE),
                            'no_rm'=> $no_rm,
                            'date_booking'=> date('Y-m-d H:i:s'),
                            'date_update'=> date('Y-m-d H:i:s')
                        ];


                        // get quota sisa
                        $quota_jkn = $this->AntreanModel->get_quota_by_id_trx_schedule_item($jadwal->id_trx_schedule_item);
                        $quota_jkn = count($quota_jkn);
                        // get quota sisa

                        // var_dump($insert_data,$insert_data_bride);
                        // die();
                        // // Insert Antrean
                        // $this->db->trans_start();
                        // $antreans = $this->AntreanModel->create_antrean($insert_data);
                        // $brides = $this->AntreanModel->create_bride($insert_data_bride);
                        // $this->db->trans_complete();
                        $antreans = 1;$brides = 1;
                    }else{
                        $return_metadata = [
                            'message' => "Retry",
                            'code' => 500
                        ];
                        $message = [
                            'response' => FALSE,
                            'metadata' => $return_metadata
                        ];
                        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                        return;
                    }

                    if ($antreans > 0 AND !empty($antreans)){
                        // Success
                        // var_dump($antreans);

                        $return_data = [
                            'nomorantrean'=> $nomorantrean,
                            'angkaantrean'=> (int) $angkaantrean,
                            'kodebooking'=> $kodebooking,
                            'norm'=> $no_rm,
                            'namapoli'=> $namapoli,
                            'namadokter'=> $namadokter,
                            'estimasidilayani'=> $this->get_estimasi($this->input->post('kodepoli', TRUE),$this->input->post('tanggalperiksa', TRUE)),
                            'sisakuotajkn'=> (int) $jadwal->quota_jkn-$quota_jkn,
                            'kuotajkn'=> (int) $jadwal->quota_jkn,
                            'sisakuotanonjkn'=> (int) $jadwal->quota_all,
                            'kuotanonjkn'=> (int) $jadwal->quota_all,
                            'keterangan'=> 'Peserta harap 60 menit lebih awal guna pencatatan administrasi.',
                        ];
        
                        $return_metadata = [
                            'message' => "Ok",
                            'code' => 200
                        ];
        
                        // Login Success
                        $message = [
                            'response' => $return_data,
                            'metadata' => $return_metadata,
                        ];
                        $this->response($message, REST_Controller::HTTP_OK);
                        return;
                    } else {
                        // Error
                        $return_metadata = [
                            'message' => "Username atau Password yang anda masukan salah",
                            'code' => 204
                        ];

                        // Success
                        $message = [
                            'metadata' => $return_metadata,
                        ];
                        $this->response($message, REST_Controller::HTTP_CREATED);
                    }
                }else{
                    $return_metadata = [
                        'message' => $messages,
                        'code' => 201
                    ];
                    $message = [
                        'metadata' => $return_metadata,
                    ];
                    $this->response($message, REST_Controller::HTTP_CREATED);
                    
                }
            }

        } else {
            $this->response(['metadata'=>array('message' => $is_valid_token['message'], 'code'=> 201) ], REST_Controller::HTTP_CREATED);
        }
    }

    /**
     * Get no Antrean API
     * --------------------
     * @param: "{
     * kodepoli"": "INT",
     * tgl"": ""2023-09-13"",
     * }"
     * --------------------------
     * @method : POST
     * @link: antrean/status
     */
    public function status_post($kodepoli = 0, $tanggal = 0) {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        $kodepoli   = $this->security->xss_clean($kodepoli);
        $tanggal    = $this->security->xss_clean($tanggal);
        
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            # Create
            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = json_decode(file_get_contents('php://input'),true); 
            $_POST = $this->security->xss_clean($_POST);
            # Form Validation
            $this->form_validation->set_rules('kodepoli', 'kodepoli', 'trim|required');
            $this->form_validation->set_rules('kodedokter', 'kodedokter', 'trim|required');
            $this->form_validation->set_rules('tanggalperiksa', 'tanggalperiksa', 'trim|required');
            $this->form_validation->set_rules('jampraktek', 'jampraktek', 'trim|required');
            if ($this->form_validation->run() == FALSE){
                // Form Validation Errors
                // var_dump(count($this->form_validation->error_array()));
                
                if (count($this->form_validation->error_array()) == 1){
                    if(@$this->form_validation->error_array()['tanggalperiksa']){
                        $messages = "Anda belum memilih tanggal periksa";
                    }
                    if(@$this->form_validation->error_array()['kodepoli']){
                        $messages = "Anda belum memilih poli";
                    }
                    if(@$this->form_validation->error_array()['jampraktek']){
                        $messages = "Anda belum memilih jam praktek";
                    }
                    if(@$this->form_validation->error_array()['kodedokter']){
                        $messages = "Anda belum memilih dokter";
                    }
                }else{
                    $messages = "Data tidak lengkap";
                }
                $return_metadata = [
                    'message' => $messages,
                    'code' => 201
                ];
                $message = [
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_CREATED);
            } else {
                $this->load->model('Antrean_model', 'AntreanModel');
                // Validasi Tanggal Periksa start
                if (!DateTime::createFromFormat('Y-m-d', $this->input->post('tanggalperiksa', TRUE))) { 
                    $messages = "Format Tanggal Tidak Sesusai, format yang benar adalah yyyy-mm-dd";
                    $return_metadata = ['message' => $messages,'code' => 201];
                    $message = ['metadata' => $return_metadata];
                    $this->response($message, REST_Controller::HTTP_CREATED);
                    return;
                } else if(strtotime($this->input->post('tanggalperiksa', TRUE)) < strtotime(date('Y-m-d'))){
                    $messages = "Tanggal Periksa Tidak Berlaku";
                    $return_metadata = ['message' => $messages,'code' => 201];
                    $message = ['metadata' => $return_metadata];
                    $this->response($message, REST_Controller::HTTP_CREATED);
                    return;
                // Validasi Tanggal Periksa end
                // Validasi Tanggal Periksa kode poli start
                } else if($this->input->post('kodepoli')){
                    $kodepoli = $this->input->post('kodepoli');
                    $kodedokter = $this->input->post('kodedokter');
                    $day = date('l', strtotime($this->input->post('tanggalperiksa')));
                    $day = $this->day_to_hari($day);
                    if(@$this->AntreanModel->get_nm_poli($kodepoli)){
                        $poli = @$this->AntreanModel->get_jadwal_poli($kodepoli);
                        $jadwal = @$this->AntreanModel->get_jadwal_dokter_poli($poli->id_mst_unit_kerja_sub,$day,$kodedokter);
                        if (!$jadwal) {
                            $messages = "Tidak ada jadwal praktek";
                            $return_metadata = ['message' => $messages,'code' => 201];
                            $message = ['metadata' => $return_metadata];
                            $this->response($message, REST_Controller::HTTP_CREATED);
                            return;
                        }
                    }else{
                        $messages = "Poli Tidak Ditemukan";
                        $return_metadata = ['message' => $messages,'code' => 201];
                        $message = ['metadata' => $return_metadata];
                        $this->response($message, REST_Controller::HTTP_CREATED);
                        return;
                    }
                }
                // Validasi Tanggal Periksa kode poli end
                // var_dump($jadwal);
                $return_data = [
                    "namapoli" => $poli->nm_poli_bpjs,
                    "namadokter" => $jadwal->nm_mst_pegawai_lengkap,
                    "totalantrean" => $jadwal->quota_jkn,
                    "sisaantrean" => $jadwal->quota_jkn,
                    "antreanpanggil" => 0,
                    "sisakuotajkn" => $jadwal->quota_jkn,
                    "kuotajkn" => $jadwal->quota_jkn,
                    "sisakuotanonjkn" => $jadwal->quota_all,
                    "kuotanonjkn" => $jadwal->quota_all,
                    "keterangan" => ""
                ];

                $return_metadata = [
                    'message' => "Ok",
                    'code' => 200
                ];

                // Login Success
                $message = [
                    'response' => $return_data,
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
                return;
                $return_metadata = [
                    'message' => $messages,
                    'code' => 201
                ];
                $message = [
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_CREATED);
                
            }
        } else {
            $this->response(['metadata'=>array('message' => $is_valid_token['message'], 'code'=> 201) ], REST_Controller::HTTP_CREATED);
        }
    }

    /**
     * Get no Antrean API
     * --------------------
     * @param: "{
     * kodebooking"": "INT",
     * }"
     * --------------------------
     * @method : POST
     * @link: antrean/sisa
     */
    public function sisa_post() {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        
        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            # Create
            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = json_decode(file_get_contents('php://input'),true); 
            $_POST = $this->security->xss_clean($_POST);
            # Form Validation
            $this->form_validation->set_rules('kodebooking', 'kodebooking', 'trim|required');
            if ($this->form_validation->run() == FALSE){
                // Form Validation Errors
                // var_dump(count($this->form_validation->error_array()));
                
                if (count($this->form_validation->error_array()) == 1){
                    if(@$this->form_validation->error_array()['kodebooking']){
                        $messages = "Kode Booking Tidak Boleh Kosong";
                    }
                }else{
                    $messages = "Data tidak lengkap";
                }
                $return_metadata = [
                    'message' => $messages,
                    'code' => 201
                ];
                $message = [
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_CREATED);
            } else {
                $this->load->model('Antrean_model', 'AntreanModel');
                // Validasi Tanggal Periksa start
                if (!DateTime::createFromFormat('Y-m-d', $this->input->post('tanggalperiksa', TRUE))) { 
                    $messages = "Format Tanggal Tidak Sesusai, format yang benar adalah yyyy-mm-dd";
                    $return_metadata = ['message' => $messages,'code' => 201];
                    $message = ['metadata' => $return_metadata];
                    $this->response($message, REST_Controller::HTTP_CREATED);
                    return;
                } else if(strtotime($this->input->post('tanggalperiksa', TRUE)) < strtotime(date('Y-m-d'))){
                    $messages = "Tanggal Periksa Tidak Berlaku";
                    $return_metadata = ['message' => $messages,'code' => 201];
                    $message = ['metadata' => $return_metadata];
                    $this->response($message, REST_Controller::HTTP_CREATED);
                    return;
                // Validasi Tanggal Periksa end
                // Validasi Tanggal Periksa kode poli start
                } else if($this->input->post('kodepoli')){
                    $kodepoli = $this->input->post('kodepoli');
                    $kodedokter = $this->input->post('kodedokter');
                    $day = date('l', strtotime($this->input->post('tanggalperiksa')));
                    $day = $this->day_to_hari($day);
                    if(@$this->AntreanModel->get_nm_poli($kodepoli)){
                        $poli = @$this->AntreanModel->get_jadwal_poli($kodepoli);
                        $jadwal = @$this->AntreanModel->get_jadwal_dokter_poli($poli->id_mst_unit_kerja_sub,$day,$kodedokter);
                        if (!$jadwal) {
                            $messages = "Tidak ada jadwal praktek";
                            $return_metadata = ['message' => $messages,'code' => 201];
                            $message = ['metadata' => $return_metadata];
                            $this->response($message, REST_Controller::HTTP_CREATED);
                            return;
                        }
                    }else{
                        $messages = "Poli Tidak Ditemukan";
                        $return_metadata = ['message' => $messages,'code' => 201];
                        $message = ['metadata' => $return_metadata];
                        $this->response($message, REST_Controller::HTTP_CREATED);
                        return;
                    }
                }
                // Validasi Tanggal Periksa kode poli end
                // var_dump($jadwal);
                $return_data = [
                    "nomorantrean" => $poli->nm_poli_bpjs,
                    "namapoli" => $poli->nm_poli_bpjs,
                    "namadokter" => $jadwal->nm_mst_pegawai_lengkap,
                    "sisaantrean" => $jadwal->quota_jkn,
                    "antreanpanggil" => "",
                    "waktutunggu" => "",
                    "keterangan" => ""
                ];

                $return_metadata = [
                    'message' => "Ok",
                    'code' => 200
                ];

                // Login Success
                $message = [
                    'response' => $return_data,
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
                return;
            }
        } else {
            $this->response(['metadata'=>array('message' => $is_valid_token['message'], 'code'=> 201) ], REST_Controller::HTTP_CREATED);
        }
    }

    // public function getRekap_post() {
    //     header("Access-Control-Allow-Origin: *");
    
    //     // Load Authorization Token Library
    //     $this->load->library('Authorization_Token');

    //     /**
    //      * User Token Validation
    //      */
    //     $is_valid_token = $this->authorization_token->validateToken();
    //     if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
    //         # Create a User Article

    //         # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
    //         $_POST = json_decode(file_get_contents('php://input'),true); 
    //         $_POST = $this->security->xss_clean($_POST);
            
    //         # Form Validation
    //         $this->form_validation->set_rules('tanggalperiksa', 'tanggalperiksa', 'trim|required');
    //         $this->form_validation->set_rules('kodepoli', 'kodepoli', 'trim|required|max_length[3]');
    //         $this->form_validation->set_rules('polieksekutif', 'polieksekutif', 'trim|required|max_length[1]');
    //         if ($this->form_validation->run() == FALSE){
    //             // Form Validation Errors
    //             // var_dump(count($this->form_validation->error_array()));
    //             if (count($this->form_validation->error_array()) == 1){
    //                 if(@$this->form_validation->error_array()['tanggalperiksa']){
    //                     $messages = "Data tidak lengkap";
    //                 }
    //                 if(@$this->form_validation->error_array()['kodepoli']){
    //                     $messages = "kode poli tidak ditemukan";
    //                 }
    //             }else{
    //                 $messages = "Data tidak lengkap";
    //             }
    //             $return_metadata = [
    //                 'message' => $messages,
    //                 'code' => 204
    //             ];
    //             $message = [
    //                 'metadata' => $return_metadata,
    //             ];
    //             $this->response($message, REST_Controller::HTTP_CREATED);
    //         } else {
    //             // Load Antrean Model
    //             $this->load->model('Antrean_model', 'AntreanModel');
                
    //             $kirim = 'TRUE';
    //             if (!DateTime::createFromFormat('Y-m-d', $this->input->post('tanggalperiksa', TRUE))) { 
    //                 $messages = "Format Tanggal Periksa Harus YYYY-MM-DD";
    //                 $kirim = 'FALSE';
    //             }
    //             if($kirim == 'TRUE'){
    //                 $data_rekap_total = $this->AntreanModel->get_rekap_antrean($this->input->post('tanggalperiksa'),$this->input->post('kodepoli'),$this->input->post('polieksekutif'));
    //                 $data_rekap_terlayani = $this->AntreanModel->get_rekap_terlayani($this->input->post('tanggalperiksa'),$this->input->post('kodepoli'),$this->input->post('polieksekutif'));
    //                 $data_rekap_lastupdate = $this->AntreanModel->get_rekap_lastupdate($this->input->post('tanggalperiksa'),$this->input->post('kodepoli'),$this->input->post('polieksekutif'));

    //                 if ($data_rekap_total > 0 AND !empty($data_rekap_total)){
    //                     // Success
    //                     $totalantrean = $data_rekap_total[0]['totalantrean'];
    //                     $terlayani = $data_rekap_terlayani[0]['terlayani'];
    //                     $namapoli = $data_rekap_lastupdate[0]['nm_poli_bpjs'];
    //                     $stamp = strtotime($data_rekap_lastupdate[0]['lastupdate']); // get unix timestamp
    //                     $time_in_ms = $stamp*1000;
    //                     $return_data = [
    //                         "namapoli" => $namapoli,
    //                         "totalantrean" => $totalantrean,
    //                         "jumlahterlayani" => $terlayani,
    //                         "lastupdate" => $time_in_ms
    //                     ];
        
    //                     $return_metadata = [
    //                         'message' => "Ok",
    //                         'code' => 200
    //                     ];
        
    //                     // Login Success
    //                     $message = [
    //                         'response' => $return_data,
    //                         'metadata' => $return_metadata,
    //                     ];
    //                     $this->response($message, REST_Controller::HTTP_OK);
    //                 } else {
    //                     // Error
    //                     $return_metadata = [
    //                         'message' => "Retry",
    //                         'code' => 500
    //                     ];
    //                     $message = [
    //                         'response' => FALSE,
    //                         'metadata' => $return_metadata
    //                     ];
    //                     $this->response($message, REST_Controller::HTTP_NOT_FOUND);
    //                 }
    //             }else{
    //                 $return_metadata = [
    //                     'message' => $messages,
    //                     'code' => 204
    //                 ];
    //                 $message = [
    //                     'metadata' => $return_metadata,
    //                 ];
    //                 $this->response($message, REST_Controller::HTTP_CREATED);
    //             }
    //         }

    //     } else {
    //         $this->response(['message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
    //     }
    // }

    // /**
    //  * Delete an Article with API
    //  * @method: DELETE
    //  */
    // public function deleteArticle_delete($id)
    // {
    //     header("Access-Control-Allow-Origin: *");
    
    //     // Load Authorization Token Library
    //     $this->load->library('Authorization_Token');

    //     /**
    //      * User Token Validation
    //      */
    //     $is_valid_token = $this->authorization_token->validateToken();
    //     if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
    //     {
    //         # Delete a User Article

    //         # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
    //         $id = $this->security->xss_clean($id);
            
    //         if (empty($id) AND !is_numeric($id))
    //         {
    //             $this->response(['status' => FALSE, 'message' => 'Invalid Article ID' ], REST_Controller::HTTP_NOT_FOUND);
    //         }
    //         else
    //         {
    //             // Load Article Model
    //             $this->load->model('article_model', 'ArticleModel');

    //             $delete_article = [
    //                 'id' => $id,
    //                 'user_id' => $is_valid_token['data']->id,
    //             ];

    //             // Delete an Article
    //             $output = $this->ArticleModel->delete_article($delete_article);

    //             if ($output > 0 AND !empty($output))
    //             {
    //                 // Success
    //                 $message = [
    //                     'status' => true,
    //                     'message' => "Article Deleted"
    //                 ];
    //                 $this->response($message, REST_Controller::HTTP_OK);
    //             } else
    //             {
    //                 // Error
    //                 $message = [
    //                     'status' => FALSE,
    //                     'message' => "Article not delete"
    //                 ];
    //                 $this->response($message, REST_Controller::HTTP_NOT_FOUND);
    //             }
    //         }

    //     } else {
    //         $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
    //     }
    // }

    // /**
    //  * Update an Article with API
    //  * @method: PUT
    //  */
    // public function updateArticle_put()
    // {
    //     header("Access-Control-Allow-Origin: *");
    
    //     // Load Authorization Token Library
    //     $this->load->library('Authorization_Token');

    //     /**
    //      * User Token Validation
    //      */
    //     $is_valid_token = $this->authorization_token->validateToken();
    //     if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
    //     {
    //         # Update a User Article


    //         # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
    //         $_POST = json_decode($this->security->xss_clean(file_get_contents("php://input")), true);
            
    //         $this->form_validation->set_data([
    //             'id' => $this->input->post('id', TRUE),
    //             'title' => $this->input->post('title', TRUE),
    //             'description' => $this->input->post('description', TRUE),
    //         ]);
            
    //         # Form Validation
    //         $this->form_validation->set_rules('id', 'Article ID', 'trim|required|numeric');
    //         $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
    //         $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[200]');
    //         if ($this->form_validation->run() == FALSE)
    //         {
    //             // Form Validation Errors
    //             $message = array(
    //                 'status' => false,
    //                 'error' => $this->form_validation->error_array(),
    //                 'message' => validation_errors()
    //             );

    //             $this->response($message, REST_Controller::HTTP_NOT_FOUND);
    //         }
    //         else
    //         {
    //             // Load Article Model
    //             $this->load->model('article_model', 'ArticleModel');

    //             $update_data = [
    //                 'user_id' => $is_valid_token['data']->id,
    //                 'id' => $this->input->post('id', TRUE),
    //                 'title' => $this->input->post('title', TRUE),
    //                 'description' => $this->input->post('description', TRUE),
    //             ];

    //             // Update an Article
    //             $output = $this->ArticleModel->update_article($update_data);

    //             if ($output > 0 AND !empty($output))
    //             {
    //                 // Success
    //                 $message = [
    //                     'status' => true,
    //                     'message' => "Article Updated"
    //                 ];
    //                 $this->response($message, REST_Controller::HTTP_OK);
    //             } else
    //             {
    //                 // Error
    //                 $message = [
    //                     'status' => FALSE,
    //                     'message' => "Article not update"
    //                 ];
    //                 $this->response($message, REST_Controller::HTTP_NOT_FOUND);
    //             }
    //         }

    //     } else {
    //         $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
    //     }
    // }
}