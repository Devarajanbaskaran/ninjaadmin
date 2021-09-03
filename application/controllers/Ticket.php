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

		$organization = $assignee = $category = $ticketstatus = null;
		$priority = $favourite = $custom_data = $ticket_status =  null;
		$favourite_organization = $this->organization_model->get_favourite_organization(array(
			"user_id"	=> $this->session->userdata('user_id'),  
	        "is_deleted" => 0,
      	)); 
		if($favourite_organization->organization_list){
			$organization = $this->organization_model->get_organization(' id in ('.$favourite_organization->organization_list.') ');			
			$favourite = explode(',', $favourite_organization->organization_list);
			$category = $this->ticket_model->get_all_category(array(),'category_name ASC',$favourite);  
			$assignee = $this->ticket_model->get_all_assignee($favourite);
			$ticket_status = getTicketStatus();
			$custom_data = [];
			foreach($organization as $o){  
				$custom_data[$o->id]['category'] = $this->ticket_model->get_all_category(array(),'category_name ASC',$o->id);
				$custom_data[$o->id]['assignee'] = $this->ticket_model->get_all_assignee($o->id);
			} 
		} 
	    $this->v_data = array( 
	      'page_id' => 'tickets',
		  'organization' => $organization, 
		  'category' => $category,
		  'ticket_status' => $ticket_status,
		  'assignee' => $assignee,
		  'custom_data' => $custom_data
	    );
	    
	}

	public function list(){
		$filter_category = $this->input->post('filter_category',TRUE);
		$filter_assignee = $this->input->post('filter_assignee',TRUE);
		$filter_status = $this->input->post('filter_status',TRUE);
		$start = $this->input->post('start',TRUE);
		$length = $this->input->post('length',TRUE); 
		$search = $this->input->post('search',TRUE);
		$order = $this->input->post('order',TRUE);
		$search_key = $search['value'];
		$order_column = (isset($order[0]['column'])) ? $order[0]['column'] : 0 ; 
		$order_dir = (isset($order[0]['dir'])) ? $order[0]['dir'] : 'asc' ; 

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
	        "is_deleted" => 0,
      		));
			$filter_organization = explode(',',$favourite_organization->organization_list);
		} 
		$column_list = ['t.ticket_no','t.summary','t.assignee','t.created_by','o.name','priority_text','c.category_name','t.due_date','ticket_status_text','t.modified_date'];
		$order_by = $column_list[$order_column];
		$order_dir = $order_dir;  
		$recordsTotal = $this->ticket_model->get_ticket_count($filter_organization,$filter_assignee,$filter_category,$filter_status,$search_key);
		$tickets = $this->ticket_model->get_all_ticket($filter_organization,$filter_assignee,$filter_category,$filter_status,$start,$length,$order_by,$order_dir,$search_key);
		$recordsFiltered = sizeof($tickets); 
		$data = array(); 
		foreach ($tickets as $t) { 
            $row = array(); 
            $row[] = $t->ticket_no;
            $row[] = $t->summary;
            $row[] = (isset($users[$t->assignee])) ? $users[$t->assignee] : '' ;
            $row[] = (isset($users[$t->created_by])) ? $users[$t->created_by] : '';
            $row[] = (isset($organization[$t->organization_id])) ? $organization[$t->organization_id] : '';
            $row[] = (isset($priority[$t->priority])) ? $priority[$t->priority] : '';
            $row[] = (isset($category[$t->category])) ? $category[$t->category] : '';
            $row[] = gen_locale_date($t->due_date);
            $row[] = ucfirst($ticketstatus[$t->ticket_status]); 
            $row[] = gen_locale_date($t->modified_date); 
            $row[] = $t->organization_id;  	//don't change row position
            $row[] = $t->id; 				//don't change row position
            $data[] = $row;					//don't change row position
        }  
		$response = [
            'recordsTotal' => $recordsFiltered,
			'recordsFiltered' => $recordsTotal,
			'data' => $data,
			'token'    => $this->security->get_csrf_hash(), 
        ]; 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);

	}

	 public function assignticket()
    {	
		$_error = $_message = $mail_status =  null;
		foreach($this->users as $u){ $users[$u->id] = ($u->first_name != '') ? ucwords($u->first_name." ".$u->last_name)  : $u->email; } 
    	$ticket_id	= $this->input->post('id',TRUE);
    	$ticket_no = $this->input->post('ticket_no',TRUE);
		$assignee = $this->input->post('assignee',TRUE);   
		if($ticket_no != 0) {
			$_error = false;
			$_message = 'Ticket Assigned Successfully';	
			$oldVal = $this->ticket_model->getOldValue($ticket_id, 'assignee'); 
            $logArray = array( 
                'ticket_id' => $ticket_id,
                'user_id' => $this->session->user_id,
                'rand_str' => $this->session->rand_str,
                'old_value' => $oldVal,
                'new_value' => ($assignee),
                'db_column' => 'assignee',
                'comments' => '',
                'activity_type' => 2, // If 2 - Ticket Updated
                'created_date' => date("Y-m-d"),
                'created_time' => date("H:i:s"),
                'created_by' => ($this->session->user_id) ? $this->session->user_id : 0,
                'created_ip' => $this->input->ip_address(),
            );
			 
		}
		$result = $this->ticket_model->updateDetails($column = 'assignee', $assignee,  $ticket_no, $ticket_id); 
		if($result){
			$_message = 'Tiket('.$ticket_no.') assigned to ['.$users[$assignee].'] successfully ';
			if ($oldVal != $assignee)
                $ticket_activity_id = $this->ticket_model->log_ticket_activity($logArray, true);
				if($ticket_activity_id){
					$this->send_assign_mail($ticket_activity_id,$ticket_id);
				}
		} 
		$response = [
            'status'   => 200,
            'error'    => $_error,
            'messages' => $_message,
            'token'    => $this->security->get_csrf_hash(),
        ]; 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
	 
	public function send_assign_mail($ticket_activity_id,$ticket_id){
		$users = $organization = $category = $priority = $ticketstatus = [0 =>''];
		$organization_info =  $ticket_info = null;
		foreach($this->users as $u){ $users[$u->id] = ($u->first_name != '') ? ucwords($u->first_name." ".$u->last_name)  : $u->email; } 
		foreach($this->organization as $o){ $organization[$o->id] = ucfirst($o->name); } 
		foreach(getPriority() as $p){$priority[$p['id']] = $p['text']; } 
		foreach(getTicketStatus() as $t){$ticketstatus[$t['id']] = $t['text']; } 
		foreach($this->category as $c){ $category[$c->id] = ucfirst($c->category_name); } 
		$ticket_info = $this->db->select('*')->where('id',$ticket_id)->get('ticket')->row();
		$organization_info = $this->db->select('*')->where('id',$ticket_info->organization_id)->get('organization')->row();
		if(sizeof($ticket_info) == 0 ||  sizeof($organization_info) == 0){
			return false;
		}
		 /* Gen Ticket History */
		 $cond = array(
            "ticket_id" => $ticket_id,
            "id!=" => $ticket_activity_id,
            "activity_type!=" => 4,
        );
        $this->db->select('*');
        $this->db->where($cond);
        $this->db->limit(4);
        $this->db->order_by('created_date DESC, created_time DESC');
        $q = $this->db->get('ticket_activity');
        $getResultArray = $q->result_array();
        $contentData['contents'] = $this->ticket_model->genTicketActivityContents($getResultArray);
        $getTicketHistory = $this->load->view('mail_templates/ticket_history', $contentData, true); 
		$data['action_by'] = $users[$this->session->userdata('user_id')];
		$data['assigned_to'] = $users[$ticket_info->assignee];;
		$data['ticket_history'] = $getTicketHistory;
		$assigneeMessage = $this->load->view('mail_templates/assignee_update', $data, true); 
		$this->load->library('email');
		$config = array (
			'mailtype' => 'html',
			'charset'  => 'utf-8',
			'priority' => '1'
		);
		$this->email->initialize($config);
		$this->email->from($organization_info->custom_outbound_email, $organization_info->name . " Help Desk");
		$this->email->subject('[Ticket #' . $ticket_info->ticket_no . '] ' . $ticket_info->summary);
		$this->email->message($assigneeMessage);
		$this->email->to('devarajeorchids@gmail.com'); 
		//$returnArray['at2asgm'][] = $this->email->send();
		//$this->email->clear();
		return true;
	}
}
