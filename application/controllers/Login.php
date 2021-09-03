<?php
defined('BASEPATH') OR exit('No direct script access allowed');  

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('login_model');
        
    } 

	public function index()
	{
       
		$this->load->view('login_view');
	}


	public function validate()
    {   
        $response = [];
        $_message = $_error = $_redirecturl= null;
        $this->form_validation->set_rules("form_username", "email id", "required|trim");
        $this->form_validation->set_rules("form_password", "password", "required|trim"); 
        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post("form_username",TRUE);  
            $password = $this->input->post("form_password");  
            $user = $this->login_model->loginDataControl(array("email" => $email,'is_deleted' => 0,'user_role'=>1));
            if($user){
                @$user_email = $user->email;@$user_password = $user->password;@$user_id = $user->id;
                if(password_verify($password, $user_password)){
                    $this->set_session_value($user);
                    $_redirecturl = base_url("organization");
                }else{
                    $_error = true;
                    $_message = 'Invalid credentials, please verify them and retry';     
                }
            }else{
                $_error = true;
                $_message = 'Invalid credentials, please verify them and retry';
            }

        }else{
            $_error = true;
            $_message = validation_errors();

        }
        $response = [
            'status'   => 200,
            'error'    => $_error,
            'messages' => $_message,
            'token'    => $this->security->get_csrf_hash(),
            'url'      => $_redirecturl 
        ]; 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
  
    }

    public function set_session_value($user = null){ 
        $this->session->user_role      = (int)$user->user_role;
        $this->session->access_type      = (int)$user->access_type;
        $this->session->user_id          = (int)$user->id;
        $this->session->organization_id  = (int)$user->organization_id;
        $this->session->rand_str         = $user->rand_str;
        $this->session->owner_id         = $user->owner_id;
        $this->session->user_fname       = $user->first_name;
        $this->session->disable_internal_note      = (int)$user->disable_internal_note; 
        if($user->profile_image !=""){
        $this->session->profile_image =$user->profile_image;
        } else {
        $this->session->profile_image ="profile_img.png";
        } 
        $this->session->user_lname       = $user->last_name;
        $this->session->user_email       = $user->email;
        $this->session->isLoggedIn       =false; 

    }

    public function logout()
    {
      $this->session->sess_destroy();
      redirect(base_url("login"));
    }
}
