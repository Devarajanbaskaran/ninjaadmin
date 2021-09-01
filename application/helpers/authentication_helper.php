<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      
  function admin_verify_session(){
      $CI = &get_instance();
      $user_role = $CI->session->userdata('user_role'); 
      if($user_role  != 1) {
       redirect('login');
      }
  } 
  
   ?>
