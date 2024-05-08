<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Productz extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("cms/Product_model", "Product");
        $this->load->model('Url_model', 'UrlModel');
    }

    public function search_get($p = 0){
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        $model  = 'Product';
        $url = $this->UrlModel->read_url()[0];

        if (isset($q_strings['name'])) {
            $whereLike = array(
                'a.description' => $q_strings['name']
            );
            $whereFields = array(
                'a.status' => 'live'
            );
            $this->$model->set_variable('whereLike', $whereLike);
            $this->$model->set_variable('whereFields', $whereFields);
            unset($q_strings['name']);
        } else {
            $this->$model->whereFields = $q_strings;
        }
        $output = $this->$model->read($id);
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
    }
}