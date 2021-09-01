<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization extends CI_Controller {
	public $organization; 
	public $v_data; 

	public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper("authentication_helper");
        $this->load->model('organization_model');
        
    }  
	public function index()
	{ 	 
		admin_verify_session(); 
		$this->get_all(); 
		$this->load->view('organization_view',$this->v_data);
	}

	public function get_all()
	{ 
	    $organization = $this->organization_model->get_organization(array(
	        "rand_str"   => $this->session->userdata('rand_str'), 
	        "is_deleted" => 0,
      	));  
		$favourite_organization = $this->organization_model->get_favourite_organization(array(
			"user_id"	=> $this->session->userdata('user_id'),
	        "rand_str"   => $this->session->userdata('rand_str'), 
	        "is_deleted" => 0,
      	));
		$favourite = [];
		foreach($favourite_organization as $value){
			array_push($favourite,$value->organization_id );
		}
	    $this->v_data = array(
	    	'page_id' => 'organization',
	      'organization' => $organization,
		  'favourite_organization' => $favourite, 
	    );
	}
	
	public function save(){
		$response = [];
        $_message = $_error = $_redirecturl= null; 
		$this->form_validation->set_rules('organization_list[]',"organization", "required|trim|numeric");
		if ($this->form_validation->run() == TRUE) {
			$organization_list = $this->input->post("organization_list",TRUE); 
			if($this->validate_data($organization_list)){
				$this->insert(); 
				$_message = 'Oraganization saved successfully';
				$_redirecturl = base_url("ticket");
			}else{
				$_error = true;
            	$_message = 'Please select valid organization';
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

	public function insert(){
		$organization_list = $this->input->post("organization_list",TRUE);
		$insert_data = array(); 
		$this -> db -> where('rand_str', $this->session->userdata('rand_str'));
		$this -> db -> delete('sa_organization');
		foreach($organization_list as $organization_id){
			$insert_data[] = array(
				'user_id' => $this->session->userdata('user_id'),
				'rand_str' => $this->session->userdata('rand_str'), 
				'organization_id' => $organization_id, 
				"created_date" => date("Y-m-d"),
                "created_time" => date("H:i:s"),
				'created_by' => $this->session->userdata('user_id'),
				'created_ip' => $this->input->ip_address(),  
			);
		}		 
		return $this->organization_model->insert_organization($batch_insert = TRUE, $insert_data); 
	}

	public function validate_data($organization_list){ 
		$invlid_organization = [];
		foreach($organization_list as $organization_id){
			$organization = $this->organization_model->get_organization(array(
				"id"   => $organization_id, 
				"rand_str"   => $this->session->userdata('rand_str'), 
				"is_deleted" => 0,
			  ));
			if(!$organization){
				array_push($invlid_organization,$organization_id);
			}
		}
		if(sizeof($invlid_organization) > 0){
			return false;
		}
		return true;
		 
	}
}
