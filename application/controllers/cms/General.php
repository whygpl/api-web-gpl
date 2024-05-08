<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class General extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/Media_model", "Media");
        $this->load->model("$this->directory/General_model", "General");
        $this->load->model("$this->directory/Page_model", "Page");
        $this->load->model("$this->directory/Email_server_model", "Email_server");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function general_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.id' => 1,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->General->set_variable('whereFields', $whereFields);
            $this->General->set_variable('orderFields', $orderFields);
            $output = $this->General->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'preview',
                'a.type' => 'navbar'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $navbar_image = $this->Media->read();
            $navbar_images = array();
            for ($i=0; $i < count($navbar_image); $i++) {
                ($navbar_image[$i]->image_url != null) ? array_push($navbar_images, array('id' => $navbar_image[$i]->id,'image_url' => IPSERVER.$navbar_image[$i]->image_url)) : array_push($navbar_images, array('image_url' => NULL));
            }
            $output->image_url_navbar = $navbar_images[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'preview',
                'a.type' => 'footer'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $footer_image = $this->Media->read();
            $footer_images = array();
            for ($i=0; $i < count($footer_image); $i++) {
                ($footer_image[$i]->image_url != null) ? array_push($footer_images, array('id' => $footer_image[$i]->id,'image_url' => IPSERVER.$footer_image[$i]->image_url)) : array_push($footer_images, array('image_url' => NULL));
            }
            $output->image_url_footer = $footer_images[0];
            $response_data = array();
            if ($output != [] && $id != 0) {
                $response_data = $output;
                $error   = false;
            } else if($output != [] or $id == 0) {
                $response_data = $output;
                $error   = false;
            } else {
                $this->REST_Return(404, $this->name . ' with id ' . $id . ' does not exist!');
                return;
            }
            if ($error == true) {
                $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            } else {
                $this->REST_Return(200, 'SUCCESS', $response_data);
            }
            return;
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function privacy_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.id' => 8,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Page->set_variable('whereFields', $whereFields);
            $this->Page->set_variable('orderFields', $orderFields);
            $output = $this->Page->read()[0];
            $output->contact = ($output->contact != null) ? json_decode($output->contact, true) : NULL;
            $response_data = array();
            if ($output != [] && $id != 0) {
                $response_data = $output;
                $error   = false;
            } else if($output != [] or $id == 0) {
                $response_data = $output;
                $error   = false;
            } else {
                $this->REST_Return(404, $this->name . ' with id ' . $id . ' does not exist!');
                return;
            }
            if ($error == true) {
                $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            } else {
                $this->REST_Return(200, 'SUCCESS', $response_data);
            }
            return;
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function smtp_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.status' => 'preview',
            );
            $orderFields = array(
            );
            $this->Email_server->set_variable('whereFields', $whereFields);
            $this->Email_server->set_variable('orderFields', $orderFields);
            $output = $this->Email_server->read()[0];
            $response_data = array();
            if ($output != [] && $id != 0) {
                $response_data = $output;
                $error   = false;
            } else if($output != [] or $id == 0) {
                $response_data = $output;
                $error   = false;
            } else {
                $this->REST_Return(404, $this->name . ' with id ' . $id . ' does not exist!');
                return;
            }
            if ($error == true) {
                $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            } else {
                $this->REST_Return(200, 'SUCCESS', $response_data);
            }
            return;
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function terms_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.id' => 9,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Page->set_variable('whereFields', $whereFields);
            $this->Page->set_variable('orderFields', $orderFields);
            $output = $this->Page->read()[0];
            $output->contact = ($output->contact != null) ? json_decode($output->contact, true) : NULL;
            $response_data = array();
            if ($output != [] && $id != 0) {
                $response_data = $output;
                $error   = false;
            } else if($output != [] or $id == 0) {
                $response_data = $output;
                $error   = false;
            } else {
                $this->REST_Return(404, $this->name . ' with id ' . $id . ' does not exist!');
                return;
            }
            if ($error == true) {
                $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            } else {
                $this->REST_Return(200, 'SUCCESS', $response_data);
            }
            return;
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function farma_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.id' => 10,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Page->set_variable('whereFields', $whereFields);
            $this->Page->set_variable('orderFields', $orderFields);
            $output = $this->Page->read()[0];
            $output->contact = ($output->contact != null) ? json_decode($output->contact, true) : NULL;
            $response_data = array();
            if ($output != [] && $id != 0) {
                $response_data = $output;
                $error   = false;
            } else if($output != [] or $id == 0) {
                $response_data = $output;
                $error   = false;
            } else {
                $this->REST_Return(404, $this->name . ' with id ' . $id . ' does not exist!');
                return;
            }
            if ($error == true) {
                $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            } else {
                $this->REST_Return(200, 'SUCCESS', $response_data);
            }
            return;
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function faq_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.id' => 11,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Page->set_variable('whereFields', $whereFields);
            $this->Page->set_variable('orderFields', $orderFields);
            $output = $this->Page->read()[0];
            $output->contact = ($output->contact != null) ? json_decode($output->contact, true) : NULL;
            $response_data = array();
            if ($output != [] && $id != 0) {
                $response_data = $output;
                $error   = false;
            } else if($output != [] or $id == 0) {
                $response_data = $output;
                $error   = false;
            } else {
                $this->REST_Return(404, $this->name . ' with id ' . $id . ' does not exist!');
                return;
            }
            if ($error == true) {
                $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            } else {
                $this->REST_Return(200, 'SUCCESS', $response_data);
            }
            return;
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

    private function tojson ($array = array()) {
        $data_return = array();
        $data_array = json_decode($array);
        $no = 1;
        for ($i=0; $i < count($data_array); $i++) { 
            if ($data_array[$i]->name != "") {
                $data_return[$i] = array(
                    'id' => $no++,
                    'name' => $data_array[$i]->name
                );
            }
        }
        return json_encode($data_return, JSON_PRETTY_PRINT);
    }

	public function update_post() {
        // CALL JSON POST
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     # Form Validation
        //     $this->form_validation->set_rules($this->create_rules);
        //     if ($this->form_validation->run() == FALSE){
        //         // Form Validation Errors
        //         $response_data = $this->form_validation->error_array();
        //         $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        //     } else {
				$model = "General";
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 1, 
                        'status' => 'preview',
                        'distributor_map_embed' => $dt_post["distributor_map_embed"],
                        'distributor_address' => $dt_post["distributor_address"],
                        'distributor_address_en' => $dt_post["distributor_address_en"],
                        'distributor_fax' => $dt_post["distributor_fax"],
                        'distributor_fax_en' => $dt_post["distributor_fax_en"],
                        'distributor_name' => $dt_post["distributor_name"],
                        'distributor_name_en' => $dt_post["distributor_name_en"],
                        'distributor_phone' => $dt_post["distributor_phone"],
                        'distributor_phone_en' => $dt_post["distributor_phone_en"],
                        'factory_address' => $dt_post["factory_address"],
                        'factory_address_en' => $dt_post["factory_address_en"],
                        'factory_fax' => $dt_post["factory_fax"],
                        'factory_fax_en' => $dt_post["factory_fax_en"],
                        'factory_map_embed' => $dt_post["factory_map_embed"],
                        'factory_name' => $dt_post["factory_name"],
                        'factory_name_en' => $dt_post["factory_name_en"],
                        'factory_phone' => $dt_post["factory_phone"],
                        'factory_phone_en' => $dt_post["factory_phone_en"],
                        // 'image_url_footer' => $dt_post["image_url_footer"],
                        // 'image_url_navbar' => $dt_post["image_url_navbar"],
                        'instagram_url' => $dt_post["instagram_url"],
                        'youtube_url' => $dt_post["youtube_url"],
                        'office_address' => $dt_post["office_address"],
                        'office_address_en' => $dt_post["office_address_en"],
                        'office_fax' => $dt_post["office_fax"],
                        'office_fax_en' => $dt_post["office_fax_en"],
                        'office_map_embed' => $dt_post["office_map_embed"],
                        'office_name' => $dt_post["office_name"],
                        'office_name_en' => $dt_post["office_name_en"],
                        'office_phone' => $dt_post["office_phone"],
                        'office_phone_en' => $dt_post["office_phone_en"],
                        'store_url' => $dt_post["store_url"],
                        'whatsapp_number' => $dt_post["whatsapp_number"],
                        'email' => $dt_post["email"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                } 
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 1, 
                        'status' => 'live',
                        'distributor_map_embed' => $dt_post["distributor_map_embed"],
                        'distributor_address' => $dt_post["distributor_address"],
                        'distributor_address_en' => $dt_post["distributor_address_en"],
                        'distributor_fax' => $dt_post["distributor_fax"],
                        'distributor_fax_en' => $dt_post["distributor_fax_en"],
                        'distributor_name' => $dt_post["distributor_name"],
                        'distributor_name_en' => $dt_post["distributor_name_en"],
                        'distributor_phone' => $dt_post["distributor_phone"],
                        'distributor_phone_en' => $dt_post["distributor_phone_en"],
                        'factory_address' => $dt_post["factory_address"],
                        'factory_address_en' => $dt_post["factory_address_en"],
                        'factory_fax' => $dt_post["factory_fax"],
                        'factory_fax_en' => $dt_post["factory_fax_en"],
                        'factory_map_embed' => $dt_post["factory_map_embed"],
                        'factory_name' => $dt_post["factory_name"],
                        'factory_name_en' => $dt_post["factory_name_en"],
                        'factory_phone' => $dt_post["factory_phone"],
                        'factory_phone_en' => $dt_post["factory_phone_en"],
                        // 'image_url_footer' => $dt_post["image_url_footer"],
                        // 'image_url_navbar' => $dt_post["image_url_navbar"],
                        'instagram_url' => $dt_post["instagram_url"],
                        'youtube_url' => $dt_post["youtube_url"],
                        'office_address' => $dt_post["office_address"],
                        'office_address_en' => $dt_post["office_address_en"],
                        'office_fax' => $dt_post["office_fax"],
                        'office_fax_en' => $dt_post["office_fax_en"],
                        'office_map_embed' => $dt_post["office_map_embed"],
                        'office_name' => $dt_post["office_name"],
                        'office_name_en' => $dt_post["office_name_en"],
                        'office_phone' => $dt_post["office_phone"],
                        'office_phone_en' => $dt_post["office_phone_en"],
                        'store_url' => $dt_post["store_url"],
                        'whatsapp_number' => $dt_post["whatsapp_number"],
                        'email' => $dt_post["email"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                }
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'id'     => $id
					);
					$this->REST_Return(201, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }

	public function update_privacy_post() {
        // CALL JSON POST
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     # Form Validation
        //     $this->form_validation->set_rules($this->create_rules);
        //     if ($this->form_validation->run() == FALSE){
        //         // Form Validation Errors
        //         $response_data = $this->form_validation->error_array();
        //         $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        //     } else {
				$model = "Page";
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 8, 
                        'status' => 'preview',
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                } 
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 8, 
                        'status' => 'live',
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                }
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'id'     => $id
					);
					$this->REST_Return(201, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }

	public function update_terms_post() {
        // CALL JSON POST
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     # Form Validation
        //     $this->form_validation->set_rules($this->create_rules);
        //     if ($this->form_validation->run() == FALSE){
        //         // Form Validation Errors
        //         $response_data = $this->form_validation->error_array();
        //         $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        //     } else {
				$model = "Page";
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 9, 
                        'status' => 'preview',
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                } 
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 9, 
                        'status' => 'live',
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                }
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'id'     => $id
					);
					$this->REST_Return(201, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }

	public function update_farma_post() {
        // CALL JSON POST
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     # Form Validation
        //     $this->form_validation->set_rules($this->create_rules);
        //     if ($this->form_validation->run() == FALSE){
        //         // Form Validation Errors
        //         $response_data = $this->form_validation->error_array();
        //         $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        //     } else {
				$model = "Page";
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 10, 
                        'status' => 'preview',
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                } 
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 10, 
                        'status' => 'live',
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                }
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'id'     => $id
					);
					$this->REST_Return(201, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }

	public function update_faq_post() {
        // CALL JSON POST
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     # Form Validation
        //     $this->form_validation->set_rules($this->create_rules);
        //     if ($this->form_validation->run() == FALSE){
        //         // Form Validation Errors
        //         $response_data = $this->form_validation->error_array();
        //         $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        //     } else {
				$model = "Page";
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 11, 
                        'status' => 'preview',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"],
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                } 
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'id' => 11, 
                        'status' => 'live',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"],
                        'pages' => $dt_post["page"],
                        'page_en' => $dt_post["page_en"],
                        'contact' => $dt_post["contact"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $id = $this->$model->replace($dataToDb);
                }
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'id'     => $id
					);
					$this->REST_Return(201, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }

	public function update_smtp_post() {
        // CALL JSON POST
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     # Form Validation
        //     $this->form_validation->set_rules($this->create_rules);
        //     if ($this->form_validation->run() == FALSE){
        //         // Form Validation Errors
        //         $response_data = $this->form_validation->error_array();
        //         $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        //     } else {
				$model = "Email_server";
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'status' => 'preview',
                        'host' => $dt_post["host"],
                        'emailhost' => $dt_post["emailhost"],
                        'password' => $dt_post["password"],
                        'secure' => $dt_post["secure"],
                        'port' => $dt_post["port"],
                        'sendquestion' => $dt_post["sendquestion"],
                        'sendadvice' => $dt_post["sendadvice"],
                        'sendany' => $dt_post["sendany"],
                    );
                    $id = $this->$model->replace($dataToDb);
                } 
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'status' => 'live',
                        'host' => $dt_post["host"],
                        'emailhost' => $dt_post["emailhost"],
                        'password' => $dt_post["password"],
                        'secure' => $dt_post["secure"],
                        'port' => $dt_post["port"],
                        'sendquestion' => $dt_post["sendquestion"],
                        'sendadvice' => $dt_post["sendadvice"],
                        'sendany' => $dt_post["sendany"],
                    );
                    $id = $this->$model->replace($dataToDb);
                }
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'id'     => $id
					);
					$this->REST_Return(201, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }
}