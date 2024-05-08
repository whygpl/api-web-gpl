<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Product extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/Hero_homes_model", "Hero_homes");
        $this->load->model("$this->directory/Product_homes_model", "Product_homes");
        $this->load->model("$this->directory/Category_model", "Category");
        $this->load->model("$this->directory/Group_model", "Group");
        $this->load->model("$this->directory/Product_model", "Product");
        $this->load->model("$this->directory/Product_type_model", "Product_type");
        $this->load->model("$this->directory/Product_htu_model", "Product_htu");
        $this->load->model("$this->directory/Product_category_model", "Product_category");
        $this->load->model("$this->directory/Product_group_model", "Product_group");
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
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            $url = $this->UrlModel->read_url()[0];
            $whereFields = array(
                'a.status' => 'live',
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
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function about_get($p = 0){
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
                'a.status' => 'live',
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
                'a.status' => 'live',
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
                    'a.status' => 'live',
                    'a.is_delete' => '0'
                );
            } else if (isset($q_strings['product_type_id'])) {
                $whereFields = array(
                    'a.product_type_id' => $q_strings['product_type_id'],
                    'a.status' => 'live',
                    'a.is_delete' => '0'
                );
            } else {
                $whereFields = array(
                    'a.status' => 'live',
                    'a.is_delete' => '0'
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

	public function group_get($p = 0){
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
                    'a.status' => 'live',
                    'a.is_delete' => '0'
                );
            } else if (isset($q_strings['product_category_id'])) {
                $whereFields = array(
                    'a.product_category_id' => $q_strings['product_category_id'],
                    'a.status' => 'live',
                    'a.is_delete' => '0'
                );
            } else {
                $whereFields = array(
                    'a.status' => 'live',
                    'a.is_delete' => '0'
                );
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Group->set_variable('whereFields', $whereFields);
            $this->Group->set_variable('orderFields', $orderFields);
            $output = $this->Group->read();
            for ($i=0; $i < count($output); $i++) {
                $category = $this->Category->read($output[$i]->product_category_id,false)[0];
                $output[$i]->product_category_name = $category->title;
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
        $model_htu = 'Product_htu';

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
                    'a.is_delete' => '0',
                    'a.status' => 'live',
                    'b.status' => 'live'
                );
            }else if (isset($q_strings['status'])) {
                $whereFields = array(
                    'a.is_delete' => '0',
                    'a.status' => 'live',
                    'b.status' => 'live'
                );
            } else {
                $whereFields = array(
                    'a.is_delete' => '0',
                    'a.status' => 'live',
                    'b.status' => 'live'
                );
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Product->set_variable('whereFields', $whereFields);
            $this->Product->set_variable('orderFields', $orderFields);
            $output = $this->Product->read();
            $no=1;
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->no = $no++;
                $output[$i]->microsite = ($output[$i]->microsite != '1') ? false : true;
                $output[$i]->gstore = ($output[$i]->gstore != '1') ? false : true;
                $output[$i]->chat = ($output[$i]->chat != '1') ? false : true;
                $output[$i]->email = ($output[$i]->email != '1') ? false : true;
                $output[$i]->image_url = ($output[$i]->image_url != null) ? IPSERVER.$output[$i]->image_url : NULL;

                if (isset($q_strings['id'])) {
                    $product_images = array();
                    $whereFields = array(
                        'a.product_detail_id' => $q_strings['id'],
                        'a.status' => 'live',
                    );
                    $this->Media->set_variable('whereFields', $whereFields);
                    $product_image = $this->Media->read();
                    $output[$i]->image_url_detail = NULL;
                    for ($d=0; $d < count($product_image); $d++) {
                        ($product_image[$d]->image_url != null) ? array_push($product_images, array('image_url' => IPSERVER.$product_image[$d]->image_url,'imgvid' => $product_image[$d]->imgvid,'id' => $product_image[$d]->id)) : array_push($product_images, array('image_url' => NULL,'imgvid' => NULL));
                        $output[$i]->image_url_detail[$d] = $product_images[$d];
                    }
                }
                $whereFields = array();
                if(isset($q_strings['id'])){
                    $whereFields = array("a.product_id" => $q_strings['id']);
                    $orderFields = array(
                        'a.id' => 'ASC',
                    );
                    $this->$model_htu->set_variable('whereFields', $whereFields);
                    $this->$model_htu->set_variable('orderFields', $orderFields);
                    $product_htu = $this->$model_htu->read(0, false);
                    $output[$i]->htu = $product_htu;
                    $htu_in = [];
                    foreach ($product_htu as $key => $value) {
                        $htu_in[]["value"] = $value->name;
                    }
                    $output[$i]->htu_in = $htu_in;
                    $htu_en = [];
                    foreach ($product_htu as $key => $value) {
                        $htu_en[]["value"] = $value->name_en;
                    }
                    $output[$i]->htu_en = $htu_en;
                    if(isset($output[$i]->attention)){
                        $output[$i]->attention = $output[$i]->attention;
                    }else{
                        $output[$i]->attention = null;
                    }
                    if (isset($output[$i]->attention_en)) {
                        $output[$i]->attention_en = $output[$i]->attention_en;
                    }else{
                        $output[$i]->attention_en = null;
                    }
                }
                
            }
            
            // var_dump($product_htu);
            
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

	public function quality_get($p = 0){
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
            // $whereFields = array(
            //     'a.status' => 'live',
            // );
            // $orderFields = array(
            //     'a.id' => 'ASC',
            // );
            // $this->Abouts->set_variable('whereFields', $whereFields);
            // $this->Abouts->set_variable('orderFields', $orderFields);
            // $output = $this->Abouts->read()[0];
            $output = array();
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.page_id' => 1,
                'a.status' => 'live',
                'a.type' => 'award'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $award_image = $this->Media->read();
            $award_images = array();
            for ($i=0; $i < count($award_image); $i++) {
                ($award_image[$i]->image_url != null) ? array_push($award_images, array('id' => $award_image[$i]->id,'image_url' => IPSERVER.$award_image[$i]->image_url)) : array_push($award_images, array('image_url' => NULL));
            }
            $output = array('award' => $award_images);
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
        // } else {
        //     $response_data = 'Invalid Token.';
        //     $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
        //     return;
        // }
	}

	public function update_type_post() {
        // CALL JSON POST
        $data       = $this->form_post(false);
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
				$model = "Product_type";
                // sebelumnya menggunakan $this->Product_homes
				/* Start Transaction */
				$this->db->trans_start();
                for ($i=1; $i <= 2; $i++) { 
                    if (!empty($_FILES["img_url_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_{$i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_{$i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
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

                    if (!empty($_FILES["img_bg_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_bg_{$i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_bg_{$i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_bg_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_bg_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["img_bg_lg_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_bg_lg_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_bg_lg_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_bg_lg_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_bg_lg_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;

                    if (!empty($_FILES["img_bg_md_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_bg_md_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_bg_md_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_bg_md_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_bg_md_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    
                    if (!empty($_FILES["img_bg_sm_${i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_bg_sm_${i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_bg_sm_${i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_bg_sm_${i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_bg_sm_${i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        for ($i=1; $i <= 2; $i++) { 
                            // bg
                            $dataToDbImageBg[$i] = array();
                            $dataToDbImageBg_lg[$i] = array();
                            $dataToDbImageBg_md[$i] = array();
                            $dataToDbImageBg_sm[$i] = array();
                            // url
                            $dataToDbImage[$i] = array();
                            $dataToDbImage_lg[$i] = array();
                            $dataToDbImage_md[$i] = array();
                            $dataToDbImage_sm[$i] = array();
                            // img bg
                            if (!empty($_FILES["img_bg_${i}"])) {
                                $dataToDbImageBg[$i] = array(
                                    'image_bg' => $_FILES["img_bg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
                                $dataToDbImageBg[$i] = array(
                                    'image_bg' => $output[0]->image_bg,
                                );
                            }
                            if (!empty($_FILES["img_bg_lg_${i}"])) {
                                $dataToDbImageBg_lg[$i] = array(
                                    'image_bg_lg' => $_FILES["img_bg_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
                                $dataToDbImageBg_lg[$i] = array(
                                    'image_bg_lg' => $output[0]->image_bg_lg,
                                );
                            }
                            if (!empty($_FILES["img_bg_md_${i}"])) {
                                $dataToDbImageBg_md[$i] = array(
                                    'image_bg_md' => $_FILES["img_bg_md_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
                                $dataToDbImageBg_md[$i] = array(
                                    'image_bg_md' => $output[0]->image_bg_md,
                                );
                            }
                            if (!empty($_FILES["img_bg_sm_${i}"])) {
                                $dataToDbImageBg_sm[$i] = array(
                                    'image_bg_sm' => $_FILES["img_bg_sm_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
                                $dataToDbImageBg_sm[$i] = array(
                                    'image_bg_sm' => $output[0]->image_bg_sm,
                                );
                            }

                            // img url
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url' => $_FILES["img_url_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
                                $dataToDbImage[$i] = array(
                                    'image_url' => $output[0]->image_url,
                                );
                            }
                            if (!empty($_FILES["img_url_lg_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $_FILES["img_url_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.id' => $i,
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
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
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
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
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read(0,false);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'title_head' => $dt_post["title_head_${i}"], 
                                'title_head_en' => $dt_post["title_head_en_${i}"], 
                                'title' => $dt_post["title_${i}"],
                                'sub_title' => $dt_post["sub_title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'sub_title_en' => $dt_post["sub_title_en_${i}"], 
                                'navigation' => $dt_post["navigation_${i}"], 
                                'navigation_en' => $dt_post["navigation_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'sub_description' => $dt_post["sub_description_${i}"],
                                'sub_description_en' => $dt_post["sub_description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDb[$i] = array_merge($dataToDb[$i],$dataToDbImage[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i],$dataToDbImageBg[$i],$dataToDbImageBg_lg[$i],$dataToDbImageBg_md[$i],$dataToDbImageBg_sm[$i]);
                        }
                    } else {
                        for ($i=1; $i <= 2; $i++) { 
                            // bg
                            $dataToDbImageBg[$i] = array();
                            $dataToDbImageBg_lg[$i] = array();
                            $dataToDbImageBg_md[$i] = array();
                            $dataToDbImageBg_sm[$i] = array();
                            // url
                            $dataToDbImage[$i] = array();
                            $dataToDbImage_lg[$i] = array();
                            $dataToDbImage_md[$i] = array();
                            $dataToDbImage_sm[$i] = array();
                            // img bg
                            if (!empty($_FILES["img_bg_${i}"])) {
                                $dataToDbImageBg[$i] = array(
                                    'image_bg' => $_FILES["img_bg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
                                $dataToDbImageBg[$i] = array(
                                    'image_bg' => $output[0]->image_bg,
                                );
                            }
                            if (!empty($_FILES["img_bg_lg_${i}"])) {
                                $dataToDbImageBg_lg[$i] = array(
                                    'image_bg_lg' => $_FILES["img_bg_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
                                $dataToDbImageBg_lg[$i] = array(
                                    'image_bg_lg' => $output[0]->image_bg_lg,
                                );
                            }
                            if (!empty($_FILES["img_bg_md_${i}"])) {
                                $dataToDbImageBg_md[$i] = array(
                                    'image_bg_md' => $_FILES["img_bg_md_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
                                $dataToDbImageBg_md[$i] = array(
                                    'image_bg_md' => $output[0]->image_bg_md,
                                );
                            }
                            if (!empty($_FILES["img_bg_sm_${i}"])) {
                                $dataToDbImageBg_sm[$i] = array(
                                    'image_bg_sm' => $_FILES["img_bg_sm_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
                                $dataToDbImageBg_sm[$i] = array(
                                    'image_bg_sm' => $output[0]->image_bg_sm,
                                );
                            }

                            // img url
                            if (!empty($_FILES["img_url_${i}"])) {
                                $dataToDbImage[$i] = array(
                                    'image_url' => $_FILES["img_url_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
                                $dataToDbImage[$i] = array(
                                    'image_url' => $output[0]->image_url,
                                );
                            }
                            if (!empty($_FILES["img_url_lg_${i}"])) {
                                $dataToDbImage_lg[$i] = array(
                                    'image_url_lg' => $_FILES["img_url_lg_${i}"]['name'],
                                );
                            } else {
                                $whereFields = array(
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
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
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
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
                                    'a.status' => 'live',
                                );
                                $this->$model->set_variable('whereFields', $whereFields);
                                $output = $this->$model->read($i);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'title_head' => $dt_post["title_head_${i}"], 
                                'title_head_en' => $dt_post["title_head_en_${i}"], 
                                'title' => $dt_post["title_${i}"],
                                'sub_title' => $dt_post["sub_title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'sub_title_en' => $dt_post["sub_title_en_${i}"], 
                                'navigation' => $dt_post["navigation_${i}"], 
                                'navigation_en' => $dt_post["navigation_en_${i}"], 
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'sub_description' => $dt_post["sub_description_${i}"],
                                'sub_description_en' => $dt_post["sub_description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDb[$i] = array_merge($dataToDb[$i],$dataToDbImage[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i],$dataToDbImageBg[$i],$dataToDbImageBg_lg[$i],$dataToDbImageBg_md[$i],$dataToDbImageBg_sm[$i]);
                        }
                        for ($i=1; $i <= 2; $i++) { 
                            $dataToDbPreview[$i] = array(
                                'id' => $i, 
                                'status' => 'preview',
                                'title_head' => $dt_post["title_head_${i}"], 
                                'title_head_en' => $dt_post["title_head_en_${i}"], 
                                'title' => $dt_post["title_${i}"],
                                'sub_title' => $dt_post["sub_title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'sub_title_en' => $dt_post["sub_title_en_${i}"], 
                                'navigation' => $dt_post["navigation_${i}"], 
                                'navigation_en' => $dt_post["navigation_en_${i}"],
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'sub_description' => $dt_post["sub_description_${i}"],
                                'sub_description_en' => $dt_post["sub_description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDbPreview[$i] = array_merge($dataToDbPreview[$i],$dataToDbImage[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i],$dataToDbImageBg[$i],$dataToDbImageBg_lg[$i],$dataToDbImageBg_md[$i],$dataToDbImageBg_sm[$i]);
                        }
                        for ($i=1; $i <= 2; $i++) { 
                            $dataToDbLive[$i] = array(
                                'id' => $i, 
                                'status' => 'live',
                                'title_head' => $dt_post["title_head_${i}"], 
                                'title_head_en' => $dt_post["title_head_en_${i}"], 
                                'title' => $dt_post["title_${i}"],
                                'sub_title' => $dt_post["sub_title_${i}"],
                                'title_en' => $dt_post["title_en_${i}"], 
                                'sub_title_en' => $dt_post["sub_title_en_${i}"], 
                                'navigation' => $dt_post["navigation_${i}"], 
                                'navigation_en' => $dt_post["navigation_en_${i}"],
                                'description' => $dt_post["description_${i}"],
                                'description_en' => $dt_post["description_en_${i}"],
                                'sub_description' => $dt_post["sub_description_${i}"],
                                'sub_description_en' => $dt_post["sub_description_en_${i}"],
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $dataToDbLive[$i] = array_merge($dataToDbLive[$i],$dataToDbImage[$i],$dataToDbImage_lg[$i],$dataToDbImage_md[$i],$dataToDbImage_sm[$i],$dataToDbImageBg[$i],$dataToDbImageBg_lg[$i],$dataToDbImageBg_md[$i],$dataToDbImageBg_sm[$i]);
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
				$model = "Product_category";
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
                        $whereFields = array(
                            'a.id' => $id,
                            'a.status' => 'live'
                        );
                        $this->$model->set_variable('whereFields',$whereFields);
                        $output = $this->$model->read($id, false);
                        $dataToDbImage = array(
                            'image_url' => $output[0]->image_url
                        );
                        $whereFields = array();
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
                //         'status' => 'preview',
                //         'product_type_id' => $dt_post["product_type_id"],
                //         'title' => $dt_post["title"],
                //         'title_en' => $dt_post["title_en"], 
                //         'description' => $dt_post["description"],
                //         'description_en' => $dt_post["description_en"]
                //     );
                //     $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                // }
                if ($dt_post['save_as'] == 'live' || true) {
                    $dataToDb = array(
                        'status' => 'live',
                        'product_type_id' => $dt_post["product_type_id"],
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                        'description' => $dt_post["description"],
                        'description_en' => $dt_post["description_en"]
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                    // var_dump($dataToDb);
                    if ($id != 0 && $id != '') {
				        $this->$model->replace($dataToDb);
                    }
                }
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'live',
                            'product_type_id' => $dt_post["product_type_id"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'description' => $dt_post["description"],
                            'description_en' => $dt_post["description_en"]
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
				$model = "Product_category";
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

	public function update_group_post($id = 0) {
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
				$model = "Product_group";
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
                //         'status' => 'preview',
                //         'product_category_id' => $dt_post["product_category_id"],
                //         'title' => $dt_post["title"],
                //         'title_en' => $dt_post["title_en"], 
                //     );
                //     $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                //     if ($id != 0 && $id != '') {
				//         $this->$model->replace($dataToDb);
                //     }
                // }
                if ($dt_post['save_as'] == 'live' || true) {
                    $dataToDb = array(
                        'status' => 'live',
                        'product_category_id' => $dt_post["product_category_id"],
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                }
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'preview',
                            'product_category_id' => $dt_post["product_category_id"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
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

	public function delete_group_post($id = 0) {
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
				$model = "Product_group";
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

	public function update_product_post($id = 0) {
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
				$model = "Product";
				$model_media = "Media";
                $type = "product_detail";
                $model_htu = 'Product_htu';
				/* Start Transaction */
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'live' || true) {
                    // if ($id != 0) {
                        $whereFields = array('a.product_detail_id' => $id,'a.type' => 'product_detail','a.imgvid' => 'img','a.status' => 'live');
                    // }
                    $this->$model_media->set_variable('whereFields', $whereFields);
                    $output_media = $this->$model_media->read(0, false);
                } else {
                    $whereFields = array('a.type' => 'product_detail','a.imgvid' => 'img','a.status' => 'live');
                    $this->$model_media->set_variable('whereFields', $whereFields);
                    $output_media = $this->$model_media->read(0, false);
                }
                $whereFields = array();
                // foreach ($output_media as $key => $value) {
                //     if (!empty($_FILES["img_url_{$value->id}"])):
                //         $config['upload_path'] = './assets/media/images';
                //         $config['allowed_types'] = '*';
                //         $config['overwrite'] = true;	
                //         $config['file_name'] = "img_url_c_{$value->id}_".time();
                //         $this->load->library('upload', $config, 'photo_upload');
                //         $this->photo_upload->initialize($config);
                //         if ( $this->photo_upload->do_upload("img_url_{$value->id}") ) {
                //             $fileData = $this->photo_upload->data();
                //             $_FILES["img_url_{$value->id}"]['name'] =  $fileData['file_name'];
                //         } else {
                //             $error = array('error' => $this->photo_upload->display_errors());
                //             $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_{$value->id}"]['name'], $error['error']);
                //             return;
                //         }
                //     endif;
                // }

                if(!isset($dt_post['count_item'])){
                    if ($id != 0) {
                        $whereFields = array('product_detail_id' => $id, 'type' => 'product_detail','status' => 'live');
                        // var_dump($whereFields);
                        $this->$model_media->set_variable('whereFields', $whereFields);
                        $checkImage = $this->$model_media->read(0, false);
                        if (count($checkImage) > 0) {
                            $this->$model_media->removetype($checkImage[0]->id,$whereFields);
                        }
                        $whereFields = array();
                    }
                 }


                // for ($i = count($output_media); $i < $dt_post['count_item']; $i++) { 
                //     // var_dump($i);
                //     if (!empty($_FILES["img_url_new_{$i}"])):
                //         $config['upload_path'] = './assets/media/images';
                //         $config['allowed_types'] = '*';
                //         $config['overwrite'] = true;	
                //         $config['file_name'] = "img_url_c_new_{$i}_".time();
                //         $this->load->library('upload', $config, 'photo_upload');
                //         $this->photo_upload->initialize($config);
                //         if ( $this->photo_upload->do_upload("img_url_new_{$i}") ) {
                //             $fileData = $this->photo_upload->data();
                //             $_FILES["img_url_new_{$i}"]['name'] =  $fileData['file_name'];
                //         } else {
                //             $error = array('error' => $this->photo_upload->display_errors());
                //             $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_new_{$i}"]['name'], $error['error']);
                //             return;
                //         }
                //     endif;
                // }

                foreach ($_FILES as $key => $value) {
                    if (strpos($key, 'img_url_new') !== false) {
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = $key."_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload($key) ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES[$key]["name"] =  $fileData['file_name'];
                            // var_dump($_FILES[$key]["name"]);
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["$key"]['name'], $error['error']);
                            return;
                        }
                    }
                }

                // if (!empty($_FILES["img_url"])):
                //     $config['upload_path'] = './assets/media/images';
                //     $config['allowed_types'] = '*';
                //     $config['overwrite'] = true;	
                //     $config['file_name'] = "img_url_".time();
                //     $this->load->library('upload', $config, 'photo_upload');
                //     $this->photo_upload->initialize($config);
                //     if ( $this->photo_upload->do_upload("img_url") ) {
                //         $fileData = $this->photo_upload->data();
                //         $_FILES["img_url"]['name'] =  $fileData['file_name'];
                //     } else {
                //         $error = array('error' => $this->photo_upload->display_errors());
                //         $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url"]['name'], $error['error']);
                //         return;
                //     }
                // endif;
                foreach ($_FILES as $key => $value) {
                    if (strpos($key, 'img_url') !== false) {
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = $key."_".time();
                        // var_dump($config['file_name']);
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload($key) ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES[$key]["name"] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["$key"]['name'], $error['error']);
                            return;
                        }
                    }
                }
                $dataToDbImage = array();
                if (!empty($_FILES["img_url"])) {
                    // var_dump($_FILES["img_url"]['name']);
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
                //     $whereFields = array();
                //     $dataToDbMediaU = array();
                //     $dataToDbMediaN = array();
                //     $dataToDbMediaNEW = array();
                //     // var_dump($output_media);
                //     // var_dump($_FILES["img_url_{$value->id}"]);die();
                //     foreach ($output_media as $key => $value) {
                //         if (!empty($_FILES["img_url_{$value->id}"])) {
                //             if (isset($dt_post["id_{$value->id}"])) {
                //                 $dataToDbMediaU[$key] = array(
                //                     'id' => $value->id,
                //                     'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                //                     'status' => 'preview'
                //                 );
                //             } else {
                //                 $dataToDbMediaU[$key] = array(
                //                     'id' => $value->id,
                //                     'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                //                     'status' => 'delete'
                //                 );
                //                 $whereFields = array('id' => $value->id, 'type' => 'product_detail','imgvid' => 'img','status' => 'preview');
                //                 $this->$model_media->removetype($value->id,$whereFields);
                //                 $whereFields = array();
                //             }
                //         } else {
                //             if (isset($dt_post["id_{$value->id}"])) {
                //                 $dataToDbMediaN[$key] = array(
                //                     'id' => $value->id,
                //                     'image_url' => $value->image_url,
                //                     'status' => 'preview'
                //                 );
                //             } else {
                //                 $dataToDbMediaN[$key] = array(
                //                     'id' => $value->id,
                //                     'image_url' => $value->image_url,
                //                     'status' => 'delete'
                //                 );
                //                 $whereFields = array('id' => $value->id, 'type' => 'product_detail','imgvid' => 'img','status' => 'preview');
                //                 // $whereFields = array('id' => $value->id,'type' => $type,'status' => 'preview');
                //                 $this->$model_media->removetype($value->id,$whereFields);
                //                 $whereFields = array();
                //             }
                //         }
                //     }
                //     for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                //         if (!empty($_FILES["img_url_new_{$i}"])){
                //             if ($dt_post["id_new_{$i}"] == '') {
                //                 $dataToDbMediaNEW = array(
                //                     'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                //                     'product_detail_id' => $id,
                //                     'type' => 'product_detail',
                //                     'imgvid' => 'img',
                //                     'page_id' => 3,
                //                     'status' => 'preview'
                //                 );
                //                 $this->$model_media->replace($dataToDbMediaNEW);
                //             }
                //         }
                //     } 
                //     $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                //     $this->$model_media->replace_batch($dataToDbMedia);
                //     $dataToDb = array(
                //         'status' => 'preview',
                //         'product_category_id' => $dt_post["product_category_id"],
                //         'product_group_id' => $dt_post["product_group_id"],
                //         'name' => $dt_post["name"],
                //         'name_en' => $dt_post["name_en"], 
                //         'description' => $dt_post["description"],
                //         'description_en' => $dt_post["description_en"],
                //         'compotition' => $dt_post["compotition"],
                //         'compotition_en' => $dt_post["compotition_en"], 
                //         'gstoreurl' => $dt_post["gstoreurl"],
                //         'micrositeurl' => $dt_post["micrositeurl"],
                //         'microsite' => $dt_post["microsite"] == 'true' ? '1' : '0',
                //         'gstore' => $dt_post["gstore"] == 'true' ? '1' : '0',
                //         'email' => $dt_post["email"] == 'true' ? '1' : '0',
                //         'chat' => $dt_post["chat"] == 'true' ? '1' : '0',
                //     );
                //     $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                //     if ($id != 0 && $id != '') {
				//         $this->$model->replace($dataToDb);
                //     }
                // }
                if ($dt_post['save_as'] == 'live' || true) {
                    $dataToDb = array(
                        'status' => 'live',
                        'product_category_id' => $dt_post["product_category_id"],
                        'product_group_id' => $dt_post["product_group_id"],
                        'name' => $dt_post["name"],
                        'name_en' => $dt_post["name_en"], 
                        'description' => $dt_post["description"],
                        'description_en' => $dt_post["description_en"],
                        'compotition' => $dt_post["compotition"],
                        'compotition_en' => $dt_post["compotition_en"],
                        // 'attention' => $dt_post["attention"] ?? null,
                        'gstoreurl' => $dt_post["gstoreurl"],
                        'micrositeurl' => $dt_post["micrositeurl"],
                        'microsite' => $dt_post["microsite"] == 'true' ? '1' : '0',
                        'gstore' => $dt_post["gstore"] == 'true' ? '1' : '0',
                        'email' => $dt_post["email"] == 'true' ? '1' : '0',
                        'chat' => $dt_post["chat"] == 'true' ? '1' : '0',
                        'chatvalue' => $dt_post["chatvalue"],
                        'emailvalue' => $dt_post["emailvalue"] 
                    );
                    if(isset($dt_post["attention"])){
                        $dataToDb["attention"] = $dt_post["attention"];
                        $dataToDb["attention_en"] = isset($dt_post["attention_en"]) ? $dt_post["attention_en"] : null;
                    }
                    $dataToDb = array_merge($dataToDb,$dataToDbID,$dataToDbImage);
                }
                $dataToDbMediaU = array();
                $dataToDbMediaN = array();
                $dataToDbMediaNEW = array();
                if($id != 0){
                    foreach ($output_media as $key => $value) {
                        if (!empty($_FILES["img_url_{$value->id}"])) {
                            if (isset($dt_post["id_{$value->id}"])) {
                                $dataToDbMediaU[$key] = array(
                                    'id' => $value->id,
                                    'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                    'status' => 'live'
                                );
                            } else {
                                $dataToDbMediaU[$key] = array(
                                    'id' => $value->id,
                                    'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                    'status' => 'delete'
                                );
                                $whereFields = array('id' => $value->id, 'type' => 'product_detail','imgvid' => 'img','status' => 'live');
                                $this->$model_media->removetype($value->id,$whereFields);
                                $whereFields = array();
                            }
                        } else {
                            if (isset($dt_post["id_{$value->id}"])) {
                                $dataToDbMediaN[$key] = array(
                                    'id' => $value->id,
                                    'image_url' => $value->image_url,
                                    'status' => 'live'
                                );
                            } else {
                                $dataToDbMediaN[$key] = array(
                                    'id' => $value->id,
                                    'image_url' => $value->image_url,
                                    'status' => 'delete'
                                );
                                $whereFields = array('id' => $value->id, 'type' => 'product_detail','imgvid' => 'img','status' => 'live');
                                // $whereFields = array('id' => $value->id,'type' => $type,'status' => 'live');
                                $this->$model_media->removetype($value->id,$whereFields);
                                $whereFields = array();
                            }
                        }
                    }
                }
                $htu = [];
                if($id != 0){
                    foreach($dt_post as $key => $value){
                        // var_dump(strpos($key, 'htu_'));
                        if ((isset($dt_post["htu_in"]) && $dt_post["htu_in"] != [])  || (isset($dt_post["htu_in"]) && $dt_post["htu_in"] != [])) {
                            $whereFields = array("a.product_id" => $id);
                            $this->$model_htu->set_variable('whereFields', $whereFields);
                            $htu = $this->$model_htu->read(0, false);
                            // var_dump($htu);
                            break;
                        
                        //     $dataHtuToDB = array(
                        //         'product_id' => $id,
                        //         'status' => 'live',
                        //         'is_delete' => 0
                        //     );
                        //     if (strpos($key, 'htu_') !== false) {
                        //         $NameHtu = array(
                        //             'name' => $value
                        //         );
                        //         $dataHtuToDB = array_merge($dataHtuToDB,$NameHtu);
                        //     }
                        //     if (strpos($key, 'en_htu_') !== false) {
                        //         $EnNameHtu = array(
                        //             'name_en' => $value
                        //         );
                        //         $dataHtuToDB = array_merge($dataHtuToDB,$EnNameHtu);
                        //     }
                        //     $this->$model_htu->remove($dataHtuToDB);
                        }
                    }
                    if($htu != []){
                        foreach ($htu as $key => $value) {
                            $whereFields = array('id' => $value->id, 'product_id' => $id);
                            $this->$model_htu->set_variable('whereFields', $whereFields);
                            $this->$model_htu->removetype($value->id,$whereFields);
                            $whereFields = array();
                        }
                    }
                }
                // for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                //     if (!empty($_FILES["img_url_new_{$i}"])){
                //         if ($dt_post["id_new_{$i}"] == '') {
                //             $dataToDbMediaNEW = array(
                //                 'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                //                 'product_detail_id' => $id,
                //                 'type' => 'product_detail',
                //                 'imgvid' => 'img',
                //                 'page_id' => 3,
                //                 'status' => 'live'
                //             );
                //             $this->$model_media->replace($dataToDbMediaNEW);
                //         }
                //     }
                // } 

                // var_dump($dt_post["attention_en"]);
                $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                $this->$model_media->replace_batch($dataToDbMedia);
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'preview',
                            'product_category_id' => $dt_post["product_category_id"],
                            'product_group_id' => $dt_post["product_group_id"],
                            'name' => $dt_post["name"],
                            'name_en' => $dt_post["name_en"], 
                            'description' => $dt_post["description"],
                            'description_en' => $dt_post["description_en"],
                            'compotition' => $dt_post["compotition"],
                            'compotition_en' => $dt_post["compotition_en"], 
                            // 'attention' => $dt_post["attention"] ?? null,
                            'gstoreurl' => $dt_post["gstoreurl"],
                            'micrositeurl' => $dt_post["micrositeurl"],
                            'microsite' => $dt_post["microsite"] == 'true' ? '1' : '0',
                            'gstore' => $dt_post["gstore"] == 'true' ? '1' : '0',
                            'email' => $dt_post["email"] == 'true' ? '1' : '0',
                            'chat' => $dt_post["chat"] == 'true' ? '1' : '0',
                            'chatvalue' => $dt_post["chatvalue"],
                            'emailvalue' => $dt_post["emailvalue"] 
                        );
                        if(isset($dt_post["attention"])){
                            $dataToDbPreview["attention"] = $dt_post["attention"];
                            $dataToDbPreview["attention_en"] = (isset($dt_post["attention_en"])) ? $dt_post["attention_en"] : null;
                        }
                        $dataToDbPreview = array_merge($dataToDbPreview,$dataToDbID,$dataToDbImage);
                        $insert = $this->$model->create($dataToDbPreview);
                        $dataHtuToDB = array();
                        // $j=1;
                        // foreach($dt_post as $key => $value){
                        //     if ("htu_in_{$id}" == $key || "htu_en_{$id}" == $key) {
                        //         if (strpos($key, 'htu_in_') !== false || strpos($key, 'htu_en_') !== false) {
                        //             $dataHtu = array(
                        //                 'product_id' => $id,
                        //                 'status' => 'live',
                        //                 'is_delete' => 0
                        //             );
                        //             if (isset($dt_post["htu_in_{$j}"])) {
                        //                 $NameHtu = array(
                        //                     'name' => $dt_post["htu_in_{$j}"]
                        //                 );
                        //                 $dataHtu = array_merge($dataHtu,$NameHtu);
                        //             }
                        //             if (isset($dt_post["htu_en_{$j}"])) {
                        //                 $NameHtu = array(
                        //                     'name_en' => $dt_post["htu_en_{$j}"]
                        //                 );
                        //                 $dataHtu = array_merge($dataHtu,$NameHtu);
                        //             }
                        //             $this->$model_htu->replace($dataHtu);
                        //             $j++;
                        //             // $i++;
                        //         }
                        //     }
                        // }
                        $i = 0;
                        foreach ($dt_post["htu_in"] as $key => $value) {
                            $baseHtu = array(
                                'product_id' => $id,
                                'status' => 'live',
                                'is_delete' => 0
                            );
                             $NameHtu = array(
                                        'name' => $dt_post["htu_in"][$i]["value"],
                                        'name_en' => $dt_post["htu_en"][$i]["value"]
                                    );
                            $dataHtu = array_merge($baseHtu,$NameHtu);
                            $this->$model_htu->create($dataHtu);
                            $i++;
                        }
                    }
                } else {
				    $this->$model->replace($dataToDb);
                    // $dataHtuToDB = array();
                    // $i = 0;
                    $j=1;
                    // foreach($dt_post as $key => $value){
                    //     if ("htu_in_{$j}" == $key || "htu_en_{$j}" == $key) {
                    //         if (strpos($key, 'htu_in_') !== false || strpos($key, 'htu_en_') !== false) {
                    //             $dataHtu = array(
                    //                 'product_id' => $id,
                    //                 'status' => 'live',
                    //                 'is_delete' => 0
                    //             );
                    //             if (isset($dt_post["htu_in_{$j}"])) {
                    //                 $NameHtu = array(
                    //                     'name' => $dt_post["htu_in_{$j}"]
                    //                 );
                    //                 $dataHtu = array_merge($dataHtu,$NameHtu);
                    //             }
                    //             if (isset($dt_post["htu_en_{$j}"])) {
                    //                 $NameHtu = array(
                    //                     'name_en' => $dt_post["htu_en_{$j}"]
                    //                 );
                    //                 $dataHtu = array_merge($dataHtu,$NameHtu);
                    //             }
                    //             $this->$model_htu->replace($dataHtu);
                    //             $j++;
                    //         }
                    //     }
                    // }
                    // var_dump($dt_post["htu_in"]);
                    // var_dump($dt_post["htu_en"]);
                    $dataToDbHtu = array();
                    if((isset($dt_post["htu_in"]) && $dt_post["htu_in"] != [])  || (isset($dt_post["htu_in"]) && $dt_post["htu_in"] != [])){
                        $i = 0;
                        foreach ($dt_post["htu_in"] as $key => $value) {
                            $baseHtu = array(
                                'product_id' => $id,
                                'status' => 'live',
                                'is_delete' => 0
                            );
                             $NameHtu = array(
                                        'name' => $dt_post["htu_in"][$i]["value"],
                                        'name_en' => $dt_post["htu_en"][$i]["value"]
                                    );
                            $dataHtu = array_merge($baseHtu,$NameHtu);
                            $this->$model_htu->replace($dataHtu);
                            $i++;
                        }

                    }
                }
                foreach($_FILES as $key => $value){
                    if(strpos($key, 'img_url_new') !== false) {
                        $i = substr($key, strlen('img_url_new'));
                        $dataToDbMediaNEW = array(
                            'image_url' => $_FILES[$key]['name'],
                            'product_detail_id' => $id,
                            'type' => 'product_detail',
                            'imgvid' => 'img',
                            'page_id' => 3,
                            'status' => 'live'
                        );
                        $this->$model_media->create($dataToDbMediaNEW); 
                        $whereFields = array();
                    }
                }
                // var_dump($id);
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

	public function delete_product_post($id = 0) {
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
				$model = "Product";
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
                                    'a.status' => 'live',
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
                                    'a.status' => 'live',
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
                                    'a.status' => 'live',
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
                                    'a.status' => 'live',
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
                                    'a.status' => 'live',
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
                                    'a.status' => 'live',
                                );
                                $this->Hero_homes->set_variable('whereFields', $whereFields);
                                $output = $this->Hero_homes->read($i);
                                $dataToDbImage_sm[$i] = array(
                                    'image_url_sm' => $output[0]->image_url_sm,
                                );
                            }
                            $dataToDb[$i] = array(
                                'id' => $i, 
                                'status' => 'live',
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
}