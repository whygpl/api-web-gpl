<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class About extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/Abouts_model", "Abouts");
        $this->load->model("$this->directory/Media_model", "Media");
        $this->load->model("$this->directory/About_company_profiles_model", "About_company_profiles");
        $this->load->model("$this->directory/About_company_histories_model", "About_company_histories");
        $this->load->model("$this->directory/About_president_model", "About_president");
        $this->load->model("$this->directory/About_vision_model", "About_vision");
        $this->load->model("$this->directory/About_mission_model", "About_mission");
        $this->load->model("$this->directory/About_value_model", "About_value");
        $this->load->model("$this->directory/About_certification_model", "About_certification");
        $this->load->model("$this->directory/About_office_model", "About_office");
        $this->load->model("$this->directory/About_factory_model", "About_factory");
        $this->load->model("$this->directory/About_award_model", "About_award");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function head_get($p = 0){
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
                'a.id' => 2,
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
        } else {
            $response_data = 'Invalid Token.';
            $this->REST_Return(401, $is_valid_token['message'] ,$response_data);
            return;
        }
	}

	public function company_get($p = 0){
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
                'a.id' => 1,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->About_company_profiles->set_variable('whereFields', $whereFields);
            $this->About_company_profiles->set_variable('orderFields', $orderFields);
            $output_profiles = $this->About_company_profiles->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.id' => 1,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->About_company_histories->set_variable('whereFields', $whereFields);
            $this->About_company_histories->set_variable('orderFields', $orderFields);
            $output_histories = $this->About_company_histories->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'about_company_profile'
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
            $output_profiles->image_urls = $about_images;
            $output_profiles->image_url = ($output_profiles->image_url != null) ? IPSERVER.$output_profiles->image_url : NULL;
            $output_histories->image_url = ($output_histories->image_url != null) ? IPSERVER.$output_histories->image_url : NULL;
            $output = array('profiles'=>$output_profiles,'histories'=>$output_histories);
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

	public function president_get($p = 0){
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
                'a.id' => 1,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->About_president->set_variable('whereFields', $whereFields);
            $this->About_president->set_variable('orderFields', $orderFields);
            $output = $this->About_president->read()[0];
            $whereFields = array();
            $orderFields = array();
            $output->image_url = ($output->image_url != null) ? IPSERVER.$output->image_url : NULL;
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

	public function vision_get($p = 0){
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
            $this->About_vision->set_variable('whereFields', $whereFields);
            $this->About_vision->set_variable('orderFields', $orderFields);
            $output_vision = $this->About_vision->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.id' => 1,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->About_mission->set_variable('whereFields', $whereFields);
            $this->About_mission->set_variable('orderFields', $orderFields);
            $output_mission = $this->About_mission->read()[0];
            $whereFields = array();
            $orderFields = array();
            $output_vision->image_url_sm = ($output_vision->image_url_sm != null) ? IPSERVER.$output_vision->image_url_sm : NULL;
            $output_vision->image_url_md = ($output_vision->image_url_md != null) ? IPSERVER.$output_vision->image_url_md : NULL;
            $output_vision->image_url_lg = ($output_vision->image_url_lg != null) ? IPSERVER.$output_vision->image_url_lg : NULL;
            $output_mission->image_url_sm = ($output_mission->image_url_sm != null) ? IPSERVER.$output_mission->image_url_sm : NULL;
            $output_mission->image_url_md = ($output_mission->image_url_md != null) ? IPSERVER.$output_mission->image_url_md : NULL;
            $output_mission->image_url_lg = ($output_mission->image_url_lg != null) ? IPSERVER.$output_mission->image_url_lg : NULL;
            $output = array('vision'=>$output_vision,'mission'=>$output_mission);
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

	public function value_get($p = 0){
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
            $this->About_value->set_variable('whereFields', $whereFields);
            $this->About_value->set_variable('orderFields', $orderFields);
            $output = $this->About_value->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'about_company_value'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $about_image = $this->Media->read(0,false);
            $about_images = array();
            for ($i=0; $i < count($about_image); $i++) {
                ($about_image[$i]->image_url != null) ? array_push($about_images, array('id' => $about_image[$i]->id,'image_url' => IPSERVER.$about_image[$i]->image_url,'title' => $about_image[$i]->title,'title_en' => $about_image[$i]->title_en)) : array_push($about_images, array('image_url' => NULL));
            }
            $output->image_urls = $about_images;
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

	public function certification_get($p = 0){
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
            $this->About_certification->set_variable('whereFields', $whereFields);
            $this->About_certification->set_variable('orderFields', $orderFields);
            $output = $this->About_certification->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'about_certifications'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $about_image = $this->Media->read(0,false);
            $about_images = array();
            for ($i=0; $i < count($about_image); $i++) {
                ($about_image[$i]->image_url != null) ? array_push($about_images, array('id' => $about_image[$i]->id,'image_url' => IPSERVER.$about_image[$i]->image_url)) : array_push($about_images, array('image_url' => NULL));
            }
            $output->image_urls = $about_images;
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

	public function office_get($p = 0){
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
            $this->About_office->set_variable('whereFields', $whereFields);
            $this->About_office->set_variable('orderFields', $orderFields);
            $output_office = $this->About_office->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.id' => 1,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->About_factory->set_variable('whereFields', $whereFields);
            $this->About_factory->set_variable('orderFields', $orderFields);
            $output_factory = $this->About_factory->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'about_office'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $office_image = $this->Media->read(0,false);
            $office_images = array();
            for ($i=0; $i < count($office_image); $i++) {
                ($office_image[$i]->image_url != null) ? array_push($office_images, array('id' => $office_image[$i]->id,'image_url' => IPSERVER.$office_image[$i]->image_url)) : array_push($office_images, array('image_url' => NULL));
            }
            $output_office->image_urls = $office_images;
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'about_factory'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $factory_image = $this->Media->read(0,false);
            $factory_images = array();
            for ($i=0; $i < count($factory_image); $i++) {
                ($factory_image[$i]->image_url != null) ? array_push($factory_images, array('id' => $factory_image[$i]->id,'image_url' => IPSERVER.$factory_image[$i]->image_url)) : array_push($factory_images, array('image_url' => NULL));
            }
            $output_factory->image_urls = $factory_images;
            $output = array('office'=>$output_office,'factory'=>$output_factory);
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

	public function award_get($p = 0){
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
            $this->About_award->set_variable('whereFields', $whereFields);
            $this->About_award->set_variable('orderFields', $orderFields);
            $output = $this->About_award->read()[0];
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.status' => 'live',
                'a.type' => 'about_awards'
            );
            $orderFields = array(
                'a.id' => 'ASC',
            );
            $this->Media->set_variable('orderFields', $orderFields);
            $this->Media->set_variable('whereFields', $whereFields);
            $about_image = $this->Media->read(0,false);
            $about_images = array();
            for ($i=0; $i < count($about_image); $i++) {
                ($about_image[$i]->image_url != null) ? array_push($about_images, array('id' => $about_image[$i]->id,'image_url' => IPSERVER.$about_image[$i]->image_url)) : array_push($about_images, array('image_url' => NULL));
            }
            $output->image_urls = $about_images;
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
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        $dataToDb = array(
                            'id' => 2, 
                            'status' => 'preview',
                            'page_title' => $dt_post["page_title"],
                            'page_title_en' => $dt_post["page_title_en"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'youtube_url' => $dt_post["youtube_url"], 
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $id = $this->$model->replace($dataToDb);
                    } else {
                        $dataToDbPreview = array(
                            'id' => 2, 
                            'status' => 'preview',
                            'page_title' => $dt_post["page_title"],
                            'page_title_en' => $dt_post["page_title_en"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'youtube_url' => $dt_post["youtube_url"], 
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $dataToDbLive = array(
                            'id' => 2, 
                            'status' => 'live',
                            'page_title' => $dt_post["page_title"],
                            'page_title_en' => $dt_post["page_title_en"],
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'youtube_url' => $dt_post["youtube_url"], 
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $id = $this->$model->replace($dataToDbPreview);
                        $id = $this->$model->replace($dataToDbLive);
                    }
				}
                // echo "<pre>";
                // // var_dump($_FILES);
                // var_dump($dataToDb);die();
                // // var_dump($dt_post["title_${i}"]);
                // echo "</pre>";
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

	public function update_company_post() {
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
                // var_dump($_FILES);
                // echo "</pre>";
                // die();
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

                if (!empty($_FILES["img_url_h"])):
                    $config['upload_path'] = './assets/media/images';
                    $config['allowed_types'] = '*';
                    $config['overwrite'] = true;	
                    $config['file_name'] = "img_url_h_".time();
                    $this->load->library('upload', $config, 'photo_upload');
                    $this->photo_upload->initialize($config);
                    if ( $this->photo_upload->do_upload("img_url_h") ) {
                        $fileData = $this->photo_upload->data();
                        $_FILES["img_url_h"]['name'] =  $fileData['file_name'];
                    } else {
                        $error = array('error' => $this->photo_upload->display_errors());
                        $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_h"]['name'], $error['error']);
                        return;
                    }
                endif;
                // if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                //     for ($i=1; $i <= 3; $i++) { 
                //         $dataToDbImage[$i] = array();
                //         if (!empty($_FILES["img_url_${i}"])) {
                //             $dataToDbImage[$i] = array(
                //                 'id' => $dt_post["img_url_id_${i}"],
                //                 'status' => 'preview',
                //                 'image_url' => $_FILES["img_url_${i}"]['name']
                //             );
                //         } else {
                //             $whereFields = array(
                //                 'a.id' => $dt_post["img_url_id_${i}"],
                //                 'a.status' => 'live',
                //                 'a.type' => 'about_company_profile'
                //             );
                //             $this->Media->set_variable('whereFields', $whereFields);
                //             $output = $this->Media->read(0, false);
                //             $dataToDbImage[$i] = array(
                //                 'id' => $dt_post["img_url_id_${i}"],
                //                 'status' => 'preview',
                //                 'image_url' => $output[0]->image_url
                //             );
                //         }
                //     }
                //     if (!empty($_FILES["img_url"])) {
                //         $dataToDbImageProfile = array(
                //             'id' => 1,
                //             'status' => 'preview',
                //             'image_url' => $_FILES["img_url"]['name']
                //         );
                //     } else {
                //         $whereFields = array(
                //             'a.id' => 1,
                //             'a.status' => 'live',
                //         );
                //         $this->About_company_profiles->set_variable('whereFields', $whereFields);
                //         $output = $this->About_company_profiles->read(0, false);
                //         $dataToDbImageProfile = array(
                //             'id' => 1,
                //             'status' => 'preview',
                //             'image_url' => $output[0]->image_url
                //         );
                //     }
                //     if (!empty($_FILES["img_url_h"])) {
                //         $dataToDbImageHistori = array(
                //             'id' => 1,
                //             'status' => 'preview',
                //             'image_url' => $_FILES["img_url_h"]['name']
                //         );
                //     } else {
                //         $whereFields = array(
                //             'a.id' => 1,
                //             'a.status' => 'live',
                //         );
                //         $this->About_company_histories->set_variable('whereFields', $whereFields);
                //         $output = $this->About_company_histories->read(0, false);
                //         $dataToDbImageHistori = array(
                //             'id' => 1,
                //             'status' => 'preview',
                //             'image_url' => $output[0]->image_url
                //         );
                //     }
                //     $dataToDbProfile = array(
                //         'id' => 1, 
                //         'status' => 'preview',
                //         'title' => $dt_post["title"],
                //         'title_en' => $dt_post["title_en"], 
                //         'subtitle' => $dt_post["subtitle"],
                //         'subtitle_en' => $dt_post["subtitle_en"],
                //         'paragraph_1' => $dt_post["paragraph_1"],
                //         'paragraph_2' => $dt_post["paragraph_2"],
                //         'paragraph_3' => $dt_post["paragraph_3"],
                //         'paragraph_4' => $dt_post["paragraph_4"],
                //         'paragraph_5' => $dt_post["paragraph_5"],
                //         'paragraph_6' => $dt_post["paragraph_6"],
                //         'paragraph_1_en' => $dt_post["paragraph_1_en"],
                //         'paragraph_2_en' => $dt_post["paragraph_2_en"],
                //         'paragraph_3_en' => $dt_post["paragraph_3_en"],
                //         'paragraph_4_en' => $dt_post["paragraph_4_en"],
                //         'paragraph_5_en' => $dt_post["paragraph_5_en"],
                //         'paragraph_6_en' => $dt_post["paragraph_6_en"],
                //         'updated_at' => date('Y-m-d H:i:s')
                //     );
                //     $dataToDbHistori = array(
                //         'id' => 1, 
                //         'status' => 'preview',
                //         'title' => $dt_post["h_title"],
                //         'title_en' => $dt_post["h_title_en"], 
                //         'subtitle' => $dt_post["h_subtitle"],
                //         'subtitle_en' => $dt_post["h_subtitle_en"],
                //         'paragraph_1' => $dt_post["h_paragraph_1"],
                //         'paragraph_2' => $dt_post["h_paragraph_2"],
                //         'paragraph_1_en' => $dt_post["h_paragraph_1_en"],
                //         'paragraph_2_en' => $dt_post["h_paragraph_2_en"],
                //         'updated_at' => date('Y-m-d H:i:s')
                //     );
                //     $dataToDbProfile = array_merge($dataToDbProfile,$dataToDbImageProfile);
                //     $dataToDbHistori = array_merge($dataToDbHistori,$dataToDbImageHistori);
                //     $id = $this->About_company_profiles->replace($dataToDbProfile);
                //     $id = $this->About_company_histories->replace($dataToDbHistori);
                // } 
                
                if ($dt_post['save_as'] == 'live' || true) {
                    for ($i=1; $i <= 3; $i++) { 
                        $dataToDbImage[$i] = array();
                        if (!empty($_FILES["img_url_${i}"])) {
                            $dataToDbImage[$i] = array(
                                'id' => $dt_post["img_url_id_${i}"],
                                'status' => 'live',
                                'image_url' => $_FILES["img_url_${i}"]['name']
                            );
                        } else {
                            $whereFields = array(
                                'a.id' => $dt_post["img_url_id_${i}"],
                                'a.status' => 'live',
                                'a.type' => 'about_company_profile'
                            );
                            $this->Media->set_variable('whereFields', $whereFields);
                            $output = $this->Media->read(0, false);
                            $dataToDbImage[$i] = array(
                                'id' => $dt_post["img_url_id_${i}"],
                                'status' => 'live',
                                'image_url' => $output[0]->image_url
                            );
                        }
                    }
                    if (!empty($_FILES["img_url"])) {
                        $dataToDbImageProfileLive = array(
                            'id' => 1,
                            'status' => 'live',
                            'image_url' => $_FILES["img_url"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 1,
                            'a.status' => 'live',
                        );
                        $this->About_company_profiles->set_variable('whereFields', $whereFields);
                        $output = $this->About_company_profiles->read(0, false);
                        $dataToDbImageProfileLive = array(
                            'id' => 1,
                            'status' => 'live',
                            'image_url' => $output[0]->image_url
                        );
                    }
                    if (!empty($_FILES["img_url_h"])) {
                        $dataToDbImageHistoriLive = array(
                            'id' => 1,
                            'status' => 'live',
                            'image_url' => $_FILES["img_url_h"]['name']
                        );
                    } else {
                        $whereFields = array(
                            'a.id' => 1,
                            'a.status' => 'live',
                        );
                        $this->About_company_histories->set_variable('whereFields', $whereFields);
                        $output = $this->About_company_histories->read(0, false);
                        $dataToDbImageHistoriLive = array(
                            'id' => 1,
                            'status' => 'live',
                            'image_url' => $output[0]->image_url
                        );
                    }

                    $dataToDbProfileLive = array(
                        'id' => 1, 
                        'status' => 'live',
                        'title' => $dt_post["title"],
                        'title_en' => $dt_post["title_en"], 
                        'subtitle' => $dt_post["subtitle"],
                        'subtitle_en' => $dt_post["subtitle_en"],
                        'paragraph_1' => $dt_post["paragraph_1"],
                        'paragraph_2' => $dt_post["paragraph_2"],
                        'paragraph_3' => $dt_post["paragraph_3"],
                        'paragraph_4' => $dt_post["paragraph_4"],
                        'paragraph_5' => $dt_post["paragraph_5"],
                        'paragraph_6' => $dt_post["paragraph_6"],
                        'paragraph_1_en' => $dt_post["paragraph_1_en"],
                        'paragraph_2_en' => $dt_post["paragraph_2_en"],
                        'paragraph_3_en' => $dt_post["paragraph_3_en"],
                        'paragraph_4_en' => $dt_post["paragraph_4_en"],
                        'paragraph_5_en' => $dt_post["paragraph_5_en"],
                        'paragraph_6_en' => $dt_post["paragraph_6_en"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $dataToDbHistoriLive = array(
                        'id' => 1, 
                        'status' => 'live',
                        'title' => $dt_post["h_title"],
                        'title_en' => $dt_post["h_title_en"], 
                        'subtitle' => $dt_post["h_subtitle"],
                        'subtitle_en' => $dt_post["h_subtitle_en"],
                        'paragraph_1' => $dt_post["h_paragraph_1"],
                        'paragraph_2' => $dt_post["h_paragraph_2"],
                        'paragraph_1_en' => $dt_post["h_paragraph_1_en"],
                        'paragraph_2_en' => $dt_post["h_paragraph_2_en"],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $dataToDbProfileLive = array_merge($dataToDbProfileLive,$dataToDbImageProfileLive);
                    $dataToDbHistoriLive = array_merge($dataToDbHistoriLive,$dataToDbImageHistoriLive);
                    $id = $this->About_company_profiles->replace($dataToDbProfileLive);
                    $id = $this->About_company_histories->replace($dataToDbHistoriLive);
                }
                $this->Media->replace_batch($dataToDbImage);
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

	public function update_president_post() {
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
                    // if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                    //     if (!empty($_FILES["img_url"])) {
                    //         $dataToDbImage = array(
                    //             'id' => 1,
                    //             'status' => 'preview',
                    //             'image_url' => $_FILES["img_url"]['name']
                    //         );
                    //     } else {
                    //         $whereFields = array(
                    //             'a.id' => 1,
                    //             'a.status' => 'live',
                    //         );
                    //         $this->About_president->set_variable('whereFields', $whereFields);
                    //         $output = $this->About_president->read(0, false);
                    //         $dataToDbImage = array(
                    //             'id' => 1,
                    //             'status' => 'preview',
                    //             'image_url' => $output[0]->image_url
                    //         );
                    //     }
                    //     $dataToDb = array(
                    //         'id' => 1, 
                    //         'status' => 'preview',
                    //         'title' => $dt_post["title"],
                    //         'title_en' => $dt_post["title_en"], 
                    //         'greeting' => $dt_post["greeting"],
                    //         'greeting_en' => $dt_post["greeting_en"],
                    //         'messenger' => $dt_post["messenger"],
                    //         'messenger_en' => $dt_post["messenger_en"],
                    //         'job_title' => $dt_post["job_title"],
                    //         'job_title_en' => $dt_post["job_title_en"],
                    //         'paragraph_1' => $dt_post["paragraph_1"],
                    //         'paragraph_2' => $dt_post["paragraph_2"],
                    //         'paragraph_3' => $dt_post["paragraph_3"],
                    //         'paragraph_4' => $dt_post["paragraph_4"],
                    //         'paragraph_5' => $dt_post["paragraph_5"],
                    //         'paragraph_6' => $dt_post["paragraph_6"],
                    //         'paragraph_7' => $dt_post["paragraph_7"],
                    //         'paragraph_1_en' => $dt_post["paragraph_1_en"],
                    //         'paragraph_2_en' => $dt_post["paragraph_2_en"],
                    //         'paragraph_3_en' => $dt_post["paragraph_3_en"],
                    //         'paragraph_4_en' => $dt_post["paragraph_4_en"],
                    //         'paragraph_5_en' => $dt_post["paragraph_5_en"],
                    //         'paragraph_6_en' => $dt_post["paragraph_6_en"],
                    //         'paragraph_7_en' => $dt_post["paragraph_7_en"],
                    //         'updated_at' => date('Y-m-d H:i:s')
                    //     );
                    //     $dataToDb = array_merge($dataToDb,$dataToDbImage);
                    //     $id = $this->About_president->replace($dataToDb);
                    // } 
                    if ($dt_post['save_as'] == 'live' || true) {
                        if (!empty($_FILES["img_url"])) {
                            $dataToDbImageLive = array(
                                'id' => 1,
                                'status' => 'live',
                                'image_url' => $_FILES["img_url"]['name']
                            );
                        } else {
                            $whereFields = array(
                                'a.id' => 1,
                                'a.status' => 'live',
                            );
                            $this->About_president->set_variable('whereFields', $whereFields);
                            $output = $this->About_president->read(0, false);
                            $dataToDbImageLive = array(
                                'id' => 1,
                                'status' => 'live',
                                'image_url' => $output[0]->image_url
                            );
                        }
                        $dataToDbLive = array(
                            'id' => 1, 
                            'status' => 'live',
                            'title' => $dt_post["title"],
                            'title_en' => $dt_post["title_en"], 
                            'greeting' => $dt_post["greeting"],
                            'greeting_en' => $dt_post["greeting_en"],
                            'messenger' => $dt_post["messenger"],
                            'messenger_en' => $dt_post["messenger_en"],
                            'job_title' => $dt_post["job_title"],
                            'job_title_en' => $dt_post["job_title_en"],
                            'paragraph_1' => $dt_post["paragraph_1"],
                            'paragraph_2' => $dt_post["paragraph_2"],
                            'paragraph_3' => $dt_post["paragraph_3"],
                            'paragraph_4' => $dt_post["paragraph_4"],
                            'paragraph_5' => $dt_post["paragraph_5"],
                            'paragraph_6' => $dt_post["paragraph_6"],
                            'paragraph_7' => $dt_post["paragraph_7"],
                            'paragraph_1_en' => $dt_post["paragraph_1_en"],
                            'paragraph_2_en' => $dt_post["paragraph_2_en"],
                            'paragraph_3_en' => $dt_post["paragraph_3_en"],
                            'paragraph_4_en' => $dt_post["paragraph_4_en"],
                            'paragraph_5_en' => $dt_post["paragraph_5_en"],
                            'paragraph_6_en' => $dt_post["paragraph_6_en"],
                            'paragraph_7_en' => $dt_post["paragraph_7_en"],
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $dataToDbLive = array_merge($dataToDbLive,$dataToDbImageLive);
                        $id = $this->About_president->replace($dataToDbLive);
                    }
                // var_dump($dt_post["title_${i}"]);
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

	public function update_vm_post() {
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
				$model_v = "About_vision";
				$model_m = "About_mission";
				$model_media = "Media";
				/* Start Transaction */
                // echo "<pre>";
                // var_dump($dt_post);
                // echo "</pre>";
                // die();
				$this->db->trans_start();
                //V
                    if (!empty($_FILES["v_img_urlSM"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "v_img_url_SM".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("v_img_urlSM") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["v_img_urlSM"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["v_img_urlSM"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["v_img_urlMD"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "v_img_url_MD".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("v_img_urlMD") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["v_img_urlMD"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["v_img_urlMD"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["v_img_urlLG"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "v_img_url_LG".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("v_img_urlLG") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["v_img_urlLG"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["v_img_urlLG"]['name'], $error['error']);
                            return;
                        }
                    endif;
                //V
                //M
                    if (!empty($_FILES["m_img_urlSM"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "m_img_url_SM".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                         if ( $this->photo_upload->do_upload("m_img_urlSM") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["m_img_urlSM"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["m_img_urlSM"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["m_img_urlMD"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "m_img_url_MD".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("m_img_urlMD") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["m_img_urlMD"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["m_img_urlMD"]['name'], $error['error']);
                            return;
                        }
                    endif;
                    if (!empty($_FILES["m_img_urlLG"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "m_img_url_LG".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("m_img_urlLG") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["m_img_urlLG"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["m_img_urlLG"]['name'], $error['error']);
                            return;
                        }
                    endif;
                //M
                foreach ($dt_post as $key => $value) {
                    if ($dt_post['save_as'] == 'preview') {
                        //V
                            $dataToDbV = array(
                                'id' => 1,
                                'title' => $dt_post['v_title'],
                                'title_en' => $dt_post['v_title_en'],
                                'description' => $dt_post['v_description'],
                                'description_en' => $dt_post['v_description_en'],
                                'status' => 'preview'
                            );
                            $whereFields = array('a.id' => 1,'a.status' => 'live');
                            $this->$model_v->set_variable('whereFields', $whereFields);
                            $output = $this->$model_v->read(0, false);
                            $whereFields = array();
                            if (!empty($_FILES["v_img_urlSM"])) {
                                $dataToDbImageV = array('image_url_sm' => $_FILES["v_img_urlSM"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_sm' => $output[0]->image_url_sm);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                            if (!empty($_FILES["v_img_urlMD"])) {
                                $dataToDbImageV = array('image_url_md' => $_FILES["v_img_urlMD"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_md' => $output[0]->image_url_md);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                            if (!empty($_FILES["v_img_urlLG"])) {
                                $dataToDbImageV = array('image_url_lg' => $_FILES["v_img_urlLG"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_lg' => $output[0]->image_url_lg);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                        //V
                        //M
                            $m_item = array();
                            if (isset($dt_post['m_item'])) {
                                foreach (json_decode($dt_post['m_item'])->data as $key => $value) {
                                    if ($value->name != '') {
                                        $m_item[$key] = array(
                                            'id' => $key+1,
                                            'name' => $value->name
                                        );
                                    }
                                }
                            }
                            $m_item_en = array();
                            if (isset($dt_post['m_item_en'])) {
                                foreach (json_decode($dt_post['m_item_en'])->data as $key => $value) {
                                    if ($value->name != '') {
                                        $m_item_en[$key] = array(
                                            'id' => $key+1,
                                            'name' => $value->name
                                        );
                                    }
                                }
                            }
                            $dataToDbM = array(
                                'id' => 1,
                                'title' => $dt_post['m_title'],
                                'title_en' => $dt_post['m_title_en'],
                                'subtitle' => $dt_post['m_subtitle'],
                                'subtitle_en' => $dt_post['m_subtitle_en'],
                                'description' => $dt_post['m_description'],
                                'description_en' => $dt_post['m_description_en'],
                                'mission_item' => json_encode(array('data' => $m_item)),
                                'mission_item_en' => json_encode(array('data' => $m_item_en)),
                                'status' => 'preview'
                            );
                            $whereFields = array('a.id' => 1,'a.status' => 'live');
                            $this->$model_m->set_variable('whereFields', $whereFields);
                            $output = $this->$model_m->read(0, false);
                            $whereFields = array();
                            if (!empty($_FILES["m_img_urlSM"])) {
                                $dataToDbImageM = array('image_url_sm' => $_FILES["m_img_urlSM"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_sm' => $output[0]->image_url_sm);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                            if (!empty($_FILES["m_img_urlMD"])) {
                                $dataToDbImageM = array('image_url_md' => $_FILES["m_img_urlMD"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_md' => $output[0]->image_url_md);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                            if (!empty($_FILES["m_img_urlLG"])) {
                                $dataToDbImageM = array('image_url_lg' => $_FILES["m_img_urlLG"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_lg' => $output[0]->image_url_lg);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                        //M
                        // echo "<pre>";
                        // var_dump($dataToDbV);var_dump($dataToDbM);die();
                        $id = $this->$model_v->replace($dataToDbV);
                        $id = $this->$model_m->replace($dataToDbM);
                    } else {
                        //V
                            $dataToDbV = array(
                                'id' => 1,
                                'title' => $dt_post['v_title'],
                                'title_en' => $dt_post['v_title_en'],
                                'description' => $dt_post['v_description'],
                                'description_en' => $dt_post['v_description_en'],
                                'status' => 'live'
                            );
                            $whereFields = array('a.id' => 1,'a.status' => 'live');
                            $this->$model_v->set_variable('whereFields', $whereFields);
                            $output = $this->$model_v->read(0, false);
                            $whereFields = array();
                            if (!empty($_FILES["v_img_urlSM"])) {
                                $dataToDbImageV = array('image_url_sm' => $_FILES["v_img_urlSM"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_sm' => $output[0]->image_url_sm);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                            if (!empty($_FILES["v_img_urlMD"])) {
                                $dataToDbImageV = array('image_url_md' => $_FILES["v_img_urlMD"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_md' => $output[0]->image_url_md);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                            if (!empty($_FILES["v_img_urlLG"])) {
                                $dataToDbImageV = array('image_url_lg' => $_FILES["v_img_urlLG"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_lg' => $output[0]->image_url_lg);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                        //V
                        //M
                            $m_item = array();
                            if (isset($dt_post['m_item'])) {
                                foreach (json_decode($dt_post['m_item'])->data as $key => $value) {
                                    if ($value->name != '') {
                                        $m_item[$key] = array(
                                            'id' => $key+1,
                                            'name' => $value->name
                                        );
                                    }
                                }
                            }
                            $m_item_en = array();
                            if (isset($dt_post['m_item_en'])) {
                                foreach (json_decode($dt_post['m_item_en'])->data as $key => $value) {
                                    if ($value->name != '') {
                                        $m_item_en[$key] = array(
                                            'id' => $key+1,
                                            'name' => $value->name
                                        );
                                    }
                                }
                            }
                            $dataToDbM = array(
                                'id' => 1,
                                'title' => $dt_post['m_title'],
                                'title_en' => $dt_post['m_title_en'],
                                'subtitle' => $dt_post['m_subtitle'],
                                'subtitle_en' => $dt_post['m_subtitle_en'],
                                'description' => $dt_post['m_description'],
                                'description_en' => $dt_post['m_description_en'],
                                'mission_item' => json_encode(array('data' => $m_item)),
                                'mission_item_en' => json_encode(array('data' => $m_item_en)),
                                'status' => 'preview'
                            );
                            $whereFields = array('a.id' => 1,'a.status' => 'live');
                            $this->$model_m->set_variable('whereFields', $whereFields);
                            $output = $this->$model_m->read(0, false);
                            $whereFields = array();
                            if (!empty($_FILES["m_img_urlSM"])) {
                                $dataToDbImageM = array('image_url_sm' => $_FILES["m_img_urlSM"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_sm' => $output[0]->image_url_sm);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                            if (!empty($_FILES["m_img_urlMD"])) {
                                $dataToDbImageM = array('image_url_md' => $_FILES["m_img_urlMD"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_md' => $output[0]->image_url_md);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                            if (!empty($_FILES["m_img_urlLG"])) {
                                $dataToDbImageM = array('image_url_lg' => $_FILES["m_img_urlLG"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_lg' => $output[0]->image_url_lg);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                        //M
                        // echo "<pre>";
                        // var_dump($dataToDbV);var_dump($dataToDbM);die();
                        $id = $this->$model_v->replace($dataToDbV);
                        $id = $this->$model_m->replace($dataToDbM);

                        //V
                            $dataToDbV = array(
                                'id' => 1,
                                'title' => $dt_post['v_title'],
                                'title_en' => $dt_post['v_title_en'],
                                'description' => $dt_post['v_description'],
                                'description_en' => $dt_post['v_description_en'],
                                'status' => 'live'
                            );
                            $whereFields = array('a.id' => 1,'a.status' => 'live');
                            $this->$model_v->set_variable('whereFields', $whereFields);
                            $output = $this->$model_v->read(0, false);
                            $whereFields = array();
                            if (!empty($_FILES["v_img_urlSM"])) {
                                $dataToDbImageV = array('image_url_sm' => $_FILES["v_img_urlSM"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_sm' => $output[0]->image_url_sm);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                            if (!empty($_FILES["v_img_urlMD"])) {
                                $dataToDbImageV = array('image_url_md' => $_FILES["v_img_urlMD"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_md' => $output[0]->image_url_md);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                            if (!empty($_FILES["v_img_urlLG"])) {
                                $dataToDbImageV = array('image_url_lg' => $_FILES["v_img_urlLG"]['name']);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            } else {
                                $dataToDbImageV = array('image_url_lg' => $output[0]->image_url_lg);
                                $dataToDbV = array_merge($dataToDbV,$dataToDbImageV);
                            }
                        //V
                        //M
                            $m_item = array();
                            if (isset($dt_post['m_item'])) {
                                foreach (json_decode($dt_post['m_item'])->data as $key => $value) {
                                    if ($value->name != '') {
                                        $m_item[$key] = array(
                                            'id' => $key+1,
                                            'name' => $value->name
                                        );
                                    }
                                }
                            }
                            $m_item_en = array();
                            if (isset($dt_post['m_item_en'])) {
                                foreach (json_decode($dt_post['m_item_en'])->data as $key => $value) {
                                    if ($value->name != '') {
                                        $m_item_en[$key] = array(
                                            'id' => $key+1,
                                            'name' => $value->name
                                        );
                                    }
                                }
                            }
                            $dataToDbM = array(
                                'id' => 1,
                                'title' => $dt_post['m_title'],
                                'title_en' => $dt_post['m_title_en'],
                                'subtitle' => $dt_post['m_subtitle'],
                                'subtitle_en' => $dt_post['m_subtitle_en'],
                                'description' => $dt_post['m_description'],
                                'description_en' => $dt_post['m_description_en'],
                                'mission_item' => json_encode(array('data' => $m_item)),
                                'mission_item_en' => json_encode(array('data' => $m_item_en)),
                                'status' => 'live'
                            );
                            $whereFields = array('a.id' => 1,'a.status' => 'live');
                            $this->$model_m->set_variable('whereFields', $whereFields);
                            $output = $this->$model_m->read(0, false);
                            $whereFields = array();
                            if (!empty($_FILES["m_img_urlSM"])) {
                                $dataToDbImageM = array('image_url_sm' => $_FILES["m_img_urlSM"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_sm' => $output[0]->image_url_sm);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                            if (!empty($_FILES["m_img_urlMD"])) {
                                $dataToDbImageM = array('image_url_md' => $_FILES["m_img_urlMD"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_md' => $output[0]->image_url_md);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                            if (!empty($_FILES["m_img_urlLG"])) {
                                $dataToDbImageM = array('image_url_lg' => $_FILES["m_img_urlLG"]['name']);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            } else {
                                $dataToDbImageM = array('image_url_lg' => $output[0]->image_url_lg);
                                $dataToDbM = array_merge($dataToDbM,$dataToDbImageM);
                            }
                        //M
                        // echo "<pre>";
                        // var_dump($dataToDbV);var_dump($dataToDbM);die();
                        $id = $this->$model_v->replace($dataToDbV);
                        $id = $this->$model_m->replace($dataToDbM);
                    }
				}
                // var_dump($dt_post["title_${i}"]);
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

	public function update_value_post() {
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
				$model = "About_value";
				$model_media = "Media";
				/* Start Transaction */
                // echo "<pre>";
                // var_dump($dt_post);
                // echo "</pre>";
                // die();
				$this->db->trans_start();
                $whereFields = array('a.type' => 'about_company_value','a.status' => 'live');
                $this->$model_media->set_variable('whereFields', $whereFields);
                $output_media = $this->$model_media->read(0, false);
                $whereFields = array();
                foreach ($output_media as $key => $value) {
                    if (!empty($_FILES["img_url_{$value->id}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_cv_{$value->id}_".time();
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
                        // var_dump($_FILES["img_url_{$value->id}"]['name']);
                    endif;
                }
                // var_dump(count($output_media));
                // var_dump($dt_post['count_item']);
                for ($i = count($output_media); $i < $dt_post['count_item']; $i++) { 
                    // var_dump($i);
                    if (!empty($_FILES["img_url_new_{$i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_cv_new_{$i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_new_{$i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_new_{$i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_new_{$i}"]['name'], $error['error']);
                            return;
                        }
                        // var_dump($_FILES["img_url_new_{$i}"]['name']);
                    endif;
                }
                // die();
                $dataToDbMediaU = array();
                $dataToDbMediaN = array();
                $dataToDbMediaNEW = array();
                if ($dt_post['save_as'] == 'preview') {
                    //V
                        $dataToDb = array(
                            'id' => 1,
                            'title' => $dt_post['title'],
                            'title_en' => $dt_post['title_en'],
                            'description' => $dt_post['description'],
                            'description_en' => $dt_post['description_en'],
                            'status' => 'preview'
                        );
                        $whereFields = array('a.id' => 1,'a.status' => 'live');
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if ($dt_post["title_{$value->id}"] != '') {
                                    $dataToDbMediaU[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'preview'
                                    );
                                } else {
                                    $dataToDbMediaU[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => 'about_company_value','status' => 'preview');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            } else {
                                if ($dt_post["title_{$value->id}"] != '') {
                                    $dataToDbMediaN[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $value->image_url,
                                        'status' => 'preview'
                                    );
                                } else {
                                    $dataToDbMediaN[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $value->image_url,
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => 'about_company_value','status' => 'preview');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            }
                        }
                        for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                            if (!empty($_FILES["img_url_new_{$i}"])){
                                if ($dt_post["title_new_{$i}"] != '') {
                                    $dataToDbMediaNEW = array(
                                        'type' => 'about_company_value',
                                        'imgvid' => 'img',
                                        'page_id' => 2,
                                        'title' => $dt_post["title_new_{$i}"],
                                        'title_en' => $dt_post["title_en_new_{$i}"],
                                        'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                                        'status' => 'preview'
                                    );
                                    $this->$model_media->replace($dataToDbMediaNEW);
                                    // echo "<pre>";
                                    // var_dump($dataToDbMediaNEW);
                                }
                            }
                        } 
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //V
                    // echo "<pre>";
                    // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                    $id = $this->$model->replace($dataToDb);
                    $this->$model_media->replace_batch($dataToDbMedia);
                } else {
                        $dataToDb = array(
                            'id' => 1,
                            'title' => $dt_post['title'],
                            'title_en' => $dt_post['title_en'],
                            'description' => $dt_post['description'],
                            'description_en' => $dt_post['description_en'],
                            'status' => 'live'
                        );
                        $whereFields = array('a.id' => 1,'a.status' => 'live');
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if ($dt_post["title_{$value->id}"] != '') {
                                    $dataToDbMediaU[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'live'
                                    );
                                } else {
                                    $dataToDbMediaU[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => 'about_company_value','status' => 'preview');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            } else {
                                if ($dt_post["title_{$value->id}"] != '') {
                                    $dataToDbMediaN[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $value->image_url,
                                        'status' => 'live'
                                    );
                                } else {
                                    $dataToDbMediaN[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $value->image_url,
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => 'about_company_value','status' => 'preview');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            }
                        }
                        for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                            if (!empty($_FILES["img_url_new_{$i}"])){
                                if ($dt_post["title_new_{$i}"] != '') {
                                    $dataToDbMediaNEW = array(
                                        'type' => 'about_company_value',
                                        'imgvid' => 'img',
                                        'page_id' => 2,
                                        'title' => $dt_post["title_new_{$i}"],
                                        'title_en' => $dt_post["title_en_new_{$i}"],
                                        'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                                        'status' => 'preview'
                                    );
                                    $this->$model_media->replace($dataToDbMediaNEW);
                                }
                            }
                        } 
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //V
                    // echo "<pre>";
                    // var_dump($dataToDb);var_dump($dataToDbMedia);
                    $id = $this->$model->replace($dataToDb);
                    $this->$model_media->replace_batch($dataToDbMedia);
                    
                    $whereFields = array('a.type' => 'about_company_value','a.status' => 'live');
                    $this->$model_media->set_variable('whereFields', $whereFields);
                    $output_media = $this->$model_media->read(0, false);
                    $whereFields = array();
                    //V
                        $dataToDb = array(
                            'id' => 1,
                            'title' => $dt_post['title'],
                            'title_en' => $dt_post['title_en'],
                            'description' => $dt_post['description'],
                            'description_en' => $dt_post['description_en'],
                            'status' => 'live'
                        );
                        $whereFields = array('a.id' => 1,'a.status' => 'live');
                        $this->$model->set_variable('whereFields', $whereFields);
                        $output = $this->$model->read(0, false);
                        $whereFields = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if ($dt_post["title_{$value->id}"] != '') {
                                    $dataToDbMediaU[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'live'
                                    );
                                } else {
                                    $dataToDbMediaU[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => 'about_company_value','status' => 'live');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            } else {
                                if ($dt_post["title_{$value->id}"] != '') {
                                    $dataToDbMediaN[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $value->image_url,
                                        'status' => 'live'
                                    );
                                } else {
                                    $dataToDbMediaN[$key] = array(
                                        'id' => $value->id,
                                        'title' => $dt_post["title_{$value->id}"],
                                        'title_en' => $dt_post["title_en_{$value->id}"],
                                        'image_url' => $value->image_url,
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => 'about_company_value','status' => 'live');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            }
                        }
                        for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                            if (!empty($_FILES["img_url_new_{$i}"])){
                                if ($dt_post["title_new_{$i}"] != '') {
                                    $dataToDbMediaNEW = array(
                                        'type' => 'about_company_value',
                                        'imgvid' => 'img',
                                        'page_id' => 2,
                                        'title' => $dt_post["title_new_{$i}"],
                                        'title_en' => $dt_post["title_en_new_{$i}"],
                                        'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                                        'status' => 'live'
                                    );
                                    $this->$model_media->replace($dataToDbMediaNEW);
                                }
                            }
                        } 
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //V
                    // echo "<pre>";
                    // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                    $id = $this->$model->replace($dataToDb);
                    $this->$model_media->replace_batch($dataToDbMedia);
                }
                // var_dump($dt_post["title_${i}"]);
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

	public function update_certification_post() {
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
				$model = "About_certification";
				$model_media = "Media";
                $type = "about_certifications";
				/* Start Transaction */
                // echo "<pre>";
                // var_dump($dt_post);
                // var_dump($_FILES);
                // echo "</pre>";
                // die();
				$this->db->trans_start();
                $whereFields = array('a.type' => $type,'a.status' => 'live');
                $this->$model_media->set_variable('whereFields', $whereFields);
                $output_media = $this->$model_media->read(0, false);
                $whereFields = array();
                
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
                        if ( $this->photo_upload->do_upload("$key") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["$key"]["name"] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["$key"]['name'], $error['error']);
                            return;
                        }
                    }
                }

                // if ($dt_post['save_as'] == 'preview') {
                //     //V
                //         $dataToDb = array(
                //             'id' => 1,
                //             'title' => $dt_post['title'],
                //             'title_en' => $dt_post['title_en'],
                //             'description' => $dt_post['description'],
                //             'description_en' => $dt_post['description_en'],
                //             'status' => 'preview'
                //         );
                //         $whereFields = array('a.id' => 1,'a.status' => 'live');
                //         $this->$model->set_variable('whereFields', $whereFields);
                //         $output = $this->$model->read(0, false);
                //         $whereFields = array();
                //         $dataToDbMediaU = array();
                //         $dataToDbMediaN = array();
                //         $dataToDbMediaNEW = array();
                //         foreach ($output_media as $key => $value) {
                //             if (!empty($_FILES["img_url_{$value->id}"])) {
                //                 if (isset($dt_post["id_{$value->id}"]) && $dt_post["id_{$value->id}"] != '') {
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
                //                 if (isset($dt_post["id_{$value->id}"]) && $dt_post["id_{$value->id}"] != '') {
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
                //                 if (isset($dt_post["id_new_{$i}"]) && $dt_post["id_new_{$i}"] == '') {
                //                     $dataToDbMediaNEW = array(
                //                         'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                //                         'type' => $type,
                //                         'imgvid' => 'img',
                //                         'page_id' => 2,
                //                         'status' => 'preview'
                //                     );
                //                     $this->$model_media->replace($dataToDbMediaNEW);
                //                 }
                //             }
                //         } 
                //         $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                //     //V
                //     // echo "<pre>";
                //     // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                //     $id = $this->$model->replace($dataToDb);
                //     $this->$model_media->replace_batch($dataToDbMedia);
                // } 
                if ($dt_post['save_as'] == 'live' || true) {
                    //V
                        $dataToDb = array(
                            'id' => 1,
                            'title' => $dt_post['title'],
                            'title_en' => $dt_post['title_en'],
                            'description' => $dt_post['description'],
                            'description_en' => $dt_post['description_en'],
                            'status' => 'live'
                        );
                        $dataToDbMediaU = array();
                        $dataToDbMediaN = array();
                        $dataToDbMediaNEW = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if (isset($dt_post["id_{$value->id}"]) && $dt_post["id_{$value->id}"] != '') {
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
                                    $whereFields = array('id' => $value->id,'type' => $type);
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            } else {
                                if (isset($dt_post["id_{$value->id}"]) && $dt_post["id_{$value->id}"] != '') {
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
                                    $whereFields = array('id' => $value->id,'type' => $type);
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            }
                        }
                        // for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                        //     if (!empty($_FILES["img_url_new_{$i}"])){
                        //         if (isset($dt_post["id_new_{$i}"]) && $dt_post["id_new_{$i}"] == '') {
                        //             $dataToDbMediaNEW = array(
                        //                 'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                        //                 'type' => $type,
                        //                 'imgvid' => 'img',
                        //                 'page_id' => 2,
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
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //V
                    // echo "<pre>";
                    // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                    $id = $this->$model->replace($dataToDb);
                    $this->$model_media->replace_batch($dataToDbMedia);
                }
                // if ($dt_post["save_as"] == 'live') {
                //     $this->$model_media->DeleteByType($type,"delete");
                //     $this->$model_media->updateStatusByType($type,"live");
                // }
                // var_dump($dt_post["title_${i}"]);
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

	public function update_office_post() {
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
				$model_office = "About_office";
				$model_factory = "About_factory";
				$model_media = "Media";
                $type = "about_office";
                $type_f = "about_factory";
				/* Start Transaction */
                // echo "<pre>";
                // var_dump($dt_post);
                // var_dump($_FILES);
                // echo "</pre>";
                // die();
				$this->db->trans_start();
                $whereFields = array('a.type' => $type,'a.status' => 'live');
                $this->$model_media->set_variable('whereFields', $whereFields);
                $output_media = $this->$model_media->read(0, false);
                $whereFields = array();
                $whereFields = array('a.type' => $type_f,'a.status' => 'live');
                $this->$model_media->set_variable('whereFields', $whereFields);
                $output_media_f = $this->$model_media->read(0, false);
                $whereFields = array();
                
                foreach ($output_media as $key => $value) {
                    if (!empty($_FILES["img_url_{$value->id}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_o_{$value->id}_".time();
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
                for ($i = count($output_media); $i < $dt_post['count_item']; $i++) { 
                    // var_dump($i);
                    if (!empty($_FILES["img_url_new_{$i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_o_new_{$i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_new_{$i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_new_{$i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_new_{$i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }

                foreach ($output_media_f as $key => $value) {
                    if (!empty($_FILES["img_url_{$value->id}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_f_{$value->id}_".time();
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
                for ($i = count($output_media_f); $i < $dt_post['f_count_item']; $i++) { 
                    // var_dump($i);
                    if (!empty($_FILES["img_url_new_{$i}"])):
                        $config['upload_path'] = './assets/media/images';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;	
                        $config['file_name'] = "img_url_f_new_{$i}_".time();
                        $this->load->library('upload', $config, 'photo_upload');
                        $this->photo_upload->initialize($config);
                        if ( $this->photo_upload->do_upload("img_url_new_{$i}") ) {
                            $fileData = $this->photo_upload->data();
                            $_FILES["img_url_new_{$i}"]['name'] =  $fileData['file_name'];
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["img_url_new_{$i}"]['name'], $error['error']);
                            return;
                        }
                    endif;
                }

                // if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                //     //O
                //         $dataToDb = array(
                //             'id' => 1,
                //             'title' => $dt_post['title'],
                //             'title_en' => $dt_post['title_en'],
                //             'description' => $dt_post['description'],
                //             'description_en' => $dt_post['description_en'],
                //             'updated_at' => date('Y-m-d H:i:s'),
                //             'status' => 'preview'
                //         );
                //         $whereFields = array();
                //         $dataToDbMediaU = array();
                //         $dataToDbMediaN = array();
                //         $dataToDbMediaNEW = array();
                //         foreach ($output_media as $key => $value) {
                //             if (!empty($_FILES["img_url_{$value->id}"])) {
                //                 if ($dt_post["id_{$value->id}"] != '') {
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
                //                 if ($dt_post["id_{$value->id}"] != '') {
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
                //                         'page_id' => 2,
                //                         'status' => 'preview'
                //                     );
                //                     $this->$model_media->replace($dataToDbMediaNEW);
                //                 }
                //             }
                //         } 
                //         $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                //     //O
                //     //F
                //         $dataToDbF = array(
                //             'id' => 1,
                //             'title' => $dt_post['f_title'],
                //             'title_en' => $dt_post['f_title_en'],
                //             'description' => $dt_post['f_description'],
                //             'description_en' => $dt_post['f_description_en'],
                //             'updated_at' => date('Y-m-d H:i:s'),
                //             'status' => 'preview'
                //         );
                //         $whereFields = array();
                //         $dataToDbMediaUF = array();
                //         $dataToDbMediaNF = array();
                //         $dataToDbMediaNEWF = array();
                //         foreach ($output_media_f as $key => $value) {
                //             if (!empty($_FILES["img_url_{$value->id}"])) {
                //                 if ($dt_post["id_{$value->id}"] != '') {
                //                     $dataToDbMediaUF[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                //                         'status' => 'preview'
                //                     );
                //                 } else {
                //                     $dataToDbMediaUF[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                //                         'status' => 'delete'
                //                     );
                //                     $whereFields = array('id' => $value->id,'type' => $type_f,'status' => 'preview');
                //                     $this->$model_media->removetype($value->id,$whereFields);
                //                     $whereFields = array();
                //                 }
                //             } else {
                //                 if ($dt_post["id_{$value->id}"] != '') {
                //                     $dataToDbMediaNF[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $value->image_url,
                //                         'status' => 'preview'
                //                     );
                //                 } else {
                //                     $dataToDbMediaNF[$key] = array(
                //                         'id' => $value->id,
                //                         'image_url' => $value->image_url,
                //                         'status' => 'delete'
                //                     );
                //                     $whereFields = array('id' => $value->id,'type' => $type_f,'status' => 'preview');
                //                     $this->$model_media->removetype($value->id,$whereFields);
                //                     $whereFields = array();
                //                 }
                //             }
                //         }
                //         for ($i = count($output_media_f); $i < $dt_post['count_item']; $i++) {
                //             if (!empty($_FILES["img_url_new_{$i}"])){
                //                 if ($dt_post["id_new_{$i}"] == '') {
                //                     $dataToDbMediaNEWF = array(
                //                         'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                //                         'type' => $type_f,
                //                         'imgvid' => 'img',
                //                         'page_id' => 2,
                //                         'status' => 'preview'
                //                     );
                //                     $this->$model_media->replace($dataToDbMediaNEWF);
                //                 }
                //             }
                //         } 
                //         $dataToDbMediaF = array_merge($dataToDbMediaUF,$dataToDbMediaNF);
                //     //F
                //     // echo "<pre>";
                //     // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                //     $id = $this->$model_office->replace($dataToDb);
                //     $this->$model_media->replace_batch($dataToDbMedia);
                //     $id = $this->$model_factory->replace($dataToDbF);
                //     $this->$model_media->replace_batch($dataToDbMediaF);
                // } 
                if ($dt_post['save_as'] == 'live' || true) {
                    //O
                        $dataToDb = array(
                            'id' => 1,
                            'title' => $dt_post['title'],
                            'title_en' => $dt_post['title_en'],
                            'description' => $dt_post['description'],
                            'description_en' => $dt_post['description_en'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'live'
                        );
                        $dataToDbMediaU = array();
                        $dataToDbMediaN = array();
                        $dataToDbMediaNEW = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if ($dt_post["id_{$value->id}"] != '') {
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
                                if ($dt_post["id_{$value->id}"] != '') {
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
                        for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                            if (!empty($_FILES["img_url_new_{$i}"])){
                                if ($dt_post["id_new_{$i}"] == '') {
                                    $dataToDbMediaNEW = array(
                                        'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                                        'type' => $type,
                                        'imgvid' => 'img',
                                        'page_id' => 2,
                                        'status' => 'live'
                                    );
                                    $this->$model_media->replace($dataToDbMediaNEW);
                                }
                            }
                        } 
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //O
                    //F
                        $dataToDbF = array(
                            'id' => 1,
                            'title' => $dt_post['f_title'],
                            'title_en' => $dt_post['f_title_en'],
                            'description' => $dt_post['f_description'],
                            'description_en' => $dt_post['f_description_en'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'live'
                        );
                        $whereFields = array();
                        $dataToDbMediaUF = array();
                        $dataToDbMediaNF = array();
                        $dataToDbMediaNEWF = array();
                        foreach ($output_media_f as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if ($dt_post["id_{$value->id}"] != '') {
                                    $dataToDbMediaUF[$key] = array(
                                        'id' => $value->id,
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'live'
                                    );
                                } else {
                                    $dataToDbMediaUF[$key] = array(
                                        'id' => $value->id,
                                        'image_url' => $_FILES["img_url_{$value->id}"]['name'],
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => $type_f,'status' => 'live');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            } else {
                                if ($dt_post["id_{$value->id}"] != '') {
                                    $dataToDbMediaNF[$key] = array(
                                        'id' => $value->id,
                                        'image_url' => $value->image_url,
                                        'status' => 'live'
                                    );
                                } else {
                                    $dataToDbMediaNF[$key] = array(
                                        'id' => $value->id,
                                        'image_url' => $value->image_url,
                                        'status' => 'delete'
                                    );
                                    $whereFields = array('id' => $value->id,'type' => $type_f,'status' => 'live');
                                    $this->$model_media->removetype($value->id,$whereFields);
                                    $whereFields = array();
                                }
                            }
                        }
                        for ($i = count($output_media_f); $i < $dt_post['count_item']; $i++) {
                            if (!empty($_FILES["img_url_new_{$i}"])){
                                if ($dt_post["id_new_{$i}"] == '') {
                                    $dataToDbMediaNEWF = array(
                                        'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                                        'type' => $type_f,
                                        'imgvid' => 'img',
                                        'page_id' => 2,
                                        'status' => 'live'
                                    );
                                    $this->$model_media->replace($dataToDbMediaNEWF);
                                }
                            }
                        } 
                        $dataToDbMediaF = array_merge($dataToDbMediaUF,$dataToDbMediaNF);
                    //F
                    // echo "<pre>";
                    // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                    $id = $this->$model_office->replace($dataToDb);
                    $this->$model_media->replace_batch($dataToDbMedia);
                    $id = $this->$model_factory->replace($dataToDbF);
                    $this->$model_media->replace_batch($dataToDbMediaF);
                }
                // var_dump($dt_post["title_${i}"]);
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

	public function update_award_post() {
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
				$model = "About_award";
				$model_media = "Media";
                $type = "about_awards";
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
                        $config['file_name'] = "img_url_a_{$value->id}_".time();
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
                //     // var_dump($i);
                //     if (!empty($_FILES["img_url_new_{$i}"])):
                //         $config['upload_path'] = './assets/media/images';
                //         $config['allowed_types'] = '*';
                //         $config['overwrite'] = true;	
                //         $config['file_name'] = "img_url_a_new_{$i}_".time();
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
                        } else {
                            $error = array('error' => $this->photo_upload->display_errors());
                            $this->REST_Return(422, 'Failed Upload Image '.$_FILES["$key"]['name'], $error['error']);
                            return;
                        }
                    }
                }

                // if ($dt_post['save_as'] == 'preview' || $dt_post['save_as'] == 'live') {
                //     //V
                //         $dataToDb = array(
                //             'id' => 1,
                //             'title' => $dt_post['title'],
                //             'title_en' => $dt_post['title_en'],
                //             'description' => $dt_post['description'],
                //             'description_en' => $dt_post['description_en'],
                //             'updated_at' => date('Y-m-d H:i:s'),
                //             'status' => 'preview'
                //         );
                //         $whereFields = array('a.id' => 1,'a.status' => 'live');
                //         $this->$model->set_variable('whereFields', $whereFields);
                //         $output = $this->$model->read(0, false);
                //         $whereFields = array();
                //         $dataToDbMediaU = array();
                //         $dataToDbMediaN = array();
                //         $dataToDbMediaNEW = array();
                //         foreach ($output_media as $key => $value) {
                //             if (!empty($_FILES["img_url_{$value->id}"])) {
                //                 if ($dt_post["id_{$value->id}"] != '') {
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
                //                 if ($dt_post["id_{$value->id}"] != '') {
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
                //                         'page_id' => 2,
                //                         'created_at' => date('Y-m-d H:i:s'),
                //                         'status' => 'preview'
                //                     );
                //                     $this->$model_media->replace($dataToDbMediaNEW);
                //                 }
                //             }
                //         } 
                //         $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                //     //V
                //     // echo "<pre>";
                //     // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                //     $id = $this->$model->replace($dataToDb);
                //     $this->$model_media->replace_batch($dataToDbMedia);
                // } 
                if ($dt_post['save_as'] == 'live' || true) {
                    //V
                        $dataToDb = array(
                            'id' => 1,
                            'title' => $dt_post['title'],
                            'title_en' => $dt_post['title_en'],
                            'description' => $dt_post['description'],
                            'description_en' => $dt_post['description_en'],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'live'
                        );
                        $dataToDbMediaU = array();
                        $dataToDbMediaN = array();
                        $dataToDbMediaNEW = array();
                        foreach ($output_media as $key => $value) {
                            if (!empty($_FILES["img_url_{$value->id}"])) {
                                if (isset($dt_post["id_{$value->id}"]) && $dt_post["id_{$value->id}"] != '') {
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
                                if (isset($dt_post["id_{$value->id}"]) && $dt_post["id_{$value->id}"] != '') {
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
                        // for ($i = count($output_media); $i < $dt_post['count_item']; $i++) {
                        //     if (!empty($_FILES["img_url_new_{$i}"])){
                        //         if (isset($dt_post["id_new_{$i}"]) && $dt_post["id_new_{$i}"] == '') {
                        //             $dataToDbMediaNEW = array(
                        //                 'image_url' => $_FILES["img_url_new_{$i}"]['name'],
                        //                 'type' => $type,
                        //                 'imgvid' => 'img',
                        //                 'page_id' => 2,
                        //                 'created_at' => date('Y-m-d H:i:s'),
                        //                 'status' => 'live'
                        //             );
                        //             $this->$model_media->replace($dataToDbMediaNEW);
                        //         }
                        //     }
                        // } 
                        $dataToDbMedia = array_merge($dataToDbMediaU,$dataToDbMediaN);
                    //V
                    // echo "<pre>";
                    // var_dump($dataToDb);var_dump($dataToDbMedia);die();
                    $id = $this->$model->replace($dataToDb);
                    $this->$model_media->replace_batch($dataToDbMedia);
                }
                // var_dump($dt_post["title_${i}"]);
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