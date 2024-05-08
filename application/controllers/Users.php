<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Users extends \Restserver\Libraries\REST_Controller
{
    public function __construct() {
        parent::__construct();
        // Load User Model
        $this->load->model('User_model', 'UserModel');
    }

    /**
     * User Register
     * --------------------------
     * @param: fullname
     * @param: username
     * @param: email
     * @param: password
     * --------------------------
     * @method : POST
     * @link : api/user/register
     */
    public function register_post() {
        header("Access-Control-Allow-Origin: *");

        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = json_decode(file_get_contents('php://input'),true); 
        $_POST = $this->security->xss_clean($_POST);
        # Form Validation
        $this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[80]|is_unique[users.email]',
            array('is_unique' => 'This %s already exists please enter another email address')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == FALSE){
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $insert_data = [
                'fullname' => $this->input->post('fullname', TRUE),
                'email' => $this->input->post('email', TRUE),
                'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert User in Database
            $output = $this->UserModel->insert_user($insert_data);
            if ($output > 0 AND !empty($output)) {
                // Success 200 Code Send
                $message = [
                    'status' => true,
                    'message' => "User registration successful"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Not Register Your Account."
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}