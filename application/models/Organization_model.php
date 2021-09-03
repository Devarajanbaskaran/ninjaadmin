<?php
class Organization_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_organization($where = array())
  {
    return $this->db->where($where)->order_by('name ASC ')->get("organization")->result();

  }
  public function get_favourite_organization($where = array())
  {
    return $this->db->select('GROUP_CONCAT(organization_id) AS organization_list')->where($where)->get("sa_organization")->row();

  }
  
  public function insert_organization($batch_insert = false,$insert_data = array())
  {
     if($batch_insert){
          $this->db->insert_batch('sa_organization', $insert_data);
        }else{
          $this->db->insert_batch('sa_organization', $insert_data);
        }  
        return TRUE;
  }
  
}

 ?>
