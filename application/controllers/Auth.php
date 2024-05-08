<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Auth extends My_Controller {

    public function __construct() {
        parent::__construct();
        // Load User Model
        $this->load->model('User_model', 'UserModel');
    }

    /**

     * User Login API
     * --------------------
     * @param: username or email
     * @param: password
     * --------------------------
     * @method : POST
     * @link: auth/login
     */

    public function login_post(){
        // CALL JSON POST
        $data       = $this->json_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];

        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required|max_length[20]');
        if ($this->form_validation->run() == FALSE){
            // Form Validation Errors
            $response_data = array(
                'error'     => $this->form_validation->error_array()
            );
            $this->REST_Return(422, $response_data);
        } else {
            // $this->input->post('username') = $_POST['username'];
            # Validation
            // $headers = $this->input->request_headers();

            if(!isset($dt_post['email']) || !isset($dt_post['password'])) {
                // Form Validation Errors
                $message = array(
                    'message' => 'email and password not found!',
                    'code' => '201'
                );
                $metadata = array('metadata'=>$message);
                $this->response($metadata, REST_Controller::HTTP_CREATED);
                return;
            }else{
                // Load Login Function
                $output = $this->UserModel->user_login($dt_post['email'],$dt_post['password']);
                if (!empty($output) AND $output != FALSE){
                    // Load Authorization Token Library
                    $this->load->library('Authorization_Token');

                    // Generate Token
                    $token_data['id'] = $output->id;
                    $token_data['time'] = time();

                    $user_token = $this->authorization_token->generateToken($token_data);

                    $response_data = array(
                        'status'    => true,
                        'token'     => $user_token
                    );
                    $this->REST_Return(200, 'Login Successful', $response_data);
                    return;
                } else {
                    // Login Error
                    $this->REST_Return(422, 'No user with that address/Your Password Invalid, please Try Again');
                    return;
                }
            }
        }
    }
}