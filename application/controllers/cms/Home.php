<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Home extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/Hero_homes_model", "Hero_homes");
        $this->load->model("$this->directory/Product_homes_model", "Product_homes");
        $this->load->model("$this->directory/Qualities_model", "Qualities");
        $this->load->model("$this->directory/Abouts_model", "Abouts");
        $this->load->model("$this->directory/Media_model", "Media");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function hero_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {

            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Hero_homes->set_variable('whereFields', $whereFields);
            $this->Hero_homes->set_variable('orderFields', $orderFields);
            $output = $this->Hero_homes->read();
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->image_url_lg = ($output[$i]->image_url_lg != null) ? IPSERVER.$output[$i]->image_url_lg : NULL;
                $output[$i]->image_url_md = ($output[$i]->image_url_md != null) ? IPSERVER.$output[$i]->image_url_md : NULL;
                $output[$i]->image_url_sm = ($output[$i]->image_url_sm != null) ? IPSERVER.$output[$i]->image_url_sm : NULL;
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
        } else {
            $response_data = 'Invalid Token.';
            $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
            return;
        }
	}

	public function about_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Abouts->set_variable('whereFields', $whereFields);
            $this->Abouts->set_variable('orderFields', $orderFields);
            $output = $this->Abouts->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.page_id' => $output->page_id,
                'a.status' => 'preview',
                'a.type' => 'about'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $about_image = $this->Media->read();
            $about_images = array();
            for ($i=0; $i < count($about_image); $i++) {
                ($about_image[$i]->image_url != null) ? array_push($about_images, array('id' => $about_image[$i]->id,'image_url' => IPSERVER.$about_image[$i]->image_url)) : array_push($about_images, array('image_url' => NULL));
            }
            $output->image_urls = $about_images;
            // for ($i=0; $i < count($output); $i++) {
            //     $output[$i]->image_url_lg = ($output[$i]->image_url_lg != null) ? IPSERVER.$output[$i]->image_url_lg : NULL;
            //     $output[$i]->image_url_md = ($output[$i]->image_url_md != null) ? IPSERVER.$output[$i]->image_url_md : NULL;
            //     $output[$i]->image_url_sm = ($output[$i]->image_url_sm != null) ? IPSERVER.$output[$i]->image_url_sm : NULL;
            // }
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
        } else {
            $response_data = 'Invalid Token.';
            $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
            return;
        }
	}

	public function product_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Product_homes->set_variable('whereFields', $whereFields);
            $this->Product_homes->set_variable('orderFields', $orderFields);
            $output = $this->Product_homes->read();
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->image_url_lg = ($output[$i]->image_url_lg != null) ? IPSERVER.$output[$i]->image_url_lg : NULL;
                $output[$i]->image_url_md = ($output[$i]->image_url_md != null) ? IPSERVER.$output[$i]->image_url_md : NULL;
                $output[$i]->image_url_sm = ($output[$i]->image_url_sm != null) ? IPSERVER.$output[$i]->image_url_sm : NULL;
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
        } else {
            $response_data = 'Invalid Token.';
            $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
            return;
        }
	}

	public function quality_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;

        // Token Validation
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            // $whereFields = array(
            //     'a.status' => 'preview',
            // );
            // $orderFields = array(
            //     'a.id' => 'ASC',
            // );
            // $this->Abouts->set_variable('whereFields', $whereFields);
            // $this->Abouts->set_variable('orderFields', $orderFields);
            // $output = $this->Abouts->read()[0];
            $output = array();
            $award_images = array();
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.page_id' => 1,
                'a.status' => 'preview',
                'a.type' => 'award'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $award_image = $this->Media->read();
            $quality_images = array();
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.page_id' => 1,
                'a.status' => 'preview',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Qualities->set_variable('orderFields', $orderFields);
            $this->Qualities->set_variable('whereFields', $whereFields);
            $quality_images = $this->Qualities->read();
            for ($i=0; $i < count($award_image); $i++) {
                ($award_image[$i]->image_url != null) ? array_push($award_images, array('id' => $award_image[$i]->id,'image_url' => IPSERVER.$award_image[$i]->image_url)) : array_push($award_images, array('image_url' => NULL));
            }
            for ($i=0; $i < count($quality_images); $i++) {
                $quality_images[$i]->image_url = ($quality_images[$i]->image_url != null) ? IPSERVER.$quality_images[$i]->image_url : NULL;
            }
            $output = array('quality' => $quality_images,'award' => $award_images);
            // for ($i=0; $i < count($output); $i++) {
            //     $output[$i]->image_url_lg = ($output[$i]->image_url_lg != null) ? IPSERVER.$output[$i]->image_url_lg : NULL;
            //     $output[$i]->image_url_md = ($output[$i]->image_url_md != null) ? IPSERVER.$output[$i]->image_url_md : NULL;
            //     $output[$i]->image_url_sm = ($output[$i]->image_url_sm != null) ? IPSERVER.$output[$i]->image_url_sm : NULL;
            // }
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
        } else {
            $response_data = 'Invalid Token.';
            $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
            return;
        }
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
				$model = "Hero_homes";
				/* Start Transaction */
				$this->db->trans_start();
                for ($i=1; $i <= 3; $i++) { 
                    if (!empty($_FILES["img_url_lg_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_lg_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_lg_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_lg_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_lg_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["img_url_md_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_md_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_md_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_md_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_md_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["img_url_sm_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_sm_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_sm_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_sm_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_sm_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbImage_lg[$i] = array();
                            $dataToDbImage_md[$i] = array();
                            $dataToDbImage_sm[$i] = array();
                            if (!empty($_FILES["img_url_lg_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $_FILES["img_url_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $output[0]->image_url_lg,
                                );
                            }
                            if (!empty($_FILES["img_url_md_${i}"])) {
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $_FILES["img_url_md_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $output[0]->image_url_md,
                                );
                            }
                            if (!empty($_FILES["img_url_sm_${i}"])) {
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $_FILES["img_url_sm_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'colour' => $dt_post["colour_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDb[$i] = array_merge($dataToDb[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                    } else {
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbImage_lg[$i] = array();
                            $dataToDbImage_md[$i] = array();
                            $dataToDbImage_sm[$i] = array();
                            if (!empty($_FILES["img_url_lg_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $_FILES["img_url_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $output[0]->image_url_lg,
                                );
                            }
                            if (!empty($_FILES["img_url_md_${i}"])) {
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $_FILES["img_url_md_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $output[0]->image_url_md,
                                );
                            }
                            if (!empty($_FILES["img_url_sm_${i}"])) {
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $_FILES["img_url_sm_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'colour' => $dt_post["colour_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDb[$i] = array_merge($dataToDb[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbPreview[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'colour' => $dt_post["colour_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDbPreview[$i] = array_merge($dataToDbPreview[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbLive[$i] = array(
                                'id' => $i, 
                                'status' => 'live',
                                'colour' => $dt_post["colour_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDbLive[$i] = array_merge($dataToDbLive[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                        $dataToDb = array_merge($dataToDbPreview,$dataToDbLive);
                    }
				}
                // echo "<pre>";
                // // var_dump($_FILES);
                // var_dump($dataToDb);die();
                // // var_dump($dt_post["title_${i}"]);
                // echo "</pre>";
				$id = $this->$model->replace_batch($dataToDb);
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

	public function update_about_post() {
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
				$model = "Abouts";
				$model_media = "Media";
				/* Start Transaction */
				$this->db->trans_start();
                for ($i=1; $i <= 3; $i++) { 
                    if (!empty($_FILES["img_url_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbImage[$i] = array();
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbImage[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $_FILES["img_url_${i}"]['name']
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_${i}"],
                                    'a.status' => 'preview',
                                    'a.type' => 'about'
                                );
                                $this->Media->set_variable('whereFields', $whereFields);
                                $output = $this->Media->read(0, false);
                                $dataToDbImage[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $output[0]->image_url
                                );
                            }
                        }
                        $dataToDbPreview = array(
                            'id' => 1, 
                            'status' => 'preview',
                            'page_title' => $dt_post["page_title"],
                            'page_title_en' => $dt_post["page_title_en"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'description' => $dt_post["description"],
                            'description_en' => $dt_post["description_en"],
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                    } else {
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbImagePreview[$i] = array();
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbImagePreview[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $_FILES["img_url_${i}"]['name']
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_${i}"],
                                    'a.status' => 'preview',
                                    'a.type' => 'about'
                                );
                                $this->Media->set_variable('whereFields', $whereFields);
                                $output = $this->Media->read(0, false);
                                $dataToDbImagePreview[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $output[0]->image_url
                                );
                            }

                            $dataToDbImageLive[$i] = array();
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbImageLive[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'live',
                                    'image_url' => $_FILES["img_url_${i}"]['name']
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_${i}"],
                                    'a.status' => 'preview',
                                    'a.type' => 'about'
                                );
                                $this->Media->set_variable('whereFields', $whereFields);
                                $output = $this->Media->read(0, false);
                                $dataToDbImageLive[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'live',
                                    'image_url' => $output[0]->image_url
                                );
                            }
                            $dataToDbImage = array_merge($dataToDbImageLive,$dataToDbImagePreview);
                        }
                        $dataToDbPreview = array(
                            'id' => 1, 
                            'status' => 'preview',
                            'page_title' => $dt_post["page_title"],
                            'page_title_en' => $dt_post["page_title_en"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'description' => $dt_post["description"],
                            'description_en' => $dt_post["description_en"],
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $dataToDbLive = array(
                            'id' => 1, 
                            'status' => 'live',
                            'page_title' => $dt_post["page_title"],
                            'page_title_en' => $dt_post["page_title_en"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'description' => $dt_post["description"],
                            'description_en' => $dt_post["description_en"],
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        // $dataToDb = array_merge($dataToDbPreview,$dataToDbLive);
                    }
				}
                // echo "<pre>";
                // // var_dump($_FILES);
                // var_dump($dataToDbImage);die();
                // // var_dump($dt_post["title_${i}"]);
                // echo "</pre>";
                if ($dataToDbPreview) {
                    $id = $this->$model->replace($dataToDbPreview);
                }
                if ($dataToDbLive) {
                    $id = $this->$model->replace($dataToDbLive);
                }
				$this->$model_media->replace_batch($dataToDbImage);
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

	public function update_product_post() {
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
				$model = "Product_homes";
				/* Start Transaction */
				$this->db->trans_start();
                for ($i=1; $i <= 2; $i++) { 
                    if (!empty($_FILES["img_url_lg_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_lg_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_lg_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_lg_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_lg_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["img_url_md_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_md_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_md_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_md_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_md_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["img_url_sm_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_sm_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_sm_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_sm_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_sm_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        for ($i=1; $i <= 2; $i++) { 
                            $dataToDbImage_lg[$i] = array();
                            $dataToDbImage_md[$i] = array();
                            $dataToDbImage_sm[$i] = array();
                            if (!empty($_FILES["img_url_lg_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $_FILES["img_url_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'preview',
                                );
                                $this->Product_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Product_homes->read(0,false);
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $output[0]->image_url_lg,
                                );
                            }
                            if (!empty($_FILES["img_url_md_${i}"])) {
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $_FILES["img_url_md_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'preview',
                                );
                                $this->Product_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Product_homes->read(0,false);
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $output[0]->image_url_md,
                                );
                            }
                            if (!empty($_FILES["img_url_sm_${i}"])) {
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $_FILES["img_url_sm_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'preview',
                                );
                                $this->Product_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Product_homes->read(0,false);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'category' => $dt_post["category_${i}"],
                                'category_en' => $dt_post["category_en_${i}"],
                                'product_type' => $dt_post["product_type_${i}"],
                                'product_type_en' => $dt_post["product_type_en_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDb[$i] = array_merge($dataToDb[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                    } else {
                        for ($i=1; $i <= 2; $i++) { 
                            $dataToDbImage_lg[$i] = array();
                            $dataToDbImage_md[$i] = array();
                            $dataToDbImage_sm[$i] = array();
                            if (!empty($_FILES["img_url_lg_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $_FILES["img_url_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Product_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Product_homes->read($i);
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $output[0]->image_url_lg,
                                );
                            }
                            if (!empty($_FILES["img_url_md_${i}"])) {
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $_FILES["img_url_md_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Product_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Product_homes->read($i);
                                $dataToDbImage_md[$i] = array(
                                    'image_url_md' => $output[0]->image_url_md,
                                );
                            }
                            if (!empty($_FILES["img_url_sm_${i}"])) {
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $_FILES["img_url_sm_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'preview',
                                );
                                $this->Product_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Product_homes->read($i);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'category' => $dt_post["category_${i}"],
                                'category_en' => $dt_post["category_en_${i}"],
                                'product_type' => $dt_post["product_type_${i}"],
                                'product_type_en' => $dt_post["product_type_en_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDb[$i] = array_merge($dataToDb[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                        for ($i=1; $i <= 2; $i++) { 
                            $dataToDbPreview[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'category' => $dt_post["category_${i}"],
                                'category_en' => $dt_post["category_en_${i}"],
                                'product_type' => $dt_post["product_type_${i}"],
                                'product_type_en' => $dt_post["product_type_en_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDbPreview[$i] = array_merge($dataToDbPreview[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                        for ($i=1; $i <= 2; $i++) { 
                            $dataToDbLive[$i] = array(
                                'id' => $i, 
                                'status' => 'live',
                                'category' => $dt_post["category_${i}"],
                                'category_en' => $dt_post["category_en_${i}"],
                                'product_type' => $dt_post["product_type_${i}"],
                                'product_type_en' => $dt_post["product_type_en_${i}"],
                                'title' => $dt_post["title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDbLive[$i] = array_merge($dataToDbLive[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i]);
                        }
                        $dataToDb = array_merge($dataToDbPreview,$dataToDbLive);
                    }
				}
                // echo "<pre>";
                // // var_dump($_FILES);
                // var_dump($dataToDb);die();
                // // var_dump($dt_post["title_${i}"]);
                // echo "</pre>";
				$id = $this->$model->replace_batch($dataToDb);
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

	public function update_aq_post() {
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
				$model = "Qualities";
				$model_media = "Media";
				/* Start Transaction */
				$this->db->trans_start();
                for ($i=1; $i <= 3; $i++) { 
                    if (!empty($_FILES["img_url_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                for ($i=1; $i <= 4; $i++) { 
                    if (!empty($_FILES["img_url_c_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_c_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_c_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_c_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_c_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbPreview[$i] = array();
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbPreview[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'name' => $dt_post["name_${i}"],
                                    'name_en' => $dt_post["name_en_${i}"],
                                    'image_url' => $_FILES["img_url_${i}"]['name'],
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_${i}"],
                                    'a.status' => 'preview',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0, false);
                                $dataToDbPreview[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'name' => $dt_post["name_${i}"],
                                    'name_en' => $dt_post["name_en_${i}"],
                                    'image_url' => $output[0]->image_url,
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            }
                        }
                        for ($i=1; $i <= 4; $i++) { 
                            $dataToDbPreviewC[$i] = array();
                            if (!empty($_FILES["img_url_c_${i}"])) {
                                $dataToDbPreviewC[$i] = array(
                                    'id' => $dt_post["img_url_id_c_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $_FILES["img_url_c_${i}"]['name'],
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_c_${i}"],
                                    'a.type' => 'award',
                                    'a.status' => 'preview',
                                );
                                $this->$model_media->set_variable('whereFields', $whereFields);
                                $output = $this->$model_media->read(0, false);
                                $dataToDbPreviewC[$i] = array(
                                    'id' => $dt_post["img_url_id_c_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $output[0]->image_url,
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            }
                        }
                    } else {
                        for ($i=1; $i <= 3; $i++) { 
                            $dataToDbPreview[$i] = array();
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbPreview[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'name' => $dt_post["name_${i}"],
                                    'name_en' => $dt_post["name_en_${i}"],
                                    'image_url' => $_FILES["img_url_${i}"]['name'],
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_${i}"],
                                    'a.status' => 'preview',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0, false);
                                $dataToDbPreview[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'preview',
                                    'name' => $dt_post["name_${i}"],
                                    'name_en' => $dt_post["name_en_${i}"],
                                    'image_url' => $output[0]->image_url,
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            }

                            $dataToDbLive[$i] = array();
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbLive[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'live',
                                    'name' => $dt_post["name_${i}"],
                                    'name_en' => $dt_post["name_en_${i}"],
                                    'image_url' => $_FILES["img_url_${i}"]['name']
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_${i}"],
                                    'a.status' => 'preview',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0, false);
                                $dataToDbLive[$i] = array(
                                    'id' => $dt_post["img_url_id_${i}"],
                                    'status' => 'live',
                                    'name' => $dt_post["name_${i}"],
                                    'name_en' => $dt_post["name_en_${i}"],
                                    'image_url' => $output[0]->image_url
                                );
                            }
                        }
                        for ($i=1; $i <= 4; $i++) { 
                            $dataToDbPreviewC[$i] = array();
                            if (!empty($_FILES["img_url_c_${i}"])) {
                                $dataToDbPreviewC[$i] = array(
                                    'id' => $dt_post["img_url_id_c_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $_FILES["img_url_c_${i}"]['name'],
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_c_${i}"],
                                    'a.type' => 'award',
                                    'a.status' => 'preview'
                                );
                                $this->$model_media->set_variable('whereFields', $whereFields);
                                $output = $this->$model_media->read(0, false);
                                $dataToDbPreviewC[$i] = array(
                                    'id' => $dt_post["img_url_id_c_${i}"],
                                    'status' => 'preview',
                                    'image_url' => $output[0]->image_url,
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                            }

                            $dataToDbLiveC[$i] = array();
                            if (!empty($_FILES["img_url_c_${i}"])) {
                                $dataToDbLiveC[$i] = array(
                                    'id' => $dt_post["img_url_id_c_${i}"],
                                    'status' => 'live',
                                    'image_url' => $_FILES["img_url_c_${i}"]['name']
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $dt_post["img_url_id_c_${i}"],
                                    'a.type' => 'award',
                                    'a.status' => 'preview'
                                );
                                $this->$model_media->set_variable('whereFields', $whereFields);
                                $output = $this->$model_media->read(0, false);
                                $dataToDbLiveC[$i] = array(
                                    'id' => $dt_post["img_url_id_c_${i}"],
                                    'status' => 'live',
                                    'image_url' => $output[0]->image_url
                                );
                            }
                        }
                        // $dataToDb = array_merge($dataToDbPreview,$dataToDbLive);
                    }
				}
                // echo "<pre>";
                // var_dump($_FILES);
                // var_dump($dataToDbLive);
                // var_dump($dataToDbPreview);
                // var_dump($dataToDbLiveC);
                // var_dump($dataToDbPreviewC);die();
                // // var_dump($dt_post["title_${i}"]);
                // echo "</pre>";
                if ($dataToDbPreview) {
                    $id = $this->$model->replace_batch($dataToDbPreview);
                }
                if ($dataToDbLive) {
                    $id = $this->$model->replace_batch($dataToDbLive);
                }
                if ($dataToDbPreviewC) {
                    $id = $this->$model_media->replace_batch($dataToDbPreviewC);
                }
                if ($dataToDbLiveC) {
                    $id = $this->$model_media->replace_batch($dataToDbLiveC);
                }
				// $this->$model_media->replace_batch($dataToDbImage);
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