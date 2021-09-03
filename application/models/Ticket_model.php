<?php
class Ticket_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_all_ticket($organization_list = null,$category_list = null,$assignee_list = null,$ticket_status_list,$start,$length,$order_by="t.ticekt_no",$oder_dir='ASC',$search_key = null)
  {
    $this->db->select("t.id,t.ticket_no,t.rand_str,t.summary,t.priority,t.due_date,t.ticket_status,t.modified_date,t.organization_id,o.name,t.assignee,u.first_name,u.last_name,u.email,c.category_name,u1.email AS creator,t.created_by,t.category,
    CASE 
        WHEN t.priority = 1 
        THEN 'Low'
        WHEN t.priority = 2 
        THEN 'Medium'
        WHEN t.priority = 3
        THEN 'High'
    END AS priority_text,
    CASE 
        WHEN t.ticket_status = 1 
        THEN 'Open'
        WHEN t.ticket_status = 2 
        THEN 'Waiting'
        WHEN t.ticket_status = 3
        THEN 'closed'
        WHEN t.ticket_status = 4
        THEN 'unassigned'
    END AS ticket_status_text ");
    $this->db->from('ticket t');
    $this->db->where('t.is_deleted', 0); 
    $this->db->where('t.rand_str', $this->session->rand_str);
    $this->db->join('organization o', 't.organization_id = o.id', 'left'); 
    $this->db->join('users u', 't.assignee = u.id', 'left'); 
    $this->db->join('users u1', 't.created_by = u1.id', 'left'); 
    $this->db->join('category c', 't.category= c.id', 'left'); 
    $this->db->where_in('t.organization_id', $organization_list);
    $this->db->where_in('t.assignee', $assignee_list); 
    $this->db->where_in('t.category', $category_list); 
    $this->db->where_in('t.ticket_status', $ticket_status_list); 
    if(strlen($search_key) > 0){
      $this->db->where('(t.summary LIKE "%'.$search_key.'%" 
      OR u.first_name LIKE "%'.$search_key.'%" 
      OR u.last_name LIKE "%'.$search_key.'%"
      OR u.email LIKE "%'.$search_key.'%"
      )');
    }
    
    $this->db->order_by($order_by,$oder_dir); 
    $this->db->limit($length, $start);
    return $this->db->get()->result(); 
  }

  public function get_ticket_count($organization_list = null,$category_list = null,$assignee_list = null,$ticket_status_list,$search_key)
  { 
    $this->db->select('t.id,t.ticket_no,t.rand_str,t.summary,t.priority,t.due_date,t.ticket_status,t.modified_date,
    t.organization_id,o.name,t.assignee,u.first_name,u.last_name,u.email,c.category_name,u1.email AS creator,t.created_by,t.category');
    $this->db->from('ticket t');
    $this->db->where('t.is_deleted', 0); 
    $this->db->where('t.rand_str', $this->session->rand_str);
    $this->db->join('organization o', 't.organization_id = o.id', 'left'); 
    $this->db->join('users u', 't.assignee = u.id', 'left'); 
    $this->db->join('users u1', 't.created_by = u1.id', 'left'); 
    $this->db->join('category c', 't.category= c.id', 'left'); 
    $this->db->where_in('t.organization_id', $organization_list);
    $this->db->where_in('t.assignee', $assignee_list); 
    $this->db->where_in('t.category', $category_list); 
    $this->db->where_in('t.ticket_status', $ticket_status_list);
    if(strlen($search_key) > 0){
      $this->db->where('(t.summary LIKE "%'.$search_key.'%" 
      OR u.first_name LIKE "%'.$search_key.'%" 
      OR u.last_name LIKE "%'.$search_key.'%"
      OR u.email LIKE "%'.$search_key.'%"
      )');
    }
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
  
  public function get_all_users($where = array())
  {
      $this->db->select('id,first_name,last_name,email,access_type,organization_id');
      $this->db->from('users'); 
      $this->db->where($where); 
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
   
  public function updateDetails($col, $val, $ticket_no, $id)
  { 
        $cond = array(
            'id' => $id,
            'ticket_no' => $ticket_no,
            'rand_str' => $this->session->rand_str
        );
        $dbdata = array(
            $col => $val,
            'modified_date' => gen_db_date(date('Y-m-d')),
            'modified_time' => date('H:i:s'),
        ); 
        $this->db->where($cond);
        $result = $this->db->update('ticket', $dbdata);
        return $result;
  }
  
  public function getOldValue($ticket_id, $column)
    {
        $where = array(
            "id" => $ticket_id, 
        );
        $this->db->select($column);
        $this->db->where($where);
        $q = $this->db->get('ticket');
        $data = $q->result_array();
        return $data[0][$column];
    }

    public function log_ticket_activity($logArray, $sendRply = true)
    {
        $returnArray = array();
        $this->db->insert('ticket_activity', $logArray);
        $insert_id = $this->db->insert_id();
        $dbdata = array(
            'modified_date' => gen_db_date(date('Y-m-d')),
            'modified_time' => date('H:i:s'),
        );
        $cond = array(
            'id' => $logArray['ticket_id']
        );
        $this->db->where($cond);
        $result = $this->db->update('ticket', $dbdata); 
        return $insert_id;
    }
     

    public function genTicketActivityContents($getResult)
    {
        $genActivity = array();
        $data['category'] = getCategory(0);
        $data['organization'] = getOrganization();
        $data['contact'] = $data['users'] = getUser();
        $data['assignee'] = getUser(3);
        $data['priority'] = getPriority();
        $data['ticket_status'] = getTicketStatus();
        $chkCol = array('ticket_status', 'status', 'priority', 'assignee', 'organization', 'description', 'summary');
        $chkCol2 = array('due_date', 'due_time', 'time_spent', 'contact');
        $subChk = array('ticket_status', 'description', 'summary');
        /* echo "<pre>"; print_r($getResult); exit; */
        if (!empty($getResult) && is_array($getResult)) {
            foreach ($getResult as $key => $innerArray) {
                $attachments = array();
                if ($innerArray['db_column'] == 'due_time')
                    continue;
                if ($innerArray['db_column'] == 'attachment' || $innerArray['db_column'] == 'public' || $innerArray['activity_type'] == 1)
                    $attachments = $this->getAllActivityAttachment($innerArray['id']);
                $date_time = date('Y-m-d H:i:s', strtotime($innerArray['created_date'] . " " . $innerArray['created_time']));
                $currCol = gen_locale_col($innerArray['db_column']);
                if ($innerArray['activity_type'] == 2 && !in_array($innerArray['db_column'], $chkCol2)) {
                    if (!in_array($innerArray['db_column'], $subChk)) {
                        $col_comments = (in_array($currCol, $chkCol)) ? 'from '
                            . getValueByID($data[$currCol], $innerArray['old_value']) . ' to '
                            . getValueByID($data[$currCol], $innerArray['new_value']) : 'to '
                            . getValueByID($data[$currCol], $innerArray['new_value']);
                        $event_cmnts = $innerArray['comments'];
                    } else if ($innerArray['db_column'] == 'description') {
                        $col_comments = ' - ';
                        $event_cmnts = $innerArray['new_value'];
                    } else if ($innerArray['db_column'] == 'ticket_status') {
                        $col_comments = ' <i>from</i> ' . getValueByID($data['ticket_status'], $innerArray['old_value']) . '<i> to </i>'
                            . getValueByID($data['ticket_status'], $innerArray['new_value']);
                        $event_cmnts = '';
                    } else if ($innerArray['db_column'] == 'summary') {
                        $col_comments = ' from <i>' . $innerArray['old_value'] . '</i> to <i>'
                            . $innerArray['new_value'] . '</i>';
                        $event_cmnts = $innerArray['comments'];
                    }

                } else if ($innerArray['db_column'] == 'due_date') {
                    $col_comments = ($innerArray['new_value'] != '0000-00-00') ? "to " . gen_locale_date($innerArray['new_value']) : 'to <i>unassigned</i>';
                    $event_cmnts = $innerArray['comments'];
                } else if ($innerArray['db_column'] == 'timeSpent') {
                    $col_comments = $innerArray['new_value'];
                    $event_cmnts = $innerArray['comments'];
                } else {
                    $col_comments = "";
                    $event_cmnts = $innerArray['comments'];
                }

                $genActivity[$date_time] = array(
                    'user_name' => ($innerArray['user_id'] == 0 && $innerArray['activity_type'] == 1) ?
                        $innerArray['created_email'] : getValueByID($data['users'], $innerArray['user_id']),
                    'activity_type' => $innerArray['activity_type'],
                    'old_value' => $innerArray['old_value'],
                    'new_value' => escape_str($innerArray['new_value']),
                    'db_column' => $currCol,
                    'col_cond' => (in_array($currCol, $chkCol)) ? 'changed ' : 'set',
                    'col_comments' => $col_comments,
                    'time_ago' => time_elapsed_string(date('Y-m-d H:i:s', strtotime($innerArray['created_date'] . " " . $innerArray['created_time'])), false),
                    'time_title' => date('l', strtotime($innerArray['created_date'])) . ","
                        . date('M jS Y', strtotime($innerArray['created_date'])) . " at "
                        . date('h:i: a', strtotime($innerArray['created_time'])),
                    'comments' => $event_cmnts,
                    'attachment' => $attachments,
                );
            }
        }
        return $genActivity;
    }

    public function getAllActivityAttachment($activity_ID)
    {
        $cond = array(
            "activity_id" => $activity_ID,
            "is_deleted" => '0',
        );
        $this->db->select('*');
        $this->db->where($cond);
        $q = $this->db->get('ticket_attachment');
        $data = $q->result_array();
        return $data;
    }
}

 ?>
