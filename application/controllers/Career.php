<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Career extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Model
        $this->load->model("cms/Career_model", "Career");
        $this->load->model("cms/Career_categorys_model", "Career_categorys");
        $this->load->model("cms/Career_join_model", "Career_join");
        $this->load->model("cms/Career_data_model", "Career_data");
        $this->load->model("cms/Page_model", "Page");
        $this->load->model("cms/Media_model", "Media");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function all_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = $this->name;
        $model_page  = "Page";
        $model_categorys  = "{$this->name}_categorys";
        $model_join  = "{$this->name}_join";
        $url = $this->UrlModel->read_url()[0];
        $whereFields = array(
            'a.status' => $q_strings['status'],
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model->set_variable('whereFields', $whereFields);
        $this->$model->set_variable('orderFields', $orderFields);
        $output_career = $this->$model->read();
        $whereFields = array();
        $orderFields = array();
        for ($i=0; $i < count($output_career); $i++) {
            $output_career[$i]->image_url = ($output_career[$i]->image_url != null) ? IPSERVER.$output_career[$i]->image_url : NULL;
        }
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.pages' => 'Karir',
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_page->set_variable('whereFields', $whereFields);
        $this->$model_page->set_variable('orderFields', $orderFields);
        $output_page = $this->$model_page->read()[0];
        $whereFields = array();
        $orderFields = array();
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.is_delete' => 0,
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_categorys->set_variable('whereFields', $whereFields);
        $this->$model_categorys->set_variable('orderFields', $orderFields);
        $output_categorys = $this->$model_categorys->read();
        $whereFields = array();
        $orderFields = array();
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.is_delete' => 0,
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_join->set_variable('whereFields', $whereFields);
        $this->$model_join->set_variable('orderFields', $orderFields);
        $output_join = $this->$model_join->read();
        for ($i=0; $i < count($output_join); $i++) {
            $output_join[$i]->deadline = ($output_join[$i]->deadline != null) ? date('d-m-Y', strtotime($output_join[$i]->deadline)) : NULL;
            $output_join[$i]->description = ($output_join[$i]->description != null) ? json_decode($output_join[$i]->description) : NULL;
            $output_join[$i]->description_en = ($output_join[$i]->description_en != null) ? json_decode($output_join[$i]->description_en) : NULL;
            $output_join[$i]->requierement = ($output_join[$i]->requierement != null) ? json_decode($output_join[$i]->requierement) : NULL;
            $output_join[$i]->requierement_en = ($output_join[$i]->requierement_en != null) ? json_decode($output_join[$i]->requierement_en) : NULL;
        }
        $output = array('career'=>$output_career,'page'=>$output_page,'categorys'=>$output_categorys,'join'=>$output_join);
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
    }

	public function detail_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = "{$this->name}_join";
        $url = $this->UrlModel->read_url()[0];
        $whereFields = array(
            'a.id' => $q_strings['id'],
            'a.is_delete' => 0,
            'a.status' => "live",
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model->set_variable('whereFields', $whereFields);
        $this->$model->set_variable('orderFields', $orderFields);
        $output = $this->$model->read();
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
    }

    

	public function submit_post() {
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
                // echo "<pre>";
                // var_dump($dt_post);
                // echo "</pre>";
                // die();
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

                if (!empty($_FILES["cv_url"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = 'pdf|jpg|png|jpeg';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "cv_url_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("cv_url") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["cv_url"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["cv_url"]['name'], $error['error']);
                        return;
                    }
                endif;
                $dataToDb = array(
                    'join_id'=> $dt_post['join_id'],
                    'name'=> $dt_post['name'],
                    'gender'=> $dt_post['gender'],
                    'birth_place'=> $dt_post['birth_place'],
                    'birth_date'=> $dt_post['birth_date'],
                    'religion'=> $dt_post['religion'],
                    'married'=> $dt_post['married'],
                    'education'=> $dt_post['education'],
                    'address'=> $dt_post['address'],
                    'province'=> $dt_post['province'],
                    'regency'=> $dt_post['regency'],
                    'district'=> $dt_post['district'],
                    'village'=> $dt_post['village'],
                    'email'=> $dt_post['email'],
                    'mobile'=> $dt_post['mobile'],
                    'mobile_sec'=> $dt_post['mobile_sec'],
                    'experience'=> $dt_post['experience'],
                    'last_company'=> $dt_post['last_company'],
                    'last_job'=> $dt_post['last_job'],
                    'last_sallary'=> $dt_post['last_sallary'],
                    'photo'=> $_FILES["img_url"]['name'],
                    'cv'=> $_FILES["cv_url"]['name'],
                );
                $id = $this->Career_data->create($dataToDb);
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