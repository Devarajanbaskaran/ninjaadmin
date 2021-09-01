<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends CI_Controller {
	public $users;
	public $organization;
	public $category;
	public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper("authentication_helper");
        $this->load->helper("common_helper");
        $this->load->model('organization_model');
        $this->load->model('ticket_model');
        $this->users = $this->ticket_model->get_all_users();
        $this->organization = $this->ticket_model->get_all_organization();
        $this->category = $this->ticket_model->get_all_category();

    } 

	public function index()
	{ 	
		admin_verify_session(); 
		$this->get_all(); 
		$this->load->view('ticket_view',$this->v_data);  
	}

	public function get_all()
	{     
		$favourite_organization = $this->organization_model->get_favourite_organization(array(
			"user_id"	=> $this->session->userdata('user_id'),
	        "rand_str"   => $this->session->userdata('rand_str'), 
	        "is_deleted" => 0,
      	)); 
      	$favourite = [];
		foreach($favourite_organization as $value){
			array_push($favourite,$value->organization_id );
		}  
		if(count($favourite) > 0){
			$organization = $this->organization_model->get_organization(' id in ('.implode(',', $favourite).')');
		}else{
			$organization = $this->organization_model->get_organization(array(
			"rand_str"   => $this->session->userdata('rand_str'), 
	        "is_deleted" => 0,));
		} 
		$category = $this->ticket_model->get_all_category(array(),'category_name ASC',$favourite);  
		$assignee = $this->ticket_model->get_all_assignee($favourite);
		$temp = [];
		foreach($organization as $o){  
			$temp[$o->id]['category'] = $this->ticket_model->get_all_category(array(),'category_name ASC',$o->id);
			$temp[$o->id]['assignee'] = $this->ticket_model->get_all_assignee($o->id);
		}  
		
	    $this->v_data = array( 
	      'page_id' => 'tickets',
		  'organization' => $organization, 
		  'category' => $category,
		  'ticket_status' => getTicketStatus(),
		  'assignee' => $assignee,
		  'temp' => $temp
	    );
	}

	public function list(){
		$users = $organization = $category = $priority = $ticketstatus = [0 =>'']; 
		foreach($this->users as $u){ $users[$u->id] = ($u->first_name != '') ? ucwords($u->first_name." ".$u->last_name)  : $u->email; } 
		foreach($this->organization as $o){ $organization[$o->id] = ucfirst($o->name); } 
		foreach(getPriority() as $p){$priority[$p['id']] = $p['text']; } 
		foreach(getTicketStatus() as $t){$ticketstatus[$t['id']] = $t['text']; } 
		foreach($this->category as $c){ $category[$c->id] = ucfirst($c->category_name); } 
		$filter_organization = $this->input->post('filter_organization',TRUE);
		if($filter_organization == 0) {
			$favourite_organization = $this->organization_model->get_favourite_organization(array(
			"user_id"	=> $this->session->userdata('user_id'),
	        "rand_str"   => $this->session->userdata('rand_str'), 
	        "is_deleted" => 0,
	      	)); 
	      	$favourite = [];
			foreach($favourite_organization as $value){
				array_push($favourite,$value->organization_id );
			}
			$filter_organization = $favourite;
		}
		$filter_category = $this->input->post('filter_category',TRUE);
		$filter_assignee = $this->input->post('filter_assignee',TRUE);
		$filter_status = $this->input->post('filter_status',TRUE);
		$start = $this->input->post('start',TRUE);
		$length = $this->input->post('length',TRUE);
		$recordsTotal = $this->ticket_model->get_ticket_count($filter_organization,$filter_assignee,$filter_category,$filter_status);
		$tickets = $this->ticket_model->get_all_ticket($filter_organization,$filter_assignee,$filter_category,$filter_status,$start,$length);
		$recordsFiltered = sizeof($tickets);
		$data = array(); 
		foreach ($tickets as $t) { 
            $row = array(); 
            $row[] = $t->ticket_no;
            $row[] = $t->summary;
            $row[] = (isset($users[$t->assignee])) ? $users[$t->assignee] : '' ;
            $row[] = (isset($users[$t->contact])) ? $users[$t->contact] : '';
            $row[] = $organization[$t->organization_id];
            $row[] = $priority[$t->priority]; 
            $row[] = $category[$t->category];
            $row[] = gen_locale_date($t->due_date);
            $row[] = ucfirst($ticketstatus[$t->ticket_status]); 
            $row[] = gen_locale_date($t->modified_date); 
            $row[] = $t->organization_id; 
            $data[] = $row;
        }  
		$response = [
            'recordsTotal' => $recordsFiltered,
			'recordsFiltered' => $recordsTotal,
			'data' => $data,
			'token'    => $this->security->get_csrf_hash(), 
        ]; 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);

	}
}
