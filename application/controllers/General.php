<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class General extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load General Model
        $this->load->model('General_model', 'GeneralModel');
        $this->load->model('Url_model', 'UrlModel');
        $this->load->model("cms/Page_model", "Page");
        $this->load->model("cms/Email_server_model", "Email_server");
    }
     /**
     * Get General with API
     * -------------------------
     * @method: GET
     */
    public function all_get() {
        header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'], $q_strings);
        // Read General
        $url = $this->UrlModel->read_url()[0];
        $footer = array();
        $footer = $this->GeneralModel->read_footer($q_strings);
        $icon = array();
        $icons = array();
        $icon = $this->GeneralModel->read_icon();
        $footer_icon = array();
        $navbar_icon = array();
        for ($i=0; $i < count($icon); $i++) {
            ($icon[$i]->type == 'navbar') ? $navbar_icon = array('navbar' => IPSERVER.$icon[$i]->image_url) : NULL;
            ($icon[$i]->type == 'footer') ? $footer_icon = array('footer' => IPSERVER.$icon[$i]->image_url) : NULL;
        }
        $icons = array_merge($navbar_icon,$footer_icon);

        if ($icon > 0 AND !empty($icon)) {
            // Success
            $message = [
                'status' => true,
                'message' => "Success",
                'data' => array('content' => array('footer' => $footer, 'icon' => $icons))
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

    public function page_get($p = 0) {
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;
        $id     = $this->security->xss_clean($p);
        
        $output = array();
        $whereFields = array(
            'a.id' => $q_strings['id'],
            'a.status' => $q_strings['status']
        );
        $this->Page->set_variable('whereFields', $whereFields);
        $output = $this->Page->read()[0];
        $output->contact = json_decode($output->contact);
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

    public function sendmail_post() {
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
				//$model = "News";
				/* Start Transaction */
				//$this->db->trans_start();
                
                // echo "<pre>";
                // // var_dump($_FILES);
                // var_dump($dt_post);
                $this->load->library("PHPMailerAutoload");
                $mail = $this->phpmailerautoload->load();

                try {                       // TCP port to connect to
                    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                    $mail->isSMTP();   
                    $output = $this->Email_server->read()[0];
                    $mail->Host = $output->host;  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = $output->emailhost;                 // SMTP username
                    $mail->Password = $output->password;                           // SMTP password
                    $mail->SMTPSecure = $output->secure;                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = $output->port;       
                    // TCP port to connect to
                    if ($dt_post['subject'] == 1) {
                        $mail->setFrom($output->emailhost, 'PERTANYAAN');     // Add a recipient
                        $titlemail = 'PERTANYAAN';
                        $mail->addAddress($output->sendquestion, $output->sendquestion);
                    }else if ($dt_post['subject'] == 2) {
                        $mail->setFrom($output->emailhost, 'KRITIK & SARAN');     // Add a recipient
                        $titlemail = 'KRITIK & SARAN';
                        $mail->addAddress($output->sendadvice, $output->sendadvice);
                    }else if ($dt_post['subject'] == 3) {
                        $mail->setFrom($output->emailhost, $dt_post['any']);     // Add a recipient
                        $titlemail = $dt_post['any'];
                        $mail->addAddress($output->sendany, $output->sendany);
                    }
                    $mail->addReplyTo($dt_post['email'], $dt_post['name']);
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = $titlemail.' : '.date('d-M-Y').' @ galenium.com';
                    $mail->Body='<style type="text/css">
                                    .header {background: #f3f3f3;}
                                    .header .columns {padding-bottom: 0;}
                                    .header p {color: #fff;margin-bottom: 0;}
                                    .header .wrapper-inner {padding: 20px; /*controls the height of the header*/}
                                    .header .container {background: #f3f3f3;}
                                    .wrapper.secondary {background: #f3f3f3;}
                                </style>
                            <!-- move the above styles into your custom stylesheet -->
                            <table align="center" bgcolor="#f3f3f3" width="100%" class="wrapper header float-center">
                                <tr>
                                    <td class="wrapper-inner">
                                        <table align="center" width="100%" class="container">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table class="row collapse">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="small-6 large-6 columns first" valign="middle">
                                                                        <table>
                                                                            <tr>
                                                                                <th></th>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="small-6 large-6 columns last" valign="middle">
                                                                        <table>
                                                                            <tr>
                                                                                <th>
                                                                                    <p class="text-right"><h3 class="text-default">'.$titlemail.'</h3></p>
                                                                                </th>
                                                                            </tr>
                                                                        </table>
                                                                        <table>
                                                                            <tr>
                                                                                <th>
                                                                                    <p class="text-right"><h3 class="text-default">'.$dt_post['message'].'</h3></p>
                                                                                </th>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>';
                            $mail->send();
                } catch (Exception $e) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
        // die();
        // // var_dump($dt_post["title_${i}"]);
        // echo "</pre>";
        //$this->db->trans_complete();
        /* End Transaction */
                if (!$mail->send()) {
					$response_data = array(
						'error'   => $mail->ErrorInfo
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
				} else {
					$response_data = array(
						'status'     => 'Message has been sent'
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