<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class News extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/News_categorys_model", "Category");
        $this->load->model("$this->directory/News_model", "News");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function category_get($p = 0){
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
            if (isset($q_strings['id'])) {
                $whereFields = array(
                    'a.id' => $q_strings['id'],
                    'a.status' => 'live'
                );
            } else {
                $whereFields = array(
                    'a.status' => 'live'
                );
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Category->set_variable('whereFields', $whereFields);
            $this->Category->set_variable('orderFields', $orderFields);
            $output = $this->Category->read();
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->image_url = ($output[$i]->image_url != null) ? IPSERVER.$output[$i]->image_url : NULL;
            }
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

	public function detail_get($p = 0){
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
            if (isset($q_strings['id'])) {
                $whereFields = array(
                    'a.id' => $q_strings['id'],
                    'a.is_delete' => 0,
                    'a.status' => 'live'
                );
            } else {
                $whereFields = array(
                    'a.is_delete' => 0,
                    'a.status' => 'live'
                );
            }
            $orderFields = array(
                'a.id' => 'DESC',
            );
            $this->News->set_variable('whereFields', $whereFields);
            $this->News->set_variable('orderFields', $orderFields);
            $output = $this->News->read();
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->image_url = ($output[$i]->image_url != null) ? IPSERVER.$output[$i]->image_url : NULL;
            }
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

	public function update_news_post($id = 0) {
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
				$model = "News";
				/* Start Transaction */
				$this->db->trans_start();
                if (!empty($_FILES["img_url"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "img_url_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("img_url") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["img_url"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url"]['name'], $error['error']);
                        return;
                    }
                endif;
                $dataToDbImage = array();
                if (!empty($_FILES["img_url"])) {
                    $dataToDbImage = array(
                        'image_url' => $_FILES["img_url"]['name'],
                    );
                } else {
                    if ($id != 0 && $id != '') {
                        $output = $this->$model->read($id, false);
                        $dataToDbImage = array(
                            'image_url' => $output[0]->image_url
                        );
                    }
                }
                if ($id == 0 && $id == '') {
                    $dataToDbID = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                } else {
                    $dataToDbID = array(
                        'id' => $id,
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                }
                // if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                //     $dataToDb = array(
                //         'status' => 'live',
                //         'category_id' => $dt_post["category_id"],
                //         'title' => $dt_post["title"],
                //         'title_en' => $dt_post["title_en"], 
                //         'description' => $dt_post["description"],
                //         'description_en' => $dt_post["description_en"],
                //         'desc' => $dt_post["desc"],
                //         'desc_en' => $dt_post["desc_en"], 
                //         'date' => $dt_post["date"],
                //     );
                //     $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                // }
                if ($dt_post['save_as'] == 'live' || true) {
                    $dataToDb = array(
                        'status' => 'live',
                        'category_id' => $dt_post["category_id"],
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                        'description' => $dt_post["description"],
                        'description_en' => $dt_post["description_en"],
                        'desc' => $dt_post["desc"],
                        'desc_en' => $dt_post["desc_en"], 
                        'date' => $dt_post["date"],
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                }
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'live',
                            'category_id' => $dt_post["category_id"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'description' => $dt_post["description"],
                            'description_en' => $dt_post["description_en"],
                            'desc' => $dt_post["desc"],
                            'desc_en' => $dt_post["desc_en"], 
                            'date' => $dt_post["date"],
                        );
                        $dataToDbPreview = array_merge($dataToDbPreview,$dataToDbID,$dataToDbImage);
                        $this->$model->replace($dataToDbPreview);
                    }
                } else {
				    $this->$model->replace($dataToDb);
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

	public function uploader_news_post($p = 0) {
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
				/* Start Transaction */
				$this->db->trans_start();
                if (!empty($_FILES["upload"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "upload_news_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("upload") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["upload"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["upload"]['name'], $error['error']);
                        return;
                    }
                endif;
				$this->db->trans_complete();
				/* End Transaction */
				if ($this->db->trans_status() === false) {
					$response_data = array(
						'error'   => $this->db->trans_status()
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'url'     => IPSERVER.$_FILES["upload"]['name']
					);
					$this->REST_Return(200, 'SUCCESS', $response_data);
				}
                return;     
        //     }
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
    }

	public function delete_news_post($id = 0) {
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
				$model = "News";
				/* Start Transaction */
				$this->db->trans_start();
                if ($id != 0 && $id != '') {
                    $dataToDb = array(
                        'id' => $id,
                        'is_delete' => 1,
                        'deleted_at' => date('Y-m-d H:i:s')
                    );
                    $this->$model->update($id, $dataToDb);
                }
                // var_dump($dataToDb);
                // die();
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