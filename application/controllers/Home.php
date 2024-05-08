<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Home extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Home Model
        $this->load->model('Home_model', 'HomeModel');
        $this->load->model('Url_model', 'UrlModel');
    }

     /**
     * Get Home with API
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

            // Read Home
            $output = $this->HomeModel->read_hero();

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
     * Get Home with API
     * -------------------------
     * @method: GET
     */
    public function all_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Home
        $hero = array();
        $about = array();
        $about_image = array();
        $about_images = array();
        $product = array();
        $qualitie = array();
        $award = array();
        $url = $this->UrlModel->read_url()[0];
        $hero = $this->HomeModel->read_hero($q_strings);
        for ($i=0; $i < count($hero); $i++) {
            $hero[$i]->image_url_lg = ($hero[$i]->image_url_lg != null) ? IPSERVER.$hero[$i]->image_url_lg : NULL;
            $hero[$i]->image_url_md = ($hero[$i]->image_url_md != null) ? IPSERVER.$hero[$i]->image_url_md : NULL;
            $hero[$i]->image_url_sm = ($hero[$i]->image_url_sm != null) ? IPSERVER.$hero[$i]->image_url_sm : NULL;
        }
        $about = $this->HomeModel->read_about($q_strings);
        $about_image = $this->HomeModel->read_about_image_filter($q_strings);
        for ($i=0; $i < count($about_image); $i++) {
            ($about_image[$i]->image_url != null) ? array_push($about_images, array('image_url' => IPSERVER.$about_image[$i]->image_url)) : array_push($about_images, array('image_url' => NULL));
        }
        $about->image_urls = $about_images;
        $product = $this->HomeModel->read_product($q_strings);
        for ($i=0; $i < count($product); $i++) {
            $product[$i]->image_url_lg = ($product[$i]->image_url_lg != null) ? IPSERVER.$product[$i]->image_url_lg : NULL;
            $product[$i]->image_url_md = ($product[$i]->image_url_md != null) ? IPSERVER.$product[$i]->image_url_md : NULL;
            $product[$i]->image_url_sm = ($product[$i]->image_url_sm != null) ? IPSERVER.$product[$i]->image_url_sm : NULL;
        }

        $qualitie = $this->HomeModel->read_qualitie();
        for ($i=0; $i < count($qualitie); $i++) {
            $qualitie[$i]->image_url = ($qualitie[$i]->image_url != null) ? IPSERVER.$qualitie[$i]->image_url : NULL;
        }

        $award = $this->HomeModel->read_award($q_strings);
        for ($i=0; $i < count($award); $i++) {
            $award[$i]->image_url = ($award[$i]->image_url != null) ? IPSERVER.$award[$i]->image_url : NULL;
        }

        if ($hero > 0 AND !empty($hero)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => array('hero' => $hero, 'about' => $about, 'product' => $product, 'qualitie' => $qualitie, 'award' => $award))
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