<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class News extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Model
        $this->load->model("cms/News_model", "News");
        $this->load->model("cms/News_categorys_model", "News_categorys");
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
        $url = $this->UrlModel->read_url()[0];
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.is_delete' => 0
        );
        $orderFields = array(
            'a.id' => 'DESC',
        );
        $this->$model->set_variable('whereFields', $whereFields);
        $this->$model->set_variable('orderFields', $orderFields);
        $output_news = $this->$model->read();
        $whereFields = array();
        $orderFields = array();
        for ($i=0; $i < count($output_news); $i++) {
            $category = $this->News_categorys->read($output_news[$i]->category_id);
            $output_news[$i]->category = $category[0]->name;
            $output_news[$i]->category_en = $category[0]->name;
            $output_news[$i]->date = ($output_news[$i]->date != null) ? date('d-m-Y', strtotime($output_news[$i]->date)) : NULL;
            $output_news[$i]->image_url = ($output_news[$i]->image_url != null) ? IPSERVER.$output_news[$i]->image_url : NULL;
        }
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.is_delete' => 0
        );
        $orderFields = array(
            'a.countview' => 'DESC',
        );
        $this->$model->set_variable('whereFields', $whereFields);
        $this->$model->set_variable('orderFields', $orderFields);
        $output_popular = $this->$model->read();
        $whereFields = array();
        $orderFields = array();
        for ($i=0; $i < count($output_popular); $i++) {
            $category = $this->News_categorys->read($output_popular[$i]->category_id);
            $output_popular[$i]->category = $category[0]->name;
            $output_popular[$i]->category_en = $category[0]->name;
            $output_popular[$i]->date = ($output_popular[$i]->date != null) ? date('d-m-Y', strtotime($output_popular[$i]->date)) : NULL;
            $output_popular[$i]->image_url = ($output_popular[$i]->image_url != null) ? IPSERVER.$output_popular[$i]->image_url : NULL;
        }
        $whereFields = array(
            'a.status' => $q_strings['status'],
            'a.pages' => 'Berita',
        );
        $orderFields = array(
            'a.id' => 'DESC',
        );
        $this->$model_page->set_variable('whereFields', $whereFields);
        $this->$model_page->set_variable('orderFields', $orderFields);
        $output_page = $this->$model_page->read()[0];
        $whereFields = array();
        $orderFields = array();
        $whereFields = array(
            'a.status' => $q_strings['status'],
        );
        $orderFields = array(
            'a.id' => 'DESC',
        );
        $this->$model_categorys->set_variable('whereFields', $whereFields);
        $this->$model_categorys->set_variable('orderFields', $orderFields);
        $output_categorys = $this->$model_categorys->read();
        $output = array('popular'=>$output_popular,'news'=>$output_news,'page'=>$output_page,'categorys'=>$output_categorys);
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
        $model  = $this->name;

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';
            if (!isset($q_strings['status'])) {
                $q_strings['status'] = "live";
            }
            $url = $this->UrlModel->read_url()[0];
            if (isset($q_strings['id'])) {
                $whereFields = array(
                    'a.id' => $q_strings['id'],
                    'a.is_delete' => 0,
                    'a.status' => $q_strings['status']
                );
            }else if (isset($q_strings['title'])) {
                $whereFields = array(
                    'a.is_delete' => 0,
                    'a.status' => $q_strings['status']
                );
                $whereLike = array(
                    'lower(a.title)' => strtolower($q_strings['title'])
                );
            $this->News->set_variable('whereLike', $whereLike);
            } else {
                $whereFields = array(
                    'a.is_delete' => 0,
                    'a.status' => 'preview'
                );
            }
            $orderFields = array(
                'a.id' => 'DESC',
            );
            $this->News->set_variable('whereFields', $whereFields);
            $this->News->set_variable('orderFields', $orderFields);
            $output = $this->News->read();
            if ($output) {
                if (isset($q_strings['id'])) {
                    $this->News->replace(array('status'=>'preview','id'=>$q_strings['id'],'countview'=>$output[0]->countview+1));
                    $this->News->replace(array('status'=>'live','id'=>$q_strings['id'],'countview'=>$output[0]->countview+1));
                }
            }
            if (count($output) > 0) {
                $category = $this->News_categorys->read($output[0]->category_id);
                for ($i=0; $i < count($output); $i++) {
                    $output[$i]->category = $category[0]->name;
                    $output[$i]->category_en = $category[0]->name;
                    $output[$i]->date = ($output[$i]->date != null) ? date('d-m-Y', strtotime($output[$i]->date)) : NULL;
                    $output[$i]->image_url = ($output[$i]->image_url != null) ? IPSERVER.$output[$i]->image_url : NULL;
                }
            }
            $whereFields = array();
            $orderFields = array();
            $whereFields = array(
                'a.is_delete' => 0,
                'a.status' => 'live',
            );
            $orderFields = array(
                'a.countview' => 'DESC',
            );
            $this->$model->set_variable('whereFields', $whereFields);
            $this->$model->set_variable('orderFields', $orderFields);
            $output_popular = $this->$model->read();
            $whereFields = array();
            $orderFields = array();
            for ($i=0; $i < count($output_popular); $i++) {
                $category = $this->News_categorys->read($output_popular[$i]->category_id);
                $output_popular[$i]->category = $category[0]->name;
                $output_popular[$i]->category_en = $category[0]->name;
                $output_popular[$i]->image_url = ($output_popular[$i]->image_url != null) ? IPSERVER.$output_popular[$i]->image_url : NULL;
            }
            for ($i=0; $i < count($output); $i++) {
                $output[$i]->popular = $output_popular;
            }
            $response_data = array();
            if ($output != [] && $id != 0) {
                if(isset($q_strings['title'])) {
                    $response_data = $output;
                } else {
                    $response_data = $output[0];
                }
                $error   = false;
            } else if($output != [] or $id == 0) {
                if(isset($q_strings['title'])) {
                    $response_data = $output;
                } else {
                    $response_data = $output[0];
                }                
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
}