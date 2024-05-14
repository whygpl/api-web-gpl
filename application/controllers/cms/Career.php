<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Career extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/Media_model", "Media");
        $this->load->model("$this->directory/Career_model", "Career");
        $this->load->model("$this->directory/Career_categorys_model", "Career_categorys");
        $this->load->model("$this->directory/Career_join_model", "Career_join");
        $this->load->model("$this->directory/Career_data_model", "Career_data");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function career_get($p = 0){
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
            $this->Career->set_variable('whereFields', $whereFields);
            $this->Career->set_variable('orderFields', $orderFields);
            $output_work = $this->Career->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.id' => 2,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Career->set_variable('whereFields', $whereFields);
            $this->Career->set_variable('orderFields', $orderFields);
            $output_culture = $this->Career->read()[0];
            $output_work->image_url = ($output_work->image_url != null) ? IPSERVER.$output_work->image_url : NULL;
            $output_culture->image_url = ($output_culture->image_url != null) ? IPSERVER.$output_culture->image_url : NULL;
            $output = array('work'=>$output_work,'culture'=>$output_culture);
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

	public function list_get($p = 0){
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
                'a.status' => 'preview',
                'is_delete' => 0
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Career_join->set_variable('whereFields', $whereFields);
            $this->Career_join->set_variable('orderFields', $orderFields);
            $output = $this->Career_join->read();
            for ($h=0; $h < count($output); $h++) {
                $output[$h]->career_categorys_name = $this->Career_categorys->read($output[$h]->career_categorys_id)[0]->title;
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

	public function apply_get($p = 0){
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
                'a.join_id' => $q_strings['join_id'],
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Career_data->set_variable('whereFields', $whereFields);
            $this->Career_data->set_variable('orderFields', $orderFields);
            $output = $this->Career_data->read();
            for ($h=0; $h < count($output); $h++) {
                $output[$h]->join_name = $this->Career_join->read($output[$h]->join_id)[0]->title;
				$output[$h]->id = $h+1;
				$output[$h]->photo = "<a href='".IPSERVER.$output[$h]->photo."' target='_blank'><img src='".IPSERVER.$output[$h]->photo."' /></a>";
                $output[$h]->cv = "<a href='".IPSERVER.$output[$h]->cv."' target='_blank'>download</a>";
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
                    'a.status' => 'preview'
                );
            } else {
                $whereFields = array(
                    'a.status' => 'preview'
                );
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Career_join->set_variable('whereFields', $whereFields);
            $this->Career_join->set_variable('orderFields', $orderFields);
            $output = $this->Career_join->read();
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->description = ($output[$i]->description != null) ? json_decode($output[$i]->description, true) : NULL;
                $output[$i]->description_en = ($output[$i]->description_en != null) ? json_decode($output[$i]->description_en, true) : NULL;
                $output[$i]->requierement = ($output[$i]->requierement != null) ? json_decode($output[$i]->requierement, true) : NULL;
                $output[$i]->requierement_en = ($output[$i]->requierement_en != null) ? json_decode($output[$i]->requierement_en, true) : NULL;
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
                    'a.status' => 'preview',
                    'is_delete' => 0,
                );
            } else {
                $whereFields = array(
                    'a.status' => 'preview',
                    'is_delete' => 0,
                );
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Career_categorys->set_variable('whereFields', $whereFields);
            $this->Career_categorys->set_variable('orderFields', $orderFields);
            $output = $this->Career_categorys->read();
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
				$model = "Career";
				$model_media = "Media";
				/* Start Transaction */
				$this->db->trans_start();
                if (!empty($_FILES["img_url_1"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "img_url_1_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("img_url_1") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["img_url_1"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_1"]['name'], $error['error']);
                        return;
                    }
                endif;
                if (!empty($_FILES["h_img_url_1"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "h_img_url_1_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("h_img_url_1") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["h_img_url_1"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["h_img_url_1"]['name'], $error['error']);
                        return;
                    }
                endif;
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    if (!empty($_FILES["img_url_1"])) {
                        $dataToDbImage = array(
                            'image_url' => $_FILES["img_url_1"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 1,
                            'a.status' => 'preview',
                        );
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        $dataToDbImage = array(
                            'image_url' => $output[0]->image_url
                        );
                    }
                    if (!empty($_FILES["h_img_url_1"])) {
                        $dataToDbImageCulture = array(
                            'image_url' => $_FILES["h_img_url_1"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 2,
                            'a.status' => 'preview',
                        );
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        $dataToDbImageCulture = array(
                            'image_url' => $output[0]->image_url
                        );
                    }
                    $dataToDb = array(
                        'id' => 1, 
                        'status' => 'preview',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"],
                        'description' => $dt_post["description"],
                        'description_en' => $dt_post["description_en"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $dataToDbCulture = array(
                        'id' => 2, 
                        'status' => 'preview',
                        'title' => $dt_post["h_title"],
                        'title_en' => $dt_post["h_title_en"],
                        'description' => $dt_post["h_description"],
                        'description_en' => $dt_post["h_description_en"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbImage);
                    $dataToDbCulture = array_merge($dataToDbCulture,$dataToDbImageCulture);
                    $id = $this->$model->replace($dataToDb);
                    $id = $this->$model->replace($dataToDbCulture);
                } 
                if ($dt_post['save_as'] == 'live') {
                    if (!empty($_FILES["img_url_1"])) {
                        $dataToDbImage = array(
                            'image_url' => $_FILES["img_url_1"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 1,
                            'a.status' => 'preview',
                        );
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        $dataToDbImage = array(
                            'image_url' => $output[0]->image_url
                        );
                    }
                    if (!empty($_FILES["h_img_url_1"])) {
                        $dataToDbImageCulture = array(
                            'image_url' => $_FILES["h_img_url_1"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 2,
                            'a.status' => 'preview',
                        );
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        $dataToDbImageCulture = array(
                            'image_url' => $output[0]->image_url
                        );
                    }
                    $dataToDb = array(
                        'id' => 1, 
                        'status' => 'live',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"],
                        'description' => $dt_post["description"],
                        'description_en' => $dt_post["description_en"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $dataToDbCulture = array(
                        'id' => 2, 
                        'status' => 'live',
                        'title' => $dt_post["h_title"],
                        'title_en' => $dt_post["h_title_en"],
                        'description' => $dt_post["h_description"],
                        'description_en' => $dt_post["h_description_en"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbImage);
                    $dataToDbCulture = array_merge($dataToDbCulture,$dataToDbImageCulture);
                    $id = $this->$model->replace($dataToDb);
                    $id = $this->$model->replace($dataToDbCulture);
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

	public function update_join_post($id = 0) {
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
				$model = "Career_join";
				/* Start Transaction */

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
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'status' => 'preview',
                        'career_categorys_id' => $dt_post["career_category_id"],
                        'deadline' => $dt_post["deadline"],
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                        'subtitle' => $dt_post["subtitle"],
                        'subtitle_en' => $dt_post["subtitle_en"], 
                        'description' => $this->tojson($dt_post["description"]),
                        'description_en' => $this->tojson($dt_post["description_en"]),  
                        'requierement' => $this->tojson($dt_post["requierement"]),
                        'requierement_en' => $this->tojson($dt_post["requierement_en"])
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID);
                }
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'status' => 'live',
                        'career_categorys_id' => $dt_post["career_category_id"],
                        'deadline' => $dt_post["deadline"],
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                        'subtitle' => $dt_post["subtitle"],
                        'subtitle_en' => $dt_post["subtitle_en"], 
                        'description' => $this->tojson($dt_post["description"]),
                        'description_en' => $this->tojson($dt_post["description_en"]),  
                        'requierement' => $this->tojson($dt_post["requierement"]),
                        'requierement_en' => $this->tojson($dt_post["requierement_en"])
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID);
                }
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'preview',
                            'career_categorys_id' => $dt_post["career_category_id"],
                            'deadline' => $dt_post["deadline"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'subtitle' => $dt_post["subtitle"],
                            'subtitle_en' => $dt_post["subtitle_en"], 
                            'description' => $this->tojson($dt_post["description"]),
                            'description_en' => $this->tojson($dt_post["description_en"]),  
                            'requierement' => $this->tojson($dt_post["requierement"]),
                            'requierement_en' => $this->tojson($dt_post["requierement_en"]) 
                        );
                        $dataToDbPreview = array_merge($dataToDbPreview,$dataToDbID);
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

	public function delete_join_post($id = 0) {
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
				$model = "Career_join";
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

	public function update_category_post($id = 0) {
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
				$model = "Career_categorys";
				/* Start Transaction */
				$this->db->trans_start();
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
                if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'status' => 'preview',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID);
                }
                if ($dt_post['save_as'] == 'live') {
                    $dataToDb = array(
                        'status' => 'live',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID);
                }
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'preview',
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                        );
                        $dataToDbPreview = array_merge($dataToDbPreview,$dataToDbID);
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

	public function delete_category_post($id = 0) {
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
				$model = "Career_categorys";
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