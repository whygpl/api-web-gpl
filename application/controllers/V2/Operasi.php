<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Operasi extends \Restserver\Libraries\REST_Controller {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get no Operasi API
     * --------------------
     * @param: "{
     * nopeserta"": ""0000 0000 00123"",
     * }"
     * --------------------------
     * @method : POST
     * @link: jkn/operasi/getbooklist
     */

    public function getBookingList_post() {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            # Create a User Article

            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = json_decode(file_get_contents('php://input'),true); 
            $_POST = $this->security->xss_clean($_POST);
            
            # Form Validation
            $this->form_validation->set_rules('nopeserta', 'Nopeserta', 'trim|required|max_length[13]|min_length[13]');
            if ($this->form_validation->run() == FALSE){
                // Form Validation Errors
                if(@$this->form_validation->error_array()['nopeserta'] == 'The Nopeserta field must be at least 13 characters in length.'){
                    $messages = "Nomor kartu bukan angka atau kurang dari 13 digit";
                }else{
                    $messages = "Data tidak lengkap";
                }
                $return_metadata = [
                    'message' => $messages,
                    'code' => 204
                ];
                $message = [
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_NO_CONTENT);
            } else {
                // Load Antrean Model
                $this->load->model('Antrean_model', 'AntreanModel');
                
                // $insert_data_bride = [
                //     'kodebooking'=> $kodebooking,
                //     'nomorantrean'=> $nomorantrean,
                //     'kd_poli_bpjs'=> $this->input->post('kodepoli', TRUE),
                //     'no_rm'=> $no_rm,
                //     'date_booking'=> date('Y-m-d H:i:s'),
                //     'date_update'=> date('Y-m-d H:i:s')
                // ];
                
                // // // Insert Antrean
                // $this->db->trans_start();
                // $antreans = $this->AntreanModel->create_antrean($insert_data);
                // $brides = $this->AntreanModel->create_bride($insert_data_bride);
                // $this->db->trans_complete();

                // if ($antreans > 0 AND !empty($antreans)){
                    // Success
                    // var_dump($antreans);
                    $list = array(
                        [
                            'kodebooking'=>'OP2020BED001',
                            'tanggaloperasi'=>'2020-05-02',
                            'jenistindakan'=>'Op ringan',
                            'kodepoli'=>'BED',
                            'namapoli'=>'BEDAH',
                            'terlaksana'=>'0'
                        ],
                        [
                            'kodebooking'=>'OP2020BED001',
                            'tanggaloperasi'=>'2020-05-02',
                            'jenistindakan'=>'Op ringan',
                            'kodepoli'=>'BED',
                            'namapoli'=>'BEDAH',
                            'terlaksana'=>'0'
                        ]);

                    $return_data = [
                        'list'=> $list
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
                // } else {
                // //     // Error
                //     $return_metadata = [
                //         'message' => "Retry",
                //         'code' => 500
                //     ];
                //     $message = [
                //         'status' => FALSE,
                //         'response' => FALSE,
                //         'metadata' => $return_metadata
                //     ];
                //     $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                // }
            }

        } else {
            $this->response(['message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function getJadwalList_post() {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            # Create a User Article

            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = json_decode(file_get_contents('php://input'),true); 
            $_POST = $this->security->xss_clean($_POST);
            
            # Form Validation
            $this->form_validation->set_rules('tanggalawal', 'tanggalawal', 'trim|required');
            $this->form_validation->set_rules('tanggalakhir', 'tanggalakhir', 'trim|required');
            if ($this->form_validation->run() == FALSE){
                // Form Validation Errors
                $messages = "Data tidak lengkap";
                $return_metadata = [
                    'message' => $messages,
                    'code' => 204
                ];
                $message = [
                    'metadata' => $return_metadata,
                ];
                $this->response($message, REST_Controller::HTTP_NO_CONTENT);
            } else {
                // Load Antrean Model
                $this->load->model('Antrean_model', 'AntreanModel');
                $kirim = 'TRUE';
                if (!DateTime::createFromFormat('Y-m-d', $this->input->post('tanggalawal')) || !DateTime::createFromFormat('Y-m-d', $this->input->post('tanggalakhir'))) { 
                    $messages = "Format Tanggal Harus YYYY-MM-DD";
                    $kirim = 'FALSE';
                }else if(strtotime($this->input->post('tanggalakhir', TRUE)) <= strtotime($this->input->post('tanggalawal', TRUE))){
                    $messages = "Tanggal Akhir tidak boleh lebih kecil dari Tanggal Awal";
                    $kirim = 'FALSE';
                }

                if($kirim == 'TRUE'){
                    // $insert_data_bride = [
                    //     'kodebooking'=> $kodebooking,
                    //     'nomorantrean'=> $nomorantrean,
                    //     'kd_poli_bpjs'=> $this->input->post('kodepoli', TRUE),
                    //     'no_rm'=> $no_rm,
                    //     'date_booking'=> date('Y-m-d H:i:s'),
                    //     'date_update'=> date('Y-m-d H:i:s')
                    // ];
                    
                    // // // Insert Antrean
                    // $this->db->trans_start();
                    // $antreans = $this->AntreanModel->create_antrean($insert_data);
                    // $brides = $this->AntreanModel->create_bride($insert_data_bride);
                    // $this->db->trans_complete();
                    
                    // if ($antreans > 0 AND !empty($antreans)){
                        // Success
                        // var_dump($antreans);
                        $list = array(
                            [
                                'kodebooking'=>'OP2020BED001',
                                'tanggaloperasi'=>'2020-05-02',
                                'jenistindakan'=>'Op ringan',
                                'kodepoli'=>'BED',
                                'namapoli'=>'BEDAH',
                                'terlaksana'=>'0',
                                'nopeserta'=>'000832712832',
                                'lastupdate'=>'1598928319892'
                            ]);
                        
                        $return_data = [
                            'list'=> $list
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
                        // } else {
                            // //     // Error
                            //     $return_metadata = [
                                //         'message' => "Retry",
                                //         'code' => 500
                                //     ];
                                //     $message = [
                                    //         'status' => FALSE,
                    //         'response' => FALSE,
                    //         'metadata' => $return_metadata
                    //     ];
                    //     $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                    // }
                }else{
                    $return_metadata = [
                        'message' => $messages,
                        'code' => 204
                    ];
                    $message = [
                        'metadata' => $return_metadata,
                    ];
                    $this->response($message, REST_Controller::HTTP_NO_CONTENT);
                }
            }
            
        } else {
            $this->response(['message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}