<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
class Users extends My_Controller {
    public function __construct() {
        parent::__construct();
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        // Load Model
        $this->load->model("$this->directory/User_model", 'UserModel');

    }


	public function all_get(){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;

        $model  = "UserModel";

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';;

           

            $whereLike = array();
            if (!empty($q_strings["fullname"])) {
                $whereLike['lower(a.fullname)'] = strtolower($q_strings["fullname"]);
            }
            if (!empty($q_strings["email"])) {
                $whereLike['lower(a.email)'] = strtolower($q_strings["email"]);
            }
            if (!empty($q_strings["role"])) {
                $whereLike['lower(a.role)'] = strtolower($q_strings["role"]);
            }
            $orderFields = array(
                'a.id' => 'ASC',
            );

            if(isset($q_strings['limit'])) $this->$model->limit = (int) $q_strings['limit']; unset($q_strings['limit']);
            if(isset($q_strings['offset'])) $this->$model->offset = (int) $q_strings['offset']; unset($q_strings['offset']);

            $this->$model->set_variable('orderFields', $orderFields);
            $this->$model->set_variable('whereLike', $whereLike);
            $output = $this->$model->read();
            
            $response_data = array();
            if ($output != []) {
                $response_data = $output;
                $error   = false;
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

    public function detail_get($id = 0){
		header("Access-Control-Allow-Origin: *");
        parse_str($_SERVER['QUERY_STRING'],$q_strings);
        $error  = true;

        $model  = "UserModel";

        // Token Validation
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // $institute_branch_id = $this->security->xss_clean($is_valid_token['data']->institute_branch_id);
            // $this->$model->whereFields = $q_strings;
            // $this->$model->whereFields['a.is_delete'] = '0';;
            $whereFields = array(
                'a.id' => $id
            );
            
            // $orderFields = array(
            //     'a.id' => 'ASC',
            // );
            
            // $this->$model->set_variable('orderFields', $orderFields);
            $this->$model->set_variable('whereFields', $whereFields);
            $output = $this->$model->read($id,false);
            // var_dump($output);
            
            $response_data = array();
            if ($output != []) {
                $response_data = $output[0];
                $error   = false;
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

    public function create_post() {
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

        $model  = "UserModel";

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
                'role' => $this->input->post('role',TRUE) ?? 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert User in Database
            $output = $this->$model->insert_user($insert_data);
            if ($output > 0 AND !empty($output)) {
                // Success 200 Code Send
                $message = [
            'status' => true,
            'message' => "Create User successfully"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Failed Create User."
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

	public function update_put($id = 0) {
        // CALL JSON POST
        header("Access-Control-Allow-Origin: *");

        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = json_decode(file_get_contents('php://input'),true); 
        $_POST = $this->security->xss_clean($_POST);
        $dt = $this->input->post();

        $dt_post = array();
        foreach ($dt as $key => $value) {
            $dt_post[$key] = $value;
        }
        // DEFINE ATTR
        $error      = TRUE;

        // validation
        $this->form_validation->set_rules('fullname', 'Full Name', 'trim|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[80]|is_unique[users.email]',
            array('is_unique' => 'This %s already exists please enter another email address')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|max_length[100]');
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
				$model = "UserModel";
				/* Start Transaction */
				$this->db->trans_start();
                
                
                $whereFields = array(
                    'a.id' => $id,
                );
                
                $this->$model->set_variable('whereFields', $whereFields);
                $dataUser = $this->$model->read($id,false);
                if (count($dataUser) < 1) {
                    $response_data = array(
						'error'   => "Data Not Found"
					);
					$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
                    return;
                }
                $whereFields = array();
                $dataToDb = array();
                // $dataToDb = array(
                //     'id' => $id
                // );
                // var_dump($dt_post);

                if (!empty($dt_post["fullname"])) {
                    $dataToDb["fullname"] = $dt_post["fullname"]; 
                }else{
                    $dataToDb["fullname"] = $dataUser[0]->fullname; 
                }

                if (!empty($dt_post["email"])) {
                    $dataToDb["email"] = $dt_post["email"]; 
                }else{
                    $dataToDb["email"] = $dataUser[0]->email; 
                }

                if (!empty($dt_post["role"])) {
                    $dataToDb["role"] = $dt_post["role"]; 
                }else{
                    $dataToDb["role"] = $dataUser[0]->role; 
                }

                if (!empty($dt_post["password"])) {
                    $dataToDb["password"] = password_hash($dt_post["password"],PASSWORD_DEFAULT); 
                }else{
                    $dataToDb["password"] = $dataUser[0]->password; 
                }

                $dataToDb["updated_at"] = date('Y-m-d H:i:s');
                
				$id = $this->$model->update_user($id,$dataToDb);
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

    public function destroy_delete($id = 0){
        $data       = $this->form_post();
        // DEFINE ATTR
        $dt_post    = $data['dt_postput'];
        $device     = $data['dt_server']['HTTP_USER_AGENT'];
        $ip         = $this->get_client_ip();
        $error      = TRUE;

        $model = "UserModel";
		/* Start Transaction */
		$this->db->trans_start();
                
                
        $whereFields = array(
            'a.id' => $id,
        );
                
        $this->$model->set_variable('whereFields', $whereFields);
        $dataUser = $this->$model->read($id,false);
        if (count($dataUser) < 1) {
            $response_data = array(
				'error'   => "Data Not Found"
			);
			$this->REST_Return(422, 'Oops, something went wrong, Please try again latter.', $response_data);
            return;
        }

        // $whereFields = array(
        //     'a.id' => $id,
        // );
        // $this->$model->set_variable('whereFields', $whereFields);
        $id = $this->$model->delete_user($id);
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
					$this->REST_Return(201, 'SUCCESS DELETE DATA', $response_data);
				}
                return;     
    }
}