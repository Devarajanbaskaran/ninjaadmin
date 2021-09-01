<?php
class Ticket_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_all_ticket($organization_list = null,$category_list = null,$assignee_list = null,$ticket_status_list,$start,$length)
  {
    $this->db->select('*');
    $this->db->from('ticket');
    $this->db->where('is_deleted', 0); 
    $this->db->where('rand_str', $this->session->rand_str);
    $this->db->where_in('organization_id', $organization_list);
    $this->db->where_in('assignee', $assignee_list); 
    $this->db->where_in('category', $category_list); 
    $this->db->where_in('ticket_status', $ticket_status_list); 
    $this->db->order_by('ticket_no DESC'); 
    $this->db->limit($length, $start);
    return $this->db->get()->result(); 
  }

  public function get_ticket_count($organization_list = null,$category_list = null,$assignee_list = null,$ticket_status_list)
  { 
    $this->db->from('ticket');
    $this->db->where('is_deleted', 0); 
    $this->db->where('rand_str', $this->session->rand_str); 
    $this->db->where_in('organization_id', $organization_list);
    $this->db->where_in('assignee', $assignee_list); 
    $this->db->where_in('category', $category_list); 
    $query = $this->db->get();
    return $query->num_rows();  
  }

  public function get_all_assignee($where_in = null)
  {
      $this->db->select('id,first_name,last_name,email,access_type,organization_id');
      $this->db->from('users');
      $this->db->where('is_deleted', 0); 
      $this->db->where('assignee', 1); 
      $this->db->where('rand_str', $this->session->rand_str); 
      $this->db->where_in('organization_id',$where_in);
      $this->db->order_by('first_name ASC'); 
      return $this->db->get()->result();

  }
  
  public function get_all_users($where_in = null)
  {
      $this->db->select('id,first_name,last_name,email,access_type,organization_id');
      $this->db->from('users'); 
      return $this->db->get()->result();

  }
  public function get_all_organization($where_in = null)
  {
      $this->db->select('*');
      $this->db->from('organization'); 
      return $this->db->get()->result();

  }


  public function get_all_category($where = array(),$order_by=null,$where_in=null)
  {
    $this->db->select('ticket_category.id,category_name,organization_id,name  as organization_name');
    $this->db->from('ticket_category');
    $this->db->where($where);
    $this->db->where('ticket_category.is_deleted',0);
    $this->db->where_in('organization_id',$where_in);
    $this->db->join('organization', 'organization.id = ticket_category.organization_id', 'left'); 
    $this->db->order_by($order_by); 
    return $this->db->get()->result(); 

  }
   
  
}

 ?>
