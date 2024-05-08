<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class About extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load About Model
        $this->load->model('About_model', 'AboutModel');
        $this->load->model('Url_model', 'UrlModel');
    }

     /**
     * Get About with API
     * -------------------------
     * @method: GET
     */
    public function hero_get() {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        // $this->load->library('Authorization_Token');

        /**
         * User Token Validation
         */
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {

            // Read About
            $output = $this->AboutModel->read_hero();

            if ($output > 0 AND !empty($output)) {
                // Success
                $message = [
                    'status' => true,
                    'message' => "Success",
                    'data' => $output
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Data Not Found"
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }

        // } else {
        //     $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        // }
    }

     /**
     * Get About with API
     * -------------------------
     * @method: GET
     */
    public function all_get() {
        header("Access-Control-Allow-Origin: *");
        // Read About
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        $about = array();
        $about_certification = array();
        $about_certification_images = array();
        $about_award = array();
        $about_award_images = array();
        $about_company_profile_images = array();
        $about_company_value_images = array();
        $about_vision = array();
        $about_mission = array();
        $about_visi_misi = array();
        $about_office = array();
        $about_factory = array();
        $about_office_images = array();
        $about_factory_images = array();
        $about_office_factory = array();
        $url = $this->UrlModel->read_url()[0];
        $about = $this->AboutModel->read_about($q_strings);
        $about_company_profile = $this->AboutModel->read_about_company_profile($q_strings);
        $about_company_profile->image_url = ($about_company_profile->image_url != null) ? IPSERVER.$about_company_profile->image_url : NULL;
        $about_company_profile_image = $this->AboutModel->read_about_image($q_strings,'about_company_profile');
        for ($d=0; $d < count($about_company_profile_image); $d++) {
            ($about_company_profile_image[$d]->image_url != null) ? array_push($about_company_profile_images, array('image_url' => IPSERVER.$about_company_profile_image[$d]->image_url)) : array_push($about_company_profile_images, array('image_url' => NULL));
        }
        $about_company_profile->slider = $about_company_profile_images;

        $about_company_historie = $this->AboutModel->read_about_company_historie($q_strings);
        $about_company_historie->image_url = ($about_company_historie->image_url != null) ? IPSERVER.$about_company_historie->image_url : NULL;

        $about_company_value = $this->AboutModel->read_about_company_value($q_strings);
        $about_company_value_image = $this->AboutModel->read_about_image($q_strings,'about_company_value');
        for ($d=0; $d < count($about_company_value_image); $d++) {
            ($about_company_value_image[$d]->image_url != null && $about_company_value_image[$d]->title != null && $about_company_value_image[$d]->title_en != null) ? array_push($about_company_value_images, array('image_url' => IPSERVER.$about_company_value_image[$d]->image_url,'title' => $about_company_value_image[$d]->title,'title_en' => $about_company_value_image[$d]->title_en)) : array_push($about_company_value_images, array('image_url' => NULL,'title' => NULL,'title_en' => NULL));
        }
        $about_company_value->list = $about_company_value_images;

        $about_greeting = $this->AboutModel->read_about_greeting($q_strings);
        $about_greeting->image_url = ($about_greeting->image_url != null) ? IPSERVER.$about_greeting->image_url : NULL;
        // for ($i=0; $i < count($about_greeting); $i++) {
        //     $about_greeting[$i]->image_url = ($about_greeting[$i]->image_url != null) ? IPSERVER.$about_greeting[$i]->image_url : NULL;
        // }
        $about_vision = $this->AboutModel->read_about_vision($q_strings);
        $about_vision->image_url_sm = ($about_vision->image_url_sm != null) ? IPSERVER.$about_vision->image_url_sm : NULL;
        $about_vision->image_url_md = ($about_vision->image_url_md != null) ? IPSERVER.$about_vision->image_url_md : NULL;
        $about_vision->image_url_lg = ($about_vision->image_url_lg != null) ? IPSERVER.$about_vision->image_url_lg : NULL;
        $about_mission = $this->AboutModel->read_about_mission($q_strings);
        $about_mission->image_url_sm = ($about_mission->image_url_sm != null) ? IPSERVER.$about_mission->image_url_sm : NULL;
        $about_mission->image_url_md = ($about_mission->image_url_md != null) ? IPSERVER.$about_mission->image_url_md : NULL;
        $about_mission->image_url_lg = ($about_mission->image_url_lg != null) ? IPSERVER.$about_mission->image_url_lg : NULL;
        $about_mission->mission_item = ($about_mission->mission_item != null) ? $json_mission_item = json_decode($about_mission->mission_item,true) : NULL;
        $about_mission->mission_item = $about_mission->mission_item['data'];
        $about_mission->mission_item_en = ($about_mission->mission_item_en != null) ? $json_mission_item_en = json_decode($about_mission->mission_item_en,true) : NULL;
        $about_mission->mission_item_en = $about_mission->mission_item_en['data'];
        $about_visi_misi = array('visi' => $about_vision,'misi' => $about_mission);

        $about_certification = $this->AboutModel->read_about_certification($q_strings);
        $about_certification_image = $this->AboutModel->read_about_image($q_strings,'about_certifications');
        for ($d=0; $d < count($about_certification_image); $d++) {
            ($about_certification_image[$d]->image_url != null) ? array_push($about_certification_images, array('image_url' => IPSERVER.$about_certification_image[$d]->image_url)) : array_push($about_certification_images, array('image_url' => NULL));
        }
        $about_certification->list = $about_certification_images;

        $about_award = $this->AboutModel->read_about_award($q_strings);
        $about_award_image = $this->AboutModel->read_about_image($q_strings,'about_awards');
        for ($d=0; $d < count($about_award_image); $d++) {
            ($about_award_image[$d]->image_url != null) ? array_push($about_award_images, array('image_url' => IPSERVER.$about_award_image[$d]->image_url)) : array_push($about_award_images, array('image_url' => NULL));
        }
        $about_award->list = $about_award_images;

        $about_office = $this->AboutModel->read_about_office($q_strings);
        $about_office_image = $this->AboutModel->read_about_image($q_strings,'about_office');
        for ($d=0; $d < count($about_office_image); $d++) {
            ($about_office_image[$d]->image_url != null) ? array_push($about_office_images, array('image_url' => IPSERVER.$about_office_image[$d]->image_url)) : array_push($about_office_images, array('image_url' => NULL));
        }
        $about_office->list = $about_office_images;

        $about_factory = $this->AboutModel->read_about_factory($q_strings);
        $about_factory_image = $this->AboutModel->read_about_image($q_strings,'about_factory');
        for ($d=0; $d < count($about_factory_image); $d++) {
            ($about_factory_image[$d]->image_url != null) ? array_push($about_factory_images, array('image_url' => IPSERVER.$about_factory_image[$d]->image_url)) : array_push($about_factory_images, array('image_url' => NULL));
        }
        $about_factory->list = $about_factory_images;

        $about_office_factory = array('office' => $about_office,'factory' => $about_factory);

        $about_us_tab = $this->AboutModel->read_about_us_tab();

        if ($about_us_tab > 0 AND !empty($about_us_tab)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => array('about' => $about,'about_company_profile'=>$about_company_profile,'about_company_historie'=>$about_company_historie,'about_company_value'=>$about_company_value,'about_greeting'=>$about_greeting,'about_visi_misi'=>$about_visi_misi,'about_us_tab'=>$about_us_tab,'about_certification'=>$about_certification,'about_award'=>$about_award,'about_office_factory'=>$about_office_factory))
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            // Error
            $message = [
                'status' => FALSE,
                'message' => "Data Not Found"
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * Add new Article with API
     * -------------------------
     * @method: POST
     */
    public function createArticle_post()
    {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            # Create a User Article

            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = $this->security->xss_clean($_POST);
            
            # Form Validation
            $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[200]');
            if ($this->form_validation->run() == FALSE)
            {
                // Form Validation Errors
                $message = array(
                    'status' => false,
                    'error' => $this->form_validation->error_array(),
                    'message' => validation_errors()
                );

                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
            else
            {
                // Load Article Model
                $this->load->model('article_model', 'ArticleModel');

                $insert_data = [
                    'user_id' => $is_valid_token['data']->id,
                    'title' => $this->input->post('title', TRUE),
                    'description' => $this->input->post('description', TRUE),
                    'created_at' => time(),
                    'updated_at' => time(),
                ];

                // Insert Article
                $output = $this->ArticleModel->create_article($insert_data);

                if ($output > 0 AND !empty($output))
                {
                    // Success
                    $message = [
                        'status' => true,
                        'message' => "Article Add"
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                } else
                {
                    // Error
                    $message = [
                        'status' => FALSE,
                        'message' => "Article not create"
                    ];
                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }

        } else {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete an Article with API
     * @method: DELETE
     */
    public function deleteArticle_delete($id)
    {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            # Delete a User Article

            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $id = $this->security->xss_clean($id);
            
            if (empty($id) AND !is_numeric($id))
            {
                $this->response(['status' => FALSE, 'message' => 'Invalid Article ID' ], REST_Controller::HTTP_NOT_FOUND);
            }
            else
            {
                // Load Article Model
                $this->load->model('article_model', 'ArticleModel');

                $delete_article = [
                    'id' => $id,
                    'user_id' => $is_valid_token['data']->id,
                ];

                // Delete an Article
                $output = $this->ArticleModel->delete_article($delete_article);

                if ($output > 0 AND !empty($output))
                {
                    // Success
                    $message = [
                        'status' => true,
                        'message' => "Article Deleted"
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                } else
                {
                    // Error
                    $message = [
                        'status' => FALSE,
                        'message' => "Article not delete"
                    ];
                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }

        } else {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update an Article with API
     * @method: PUT
     */
    public function updateArticle_put()
    {
        header("Access-Control-Allow-Origin: *");
    
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');

        /**
         * User Token Validation
         */
        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE)
        {
            # Update a User Article


            # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
            $_POST = json_decode($this->security->xss_clean(file_get_contents("php://input")), true);
            
            $this->form_validation->set_data([
                'id' => $this->input->post('id', TRUE),
                'title' => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', TRUE),
            ]);
            
            # Form Validation
            $this->form_validation->set_rules('id', 'Article ID', 'trim|required|numeric');
            $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[200]');
            if ($this->form_validation->run() == FALSE)
            {
                // Form Validation Errors
                $message = array(
                    'status' => false,
                    'error' => $this->form_validation->error_array(),
                    'message' => validation_errors()
                );

                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
            else
            {
                // Load Article Model
                $this->load->model('article_model', 'ArticleModel');

                $update_data = [
                    'user_id' => $is_valid_token['data']->id,
                    'id' => $this->input->post('id', TRUE),
                    'title' => $this->input->post('title', TRUE),
                    'description' => $this->input->post('description', TRUE),
                ];

                // Update an Article
                $output = $this->ArticleModel->update_article($update_data);

                if ($output > 0 AND !empty($output))
                {
                    // Success
                    $message = [
                        'status' => true,
                        'message' => "Article Updated"
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                } else
                {
                    // Error
                    $message = [
                        'status' => FALSE,
                        'message' => "Article not update"
                    ];
                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }

        } else {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message'] ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}