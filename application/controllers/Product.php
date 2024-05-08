<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Product extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Product Model
        $this->load->model('Product_model', 'ProductModel');
        $this->load->model('Url_model', 'UrlModel');
    }

     /**
     * Get Product Products with API
     * -------------------------
     * @method: GET
     */
    public function type_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Product
        $type = array();
        $url = $this->UrlModel->read_url()[0];
        $type = $this->ProductModel->read_type($q_strings);
        for ($i=0; $i < count($type); $i++) {
            $type[$i]->image_url = ($type[$i]->image_url != null) ? IPSERVER.$type[$i]->image_url : NULL;
            $type[$i]->image_url_lg = ($type[$i]->image_url_lg != null) ? IPSERVER.$type[$i]->image_url_lg : NULL;
            $type[$i]->image_url_md = ($type[$i]->image_url_md != null) ? IPSERVER.$type[$i]->image_url_md : NULL;
            $type[$i]->image_url_sm = ($type[$i]->image_url_sm != null) ? IPSERVER.$type[$i]->image_url_sm : NULL;
            $type[$i]->image_bg = ($type[$i]->image_bg != null) ? IPSERVER.$type[$i]->image_bg : NULL;
            $type[$i]->image_bg_lg = ($type[$i]->image_bg_lg != null) ? IPSERVER.$type[$i]->image_bg_lg : NULL;
            $type[$i]->image_bg_md = ($type[$i]->image_bg_md != null) ? IPSERVER.$type[$i]->image_bg_md : NULL;
            $type[$i]->image_bg_sm = ($type[$i]->image_bg_sm != null) ? IPSERVER.$type[$i]->image_bg_sm : NULL;
        }
        if (count($type) > 1) {
            $type = $type;
        } else {
            $type = $type[0];
        }
        if (!empty($type)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => $type)
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
    * Get Product Category with API
    * -------------------------
    * @method: GET
    */
    public function category_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Product Category
        $category = array();
        $url = $this->UrlModel->read_url()[0];
        $category = $this->ProductModel->read_category($q_strings);
        for ($i=0; $i < count($category); $i++) {
            $category[$i]->image_url = ($category[$i]->image_url != null) ? IPSERVER.$category[$i]->image_url : NULL;
        }
        if (!empty($category)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => $category)
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
    * Get Product group with API
    * -------------------------
    * @method: GET
    */
    public function group_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Product group
        $group = array();
        $url = $this->UrlModel->read_url()[0];
        $group = $this->ProductModel->read_group($q_strings);
        for ($i=0; $i < count($group); $i++) {
            $group[$i]->image_url = ($group[$i]->image_url != null) ? IPSERVER.$group[$i]->image_url : NULL;
        }
        if (!empty($group)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => $group)
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
    * Get Product with API
    * -------------------------
    * @method: GET
    */
    public function detailz_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Product product
        $product = array();
        $product_images = array();
        $product_groups = array();
        $product_htus = array();
        $product_nettos = array();
        $url = $this->UrlModel->read_url()[0];
        $product = $this->ProductModel->read_product($q_strings);
        for ($i=0; $i < count($product); $i++) {
            $product[$i]->microsite = ($product[$i]->microsite != '1') ? false : true;
            $product[$i]->gstore = ($product[$i]->gstore != '1') ? false : true;
            $product[$i]->chat = ($product[$i]->chat != '1') ? false : true;
            $product[$i]->email = ($product[$i]->email != '1') ? false : true;
            $product[$i]->image_url = ($product[$i]->image_url != null) ? IPSERVER.$product[$i]->image_url : NULL;
            $product_image = $this->ProductModel->read_product_image($product[$i]->id,$q_strings['status']);
            $product[$i]->image_url_detail = NULL;
            for ($d=0; $d < count($product_image); $d++) {
                ($product_image[$d]->image_url != null) ? array_push($product_images, array('image_url' => IPSERVER.$product_image[$d]->image_url,'imgvid' => $product_image[$d]->imgvid)) : array_push($product_images, array('image_url' => NULL,'imgvid' => NULL));
                $product[$i]->image_url_detail[$d] = $product_images[$d];
            }
            $product_category = $this->ProductModel->read_product_category($product[$i]->product_category_id);
            $product[$i]->category = $product_category[0];
            $product_group = $this->ProductModel->read_product_group($product[$i]->product_group_id);
            if (count($product_group) < 1) {
                $product[$i]->group = new stdClass();
            }else{
                $product[$i]->group = $product_group[0];
            }
            $product_htu = $this->ProductModel->read_product_htu($product[$i]->id);
            $product[$i]->htu = NULL;
            for ($h=0; $h < count($product_htu); $h++) {
                // ($product_htu[$h]->name != null && $product_htu[$h]->name_en != null) ? $product_htus[$h] = array('name' => $product_htu[$h]->name,'name_en' => $product_htu[$h]->name_en) : $product_htus[$h] = array('name' => NULL,'name_en' => NULL);
                $product[$i]->htu[$h] = $product_htu[$h];
            }
            // $product_netto = $this->ProductModel->read_product_netto($product[$i]->id);
            // for ($h=0; $h < count($product_netto); $h++) {
            //     ($product_netto[$h]->name != null && $product_netto[$h]->name_en != null) ? array_push($product_nettos, array('name' => $product_netto[$h]->name,'name_en' => $product_netto[$h]->name_en)) : array_push($product_htus[$i], array('name' => NULL,'name_en' => NULL));
            // }
            // $product[$i]->netto = $product_nettos;
        }
        if (count($product) > 1) {
            $product = $product;
        } else {
            $product = $product[0];
        }
        if (!empty($product)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => $product)
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
    * Get Product with API
    * -------------------------
    * @method: GET
    */
    public function drug_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Product product
        $product = array();
        $product_images = array();
        $product_groups = array();
        $product_htus = array();
        $product_nettos = array();
        $url = $this->UrlModel->read_url()[0];
        $product = $this->ProductModel->read_product_filter('drug',$q_strings['status']);
        for ($i=0; $i < count($product); $i++) {
            $product[$i]->microsite = ($product[$i]->microsite != '1') ? false : true;
            $product[$i]->gstore = ($product[$i]->gstore != '1') ? false : true;
            $product[$i]->chat = ($product[$i]->chat != '1') ? false : true;
            $product[$i]->email = ($product[$i]->email != '1') ? false : true;
            $product[$i]->image_url = ($product[$i]->image_url != null) ? IPSERVER.$product[$i]->image_url : NULL;
            $product_image = $this->ProductModel->read_product_image($product[$i]->id,$q_strings['status']);
            $product[$i]->image_url_detail = NULL;
            for ($d=0; $d < count($product_image); $d++) {
                ($product_image[$d]->image_url != null) ? array_push($product_images, array('image_url' => IPSERVER.$product_image[$d]->image_url,'imgvid' => $product_image[$d]->imgvid)) : array_push($product_images, array('image_url' => NULL,'imgvid' => NULL));
                $product[$i]->image_url_detail[$d] = $product_images[$d];
            }
            $product_category = $this->ProductModel->read_product_category($product[$i]->product_category_id);
            $product[$i]->namesearch = $product[$i]->name.', '.$product_category[0]->title;
            $product[$i]->category = $product_category[0];
            $product_group = $this->ProductModel->read_product_group($product[$i]->product_group_id);
            $product[$i]->group = @$product_group[0];
            $product_htu = $this->ProductModel->read_product_htu($product[$i]->id);
            $product[$i]->htu = NULL;
            for ($h=0; $h < count($product_htu); $h++) {
                // ($product_htu[$h]->name != null && $product_htu[$h]->name_en != null) ? $product_htus[$h] = array('name' => $product_htu[$h]->name,'name_en' => $product_htu[$h]->name_en) : $product_htus[$h] = array('name' => NULL,'name_en' => NULL);
                $product[$i]->htu[$h] = $product_htu[$h];
            }
            // $product_netto = $this->ProductModel->read_product_netto($product[$i]->id);
            // for ($h=0; $h < count($product_netto); $h++) {
            //     ($product_netto[$h]->name != null && $product_netto[$h]->name_en != null) ? array_push($product_nettos, array('name' => $product_netto[$h]->name,'name_en' => $product_netto[$h]->name_en)) : array_push($product_htus[$i], array('name' => NULL,'name_en' => NULL));
            // }
            // $product[$i]->netto = $product_nettos;
        }
        if (count($product) > 1) {
            $product = $product;
        } else {
            $product = $product[0];
        }
        if (!empty($product)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => $product)
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
    * Get Product with API
    * -------------------------
    * @method: GET
    */
    public function health_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read Product product
        $product = array();
        $product_images = array();
        $product_groups = array();
        $product_htus = array();
        $product_nettos = array();
        $url = $this->UrlModel->read_url()[0];
        $product = $this->ProductModel->read_product_filter('health',$q_strings['status']);
        for ($i=0; $i < count($product); $i++) {
            $product[$i]->microsite = ($product[$i]->microsite != '1') ? false : true;
            $product[$i]->gstore = ($product[$i]->gstore != '1') ? false : true;
            $product[$i]->chat = ($product[$i]->chat != '1') ? false : true;
            $product[$i]->email = ($product[$i]->email != '1') ? false : true;
            $product[$i]->image_url = ($product[$i]->image_url != null) ? IPSERVER.$product[$i]->image_url : NULL;
            $product_image = $this->ProductModel->read_product_image($product[$i]->id,$q_strings['status']);
            $product[$i]->image_url_detail = NULL;
            for ($d=0; $d < count($product_image); $d++) {
                ($product_image[$d]->image_url != null) ? array_push($product_images, array('image_url' => IPSERVER.$product_image[$d]->image_url,'imgvid' => $product_image[$d]->imgvid)) : array_push($product_images, array('image_url' => NULL,'imgvid' => NULL));
                $product[$i]->image_url_detail[$d] = $product_images[$d];
            }
            $product_category = $this->ProductModel->read_product_category($product[$i]->product_category_id);
            $product[$i]->namesearch = $product[$i]->name.' '.$product_category[0]->title;
            $product[$i]->category = $product_category[0];
            $product_group = $this->ProductModel->read_product_group($product[$i]->product_group_id);
            if (isset($product_group[0])) {
                $product[$i]->group = $product_group[0];
            }else{
                $product[$i]->group = new stdClass();
            }
            $product_htu = $this->ProductModel->read_product_htu($product[$i]->id);
            $product[$i]->htu = NULL;
            for ($h=0; $h < count($product_htu); $h++) {
                // ($product_htu[$h]->name != null && $product_htu[$h]->name_en != null) ? $product_htus[$h] = array('name' => $product_htu[$h]->name,'name_en' => $product_htu[$h]->name_en) : $product_htus[$h] = array('name' => NULL,'name_en' => NULL);
                $product[$i]->htu[$h] = $product_htu[$h];
            }
            // $product_netto = $this->ProductModel->read_product_netto($product[$i]->id);
            // for ($h=0; $h < count($product_netto); $h++) {
            //     ($product_netto[$h]->name != null && $product_netto[$h]->name_en != null) ? array_push($product_nettos, array('name' => $product_netto[$h]->name,'name_en' => $product_netto[$h]->name_en)) : array_push($product_htus[$i], array('name' => NULL,'name_en' => NULL));
            // }
            // $product[$i]->netto = $product_nettos;
        }
        if (count($product) > 1) {
            $product = $product;
        } else {
            $product = $product[0];
        }
        if (!empty($product)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => $product)
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