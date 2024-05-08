<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Business extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Model
        $this->load->model("cms/Business_export_model", "Business_export");
        $this->load->model("cms/Business_manufacturing_model", "Business_manufacturing");
        $this->load->model("cms/Business_distribution_model", "Business_distribution");
        $this->load->model("cms/Page_model", "Page");
        $this->load->model("cms/Media_model", "Media");
        $this->load->model('Url_model', 'UrlModel');
    }

	public function all_get($p = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model_export  = "{$this->name}_export";
        $model_manufacturing  = "{$this->name}_manufacturing";
        $model_distribution  = "{$this->name}_distribution";
        $model_page  = "Page";
        $url = $this->UrlModel->read_url()[0];
        $whereFields = array(
            'a.status' => $q_strings['status'],
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_export->set_variable('whereFields', $whereFields);
        $this->$model_export->set_variable('orderFields', $orderFields);
        $output_export = $this->$model_export->read()[0];
        $whereFields = array();
        $orderFields = array();
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.type' => 'business_export'
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->Media->set_variable('orderFields', $orderFields);
        $this->Media->set_variable('whereFields', $whereFields);
        $export_image = $this->Media->read(0,false);
        $whereFields = array();
        $orderFields = array();
        $export_images = array();
        for ($i=0; $i < count($export_image); $i++) {
            ($export_image[$i]->image_url != null) ? array_push($export_images, array('id' => $export_image[$i]->id,'image_url' => IPSERVER.$export_image[$i]->image_url)) : array_push($export_images, array('image_url' => NULL));
        }
        $output_export->image_urls = $export_images;
        $whereFields = array(
            'a.status' => $q_strings['status'],
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_manufacturing->set_variable('whereFields', $whereFields);
        $this->$model_manufacturing->set_variable('orderFields', $orderFields);
        $output_manufacturing = $this->$model_manufacturing->read()[0];
        $output_manufacturing->image_url = ($output_manufacturing->image_url != null) ? IPSERVER.$output_manufacturing->image_url : NULL;
        $output_manufacturing->par_list_2 = ($output_manufacturing->par_list_2 != null) ? json_decode($output_manufacturing->par_list_2) : NULL;
        $output_manufacturing->par_list_3 = ($output_manufacturing->par_list_3 != null) ? json_decode($output_manufacturing->par_list_3) : NULL;
        $output_manufacturing->par_list_2_en = ($output_manufacturing->par_list_2_en != null) ? json_decode($output_manufacturing->par_list_2_en) : NULL;
        $output_manufacturing->par_list_3_en = ($output_manufacturing->par_list_3_en != null) ? json_decode($output_manufacturing->par_list_3_en) : NULL;
        $whereFields = array();
        $orderFields = array();
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.type' => 'business_manufacturing'
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->Media->set_variable('orderFields', $orderFields);
        $this->Media->set_variable('whereFields', $whereFields);
        $manufacturing_image = $this->Media->read(0,false);
        $whereFields = array();
        $orderFields = array();
        $manufacturing_images = array();
        for ($i=0; $i < count($manufacturing_image); $i++) {
            ($manufacturing_image[$i]->image_url != null) ? array_push($manufacturing_images, array('id' => $manufacturing_image[$i]->id,'image_url' => IPSERVER.$manufacturing_image[$i]->image_url)) : array_push($manufacturing_images, array('image_url' => NULL));
        }
        $output_manufacturing->image_urls = $manufacturing_images;
        $whereFields = array(
            'a.is_delete' => '0',
            'a.status' => $q_strings['status'],
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_distribution->set_variable('whereFields', $whereFields);
        $this->$model_distribution->set_variable('orderFields', $orderFields);
        $output_distribution = $this->$model_distribution->read();
        for ($i=0; $i < count($output_distribution); $i++) {
            $output_distribution[$i]->companies = ($output_distribution[$i]->companies != null) ? json_decode($output_distribution[$i]->companies) : NULL;
            $output_distribution[$i]->companies_en = ($output_distribution[$i]->companies_en != null) ? json_decode($output_distribution[$i]->companies_en) : NULL;
        }
        $whereFields = array();
        $orderFields = array();
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.pages' => 'Bisnis',
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->$model_page->set_variable('whereFields', $whereFields);
        $this->$model_page->set_variable('orderFields', $orderFields);
        $output_page = $this->$model_page->read()[0];
        $output_page->contact = ($output_page->contact != null) ? json_decode($output_page->contact) : NULL;
        $output = array('export'=>$output_export,'manufacturing'=>$output_manufacturing,'distribution'=>$output_distribution,'page'=>$output_page);
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

	public function mitra_get(){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error      = true;
        $q_strings  = $this->security->xss_clean($q_strings);
        $url        = $this->UrlModel->read_url()[0];
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.type' => 'business_mitra'
        );
        $orderFields = array(
            'a.id' => 'ASC',
        );
        $this->Media->set_variable('orderFields', $orderFields);
        $this->Media->set_variable('whereFields', $whereFields);
        $output = $this->Media->read(0,false);
        for ($i=0; $i < count($output); $i++) {
            $output[$i]->image_url = ($output[$i]->image_url != null) ? IPSERVER.$output[$i]->image_url : NULL;
        }
        $response_data = array();
        if ($output != []) {
            $response_data = $output;
            $error   = false;
        } else if ($output != []) {
            $response_data = $output;
            $error   = false;
        } else {
            $this->REST_Return(404, $this->name . ' does not exist!');
            return;
        }
        if ($error == true) {
            $this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
        } else {
            $this->REST_Return(200, 'SUCCESS', $response_data);
        }
        return;
    }
}