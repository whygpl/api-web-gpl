<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Business extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/Media_model", "Media");
        $this->load->model("$this->directory/Business_export_model", "Business_export");
        $this->load->model("$this->directory/Business_manufacturing_model", "Business_manufacturing");
        $this->load->model("$this->directory/Business_distribution_model", "Business_distribution");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function business_get($p = 0){
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
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Business_export->set_variable('whereFields', $whereFields);
            $this->Business_export->set_variable('orderFields', $orderFields);
            $output_export = $this->Business_export->read()[0];
            $output_export->contact = ($output_export->contact != null) ? json_decode($output_export->contact, true) : NULL;
            $output_export->contact_en = ($output_export->contact_en != null) ? json_decode($output_export->contact_en, true) : NULL;
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.id' => 1,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Business_manufacturing->set_variable('whereFields', $whereFields);
            $this->Business_manufacturing->set_variable('orderFields', $orderFields);
            $output_manufacturing = $this->Business_manufacturing->read()[0];
            $output_manufacturing->par_list_2 = ($output_manufacturing->par_list_2 != null) ? json_decode($output_manufacturing->par_list_2, true) : NULL;
            $output_manufacturing->par_list_3 = ($output_manufacturing->par_list_3 != null) ? json_decode($output_manufacturing->par_list_3, true) : NULL;
            $output_manufacturing->par_list_2_en = ($output_manufacturing->par_list_2_en != null) ? json_decode($output_manufacturing->par_list_2_en, true) : NULL;
            $output_manufacturing->par_list_3_en = ($output_manufacturing->par_list_3_en != null) ? json_decode($output_manufacturing->par_list_3_en, true) : NULL;
            $output_manufacturing->contact = ($output_manufacturing->contact != null) ? json_decode($output_manufacturing->contact, true) : NULL;
            $output_manufacturing->contact_en = ($output_manufacturing->contact_en != null) ? json_decode($output_manufacturing->contact_en, true) : NULL;
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'business_export'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $business_export_image = $this->Media->read();
            $business_export_images = array();
            for ($i=0; $i < count($business_export_image); $i++) {
                ($business_export_image[$i]->image_url != null) ? array_push($business_export_images, array('id' => $business_export_image[$i]->id,'image_url' => IPSERVER.$business_export_image[$i]->image_url)) : array_push($business_export_images, array('image_url' => NULL));
            }
            $output_export->image_urls = $business_export_images[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'business_manufacturing'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $business_manufacturing_image = $this->Media->read();
            $business_manufacturing_images = array();
            for ($i=0; $i < count($business_manufacturing_image); $i++) {
                ($business_manufacturing_image[$i]->image_url != null) ? array_push($business_manufacturing_images, array('id' => $business_manufacturing_image[$i]->id,'image_url' => IPSERVER.$business_manufacturing_image[$i]->image_url)) : array_push($business_manufacturing_images, array('image_url' => NULL));
            }
            $output_manufacturing->image_url = ($output_manufacturing->image_url != null) ? IPSERVER.$output_manufacturing->image_url : NULL;
            $output_manufacturing->image_urls = $business_manufacturing_images[0];
            $output = array('export'=>$output_export,'manufacturing'=>$output_manufacturing);
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

	public function distributor_get($p = 0){
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
                // 'a.status' => 'live',
                'a.status' => 'live',
                'a.is_delete' => 0
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Business_distribution->set_variable('whereFields', $whereFields);
            $this->Business_distribution->set_variable('orderFields', $orderFields);
            $output = $this->Business_distribution->read();
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
                    'a.status' => 'live'
                );
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Business_distribution->set_variable('whereFields', $whereFields);
            $this->Business_distribution->set_variable('orderFields', $orderFields);
            $output = $this->Business_distribution->read();
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->companies = ($output[$i]->companies != null) ? json_decode($output[$i]->companies, true) : NULL;
                $output[$i]->companies_en = ($output[$i]->companies_en != null) ? json_decode($output[$i]->companies_en, true) : NULL;
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

	public function mitra_get($p = 0){
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
            $url = $this->UrlModel->read_url()[0];;
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'business_mitra'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $mitra_image = $this->Media->read(0,false);
            $mitra_images = array();
            for ($i=0; $i < count($mitra_image); $i++) {
                ($mitra_image[$i]->image_url != null) ? array_push($mitra_images, array('id' => $mitra_image[$i]->id,'image_url' => IPSERVER.$mitra_image[$i]->image_url)) : array_push($mitra_images, array('image_url' => NULL));
            }
            $output = $mitra_images;
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
				$model_export = "Business_export";
				$model_manufacture = "Business_manufacturing";
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
                if (!empty($_FILES["h_img_url"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "h_img_url_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("h_img_url") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["h_img_url"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["h_img_url"]['name'], $error['error']);
                        return;
                    }
                endif;
                // if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                //     //Image to media
                //     if (!empty($_FILES["img_url_1"])) {
                //         $dataToDbImageExport = array(
                //             'id' => $dt_post["img_url_id_1"],
                //             'status' => 'preview',
                //             'image_url' => $_FILES["img_url_1"]['name']
                //         );
                //     } else {
                //         $whereFields = array(
                //             'a.id' => $dt_post["img_url_id_1"],
                //             'a.status' => 'live',
                //         );
                //         $this->$model_media->set_variable('whereFields', $whereFields);
                //         $output = $this->$model_media->read(0, false);
                //         $whereFields = array();
                //         $dataToDbImageExport = array(
                //             'id' => $output[0]->id,
                //             'status' => 'preview',
                //             'image_url' => $output[0]->image_url
                //         );
                //     }
                //     //Image to media
                //     if (!empty($_FILES["h_img_url_1"])) {
                //         $dataToDbImageManufacture = array(
                //             'id' => $dt_post["h_img_url_id_1"],
                //             'status' => 'preview',
                //             'image_url' => $_FILES["h_img_url_1"]['name']
                //         );
                //     } else {
                //         $whereFields = array(
                //             'a.id' => $dt_post["h_img_url_id_1"],
                //             'a.status' => 'live',
                //         );
                //         $this->$model_media->set_variable('whereFields', $whereFields);
                //         $output = $this->$model_media->read(0, false);
                //         $whereFields = array();
                //         $dataToDbImageManufacture = array(
                //             'id' => $output[0]->id,
                //             'status' => 'preview',
                //             'image_url' => $output[0]->image_url
                //         );
                //     }
                //     //Image to media

                //     $dataToDbExport = array(
                //         'id' => 1, 
                //         'status' => 'preview',
                //         'title' => $dt_post["title"],
                //         'title_en' => $dt_post["title_en"],
                //         'subtitle' => $dt_post["subtitle"],
                //         'subtitle_en' => $dt_post["subtitle_en"],
                //         'paraghraph_1' => $dt_post["paraghraph_1"],
                //         'paraghraph_2' => $dt_post["paraghraph_2"],
                //         'paraghraph_3' => $dt_post["paraghraph_3"],
                //         'paraghraph_1_en' => $dt_post["paraghraph_1_en"],
                //         'paraghraph_2_en' => $dt_post["paraghraph_2_en"],
                //         'paraghraph_3_en' => $dt_post["paraghraph_3_en"],
                //         'contact' => $dt_post["contact"],
                //         'contact_en' => $dt_post["contact_en"],
                //         'updated_at' => date('Y-m-d H:i:s')
                //     );

                //     $whereFields = array(
                //         'a.id' => 1,
                //         'a.status' => 'live',
                //     );
                //     $this->$model_manufacture->set_variable('whereFields', $whereFields);
                //     $outputmanufac = $this->$model_manufacture->read(0, false);
                //     $whereFields = array();
                //     $dataToDbImageManufac = array(
                //         'image_url' => $outputmanufac[0]->image_url
                //     );
                //     $dataToDbManufacture = array(
                //         'id' => 1, 
                //         'status' => 'preview',
                //         'title' => $dt_post["h_title"],
                //         'title_en' => $dt_post["h_title_en"],
                //         'subtitle' => $dt_post["h_subtitle"],
                //         'subtitle_en' => $dt_post["h_subtitle_en"],
                //         'paraghraph_1' => $dt_post["h_paraghraph_1"],
                //         'paraghraph_2' => $dt_post["h_paraghraph_2"],
                //         'paraghraph_3' => $dt_post["h_paraghraph_3"],
                //         'paraghraph_1_en' => $dt_post["h_paraghraph_1_en"],
                //         'paraghraph_2_en' => $dt_post["h_paraghraph_2_en"],
                //         'paraghraph_3_en' => $dt_post["h_paraghraph_3_en"],
                //         'par_list_2' => $this->tojson($dt_post["par_list_2"]),
                //         'par_list_3' => $this->tojson_form($dt_post["par_list_3"]),
                //         'par_list_2_en' => $this->tojson($dt_post["par_list_2_en"]),
                //         'par_list_3_en' => $this->tojson_form($dt_post["par_list_3_en"]),
                //         'updated_at' => date('Y-m-d H:i:s')
                //     );
                //     $dataToDbManufacture = array_merge($dataToDbManufacture,$dataToDbImageManufac);
                //     $this->$model_media->replace($dataToDbImageExport);
                //     $this->$model_media->replace($dataToDbImageManufacture);
                //     // var_dump($dataToDbManufacture);
                //     $id = $this->$model_export->replace($dataToDbExport);
                //     $id = $this->$model_manufacture->replace($dataToDbManufacture);
                // } 
                if ($dt_post['save_as'] == 'live' || true) {
                    //Image to media
                    if (!empty($_FILES["img_url_1"])) {
                        $dataToDbImageExport = array(
                            'id' => $dt_post["img_url_id_1"],
                            'status' => 'live',
                            'image_url' => $_FILES["img_url_1"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => $dt_post["img_url_id_1"],
                            'a.status' => 'live',
                        );
                        $this->$model_media->set_variable('whereFields', $whereFields);
                        $output = $this->$model_media->read(0, false);
                        $whereFields = array();
                        $dataToDbImageExport = array(
                            'id' => $output[0]->id,
                            'status' => 'live',
                            'image_url' => $output[0]->image_url
                        );
                    }
                    //Image to media
                    if (!empty($_FILES["h_img_url_1"])) {
                        $dataToDbImageManufacture = array(
                            'id' => $dt_post["h_img_url_id_1"],
                            'status' => 'live',
                            'image_url' => $_FILES["h_img_url_1"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => $dt_post["h_img_url_id_1"],
                            'a.status' => 'live',
                        );
                        $this->$model_media->set_variable('whereFields', $whereFields);
                        $output = $this->$model_media->read(0, false);
                        $whereFields = array();
                        $dataToDbImageManufacture = array(
                            'id' => $output[0]->id,
                            'status' => 'live',
                            'image_url' => $output[0]->image_url
                        );
                    }
                    //Image to media

                    $dataToDbExport = array(
                        'id' => 1, 
                        'status' => 'live',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"],
                        'subtitle' => $dt_post["subtitle"],
                        'subtitle_en' => $dt_post["subtitle_en"],
                        'paraghraph_1' => $dt_post["paraghraph_1"],
                        'paraghraph_2' => $dt_post["paraghraph_2"],
                        'paraghraph_3' => $dt_post["paraghraph_3"],
                        'paraghraph_1_en' => $dt_post["paraghraph_1_en"],
                        'paraghraph_2_en' => $dt_post["paraghraph_2_en"],
                        'paraghraph_3_en' => $dt_post["paraghraph_3_en"],
                        'contact' => $dt_post["contact"] ?? null,
                        'contact_en' => $dt_post["contact_en"] ?? null,
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    if (!empty($_FILES["h_img_url"])) {
                        $dataToDbImageManufac = array(
                            'image_url' => $_FILES["h_img_url"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 1,
                            'a.status' => 'live',
                        );
                        $this->$model_manufacture->set_variable('whereFields', $whereFields);
                        $outputmanufac = $this->$model_manufacture->read(0, false);
                        $whereFields = array();
                        $dataToDbImageManufac = array(
                            'image_url' => $outputmanufac[0]->image_url
                        );
                    }
                    $dataToDbManufacture = array(
                        'id' => 1, 
                        'status' => 'live',
                        'title' => $dt_post["h_title"],
                        'title_en' => $dt_post["h_title_en"],
                        'subtitle' => $dt_post["h_subtitle"],
                        'subtitle_en' => $dt_post["h_subtitle_en"],
                        'paraghraph_1' => $dt_post["h_paraghraph_1"],
                        'paraghraph_2' => $dt_post["h_paraghraph_2"],
                        'paraghraph_3' => $dt_post["h_paraghraph_3"],
                        'paraghraph_1_en' => $dt_post["h_paraghraph_1_en"],
                        'paraghraph_2_en' => $dt_post["h_paraghraph_2_en"],
                        'paraghraph_3_en' => $dt_post["h_paraghraph_3_en"],
                        'par_list_2' => $this->tojson($dt_post["par_list_2"]),
                        'par_list_3' => $this->tojson_form($dt_post["par_list_3"]),
                        'par_list_2_en' => $this->tojson($dt_post["par_list_2_en"]),
                        'par_list_3_en' => $this->tojson_form($dt_post["par_list_3_en"]),
                        'contact' => $dt_post["manufacture_contact"] ?? null,
                        'contact_en' => $dt_post["manufacture_contact_en"] ?? null,
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $dataToDbManufacture = array_merge($dataToDbManufacture,$dataToDbImageManufac);
                    $this->$model_media->replace($dataToDbImageExport);
                    $this->$model_media->replace($dataToDbImageManufacture);
                    $id = $this->$model_export->replace($dataToDbExport);
                    $id = $this->$model_manufacture->replace($dataToDbManufacture);
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

    private function tojson_form ($array = array()) {
        $data_return = array();
        $data_array = json_decode($array);
        $no = 1;
        for ($i=0; $i < count($data_array); $i++) { 
            if ($data_array[$i]->name != "") {
                $data_return[$i] = array(
                    'id' => $no++,
                    'name' => $data_array[$i]->name,
                    'form' => $data_array[$i]->form
                );
            }
        }
        return json_encode($data_return, JSON_PRETTY_PRINT);
    }  

    private function tojson_distri ($array = array()) {
        $data_return = array();
        $data_array = json_decode($array);
        $no = 1;
        for ($i=0; $i < count($data_array); $i++) { 
            if ($data_array[$i]->name != "") {
                $data_return[$i] = array(
                    'address' => $data_array[$i]->address,
                    'name' => $data_array[$i]->name
                );
            }
        }
        return json_encode($data_return, JSON_PRETTY_PRINT);
    }  

	public function update_distribution_post($id = 0) {
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
				$model = "Business_distribution";
				/* Start Transaction */
				$this->db->trans_start();

                $checkingLongLat = $this->$model->checkingLogLat($dt_post['latitude'],$dt_post['longitude']);
                if(count($checkingLongLat) > 0){
                    foreach ($checkingLongLat as $key => $value) {
                        $countDataCheking = 0;
                        if ($id != $value["id"]) {
                            $countDataCheking += 1;;
                        }
                        if ($id == $value["id"]) {
                            $countDataCheking = 0;
                            break;
                        }
                    }
                    if ($countDataCheking > 0) {
                        $response_data = array(
                            'error'   => "Duplicate Latitude Longitude"
                        );
                        $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
                        return;    
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
                //         'city_name' => $dt_post["city_name"],
                //         'city_name_en' => $dt_post["city_name_en"], 
                //         'latitude' => $dt_post["latitude"], 
                //         'longitude' => $dt_post["longitude"], 
                //         'type' => $dt_post["type"], 
                //         'companies' => $this->tojson_distri($dt_post["companies"]),
                //         'companies_en' => $this->tojson_distri($dt_post["companies_en"]), 
                //     );
                //     $dataToDb = array_merge($dataToDb,$dataToDbID);
                // }
                if ($dt_post['save_as'] == 'live' || true) {
                    $dataToDb = array(
                        'status' => 'live',
                        'city_name' => $dt_post["city_name"],
                        'city_name_en' => $dt_post["city_name_en"], 
                        'latitude' => $dt_post["latitude"], 
                        'longitude' => $dt_post["longitude"], 
                        'type' => $dt_post["type"], 
                        'companies' => $this->tojson_distri($dt_post["companies"]),
                        'companies_en' => $this->tojson_distri($dt_post["companies_en"]), 
                    );
                    $dataToDb = array_merge($dataToDb,$dataToDbID);
                }
                if ($id == 0 && $id == '') {
				    $id = $this->$model->create($dataToDb);
                    if ($id) {
                        $dataToDbPreview = array(
                            'id' => $id,
                            'status' => 'live',
                            'city_name' => $dt_post["city_name"],
                            'city_name_en' => $dt_post["city_name_en"], 
                            'latitude' => $dt_post["latitude"], 
                            'longitude' => $dt_post["longitude"], 
                            'type' => $dt_post["type"], 
                            'companies' => $this->tojson_distri($dt_post["companies"]),
                            'companies_en' => $this->tojson_distri($dt_post["companies_en"]), 
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

	public function delete_distribution_post($id = 0) {
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
				$model = "Business_distribution";
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

	public function update_mitra_post() {
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
				$model_media = "Media";
                $type = "business_mitra";
				/* Start Transaction */
                // echo "<pre>";
                // var_dump($dt_post);
                // var_dump($_FILES);
                // echo "</pre>";
                // die();
				$this->db->trans_start();
                if ($dt_post['save_as'] == 'live') {
                    $whereFields = array('a.type' => $type,'a.status' => 'live');
                    $this->$model_media->set_variable('whereFields', $whereFields);
                    $output_media = $this->$model_media->read(0, false);
                    $whereFields = array();
                } else {
                    $whereFields = array('a.type' => $type,'a.status' => 'live');
                    $this->$model_media->set_variable('whereFields', $whereFields);
                    $output_media = $this->$model_media->read(0, false);
                    $whereFields = array();
                }

                
                foreach ($output_media as $key => $value) {
                    if (!empty($_FILES["img_url_{$value->id}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_c_{$value->id}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_{$value->id}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_{$value->id}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_{$value->id}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }
                // for ($i = count($output_media); $i < $dt_post['count_item']; $i++) { 
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
                        if ( $this->photo_upload->do_upload("$key") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["$key"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["$key"]['name'], $error['error']);
                            return;
                        }
                    }
                }

                // if ($dt_post['save_as'] == 'preview') {
                //         $whereFields = array();
                //         $dataToDbMediaU = array();
                //         $dataToDbMediaN = array();
                //         $dataToDbMediaNEW = array();
                //         foreach ($output_media as $key => $value) {
                //             if (!empty($_FILES["img_url_{$value->id}"])) {
                //                 if (isset($dt_post["id_$value->id"]) && $dt_post["id_{$value->id}"] != '') {
                //                     $dataToDbMediaU[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                //                         'status' => 'preview'
                //                     );
                //                 } else {
                //                     $dataToDbMediaU[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                //                         'status' => 'delete'
                //                     );
                //                     $whereFields = array('id' => $value->id,'type' => $type,'status' => 'preview');
                //                     $this->$model_media->removetype($value->id,$whereFields);
                //                     $whereFields = array();
                //                 }
                //             } else {
                //                 if (isset($dt_post["id_$value->id"]) && $dt_post["id_$value->id"] != '') {
                //                     $dataToDbMediaN[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $value->image_url,
                //                         'status' => 'preview'
                //                     );
                //                 } else {
                //                     $dataToDbMediaN[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $value->image_url,
                //                         'status' => 'delete'
                //                     );
                //                     $whereFields = array('id' => $value->id,'type' => $type,'status' => 'preview');
                //                     $this->$model_media->removetype($value->id,$whereFields);
                //                     $whereFields = array();
                //                 }
                //             }
                //         }
                //         for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                //             if (!empty($_FILES["img_url_new_{$i}"])){
                //                 if ($dt_post["id_new_{$i}"] == '') {
                //                     $dataToDbMediaNEW = array(
                //                         'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                //                         'type' => $type,
                //                         'imgvid' => 'img',
                //                         'page_id' => 4,
                //                         'status' => 'preview'
                //                     );
                //                     $this->$model_media->replace($dataToDbMediaNEW);
                //                 }
                //             }
                //         } 
                //         $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                //     //V
                //     $this->$model_media->replace_batch($dataToDbMedia);
                // } 
                if ($dt_post['save_as'] == 'live' || true) {
                    //V
                        $dataToDbMediaU = array();
                        $dataToDbMediaN = array();
                        $dataToDbMediaNEW = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if (isset($dt_post["id_$value->id"]) &&  $dt_post["id_{$value->id}"] != '') {
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
                                    $whereFields = array('id' => $value->id,'type' => $type,'status' => 'live');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            } else {
                                if (isset($dt_post["id_$value->id"]) && $dt_post["id_{$value->id}"] != '') {
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
                                    $whereFields = array('id' => $value->id,'type' => $type,'status' => 'live');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            }
                        }
                        // for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                        //     if (!empty($_FILES["img_url_new_{$i}"])){
                        //         if ($dt_post["id_new_{$i}"] == '') {
                        //             $dataToDbMediaNEW = array(
                        //                 'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                        //                 'type' => $type,
                        //                 'imgvid' => 'img',
                        //                 'page_id' => 4,
                        //                 'status' => 'live'
                        //             );
                        //             $this->$model_media->replace($dataToDbMediaNEW);
                        //         }
                        //     }
                        // } 
                        foreach($_FILES as $key => $value){
                            if(strpos($key, 'img_url_new') !== false) {
                                $i = substr($key, strlen('img_url_new_'));
                                $dataToDbMediaNEW = array(
                                    'image_url' => $_FILES[$key]['name'],
                                    'type' => $type,
                                    'imgvid' => 'img',
                                    'page_id' => 2,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'status' => 'live'
                                );
                                $this->$model_media->replace($dataToDbMediaNEW); 
                                $whereFields = array();
                            }
                        }
                        // if ($dt_post["save_as"] == 'live') {
                        //     $this->$model_media->DeleteByType($type,"delete"); 
                        //     $this->$model_media->updateStatusByType($type,"live");
                        // }
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //V
                    $this->$model_media->replace_batch($dataToDbMedia);

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
						'id'     => 0
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