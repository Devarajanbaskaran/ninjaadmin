<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
// --------------------------------------
//		 COMMON CONSTANTS	 |
//---------------------------------------
define("DATE_FORMAT", "m/d/Y");
define("DATE_TIME_FORMAT", "m/d/Y h:i:s a");
    
    
// --------------------------------------
//		 COMMON FUNCTIONALITIES	 |
//---------------------------------------
    
 
if ( ! function_exists('getValueById')) {
    function getValueById($values, $default, $getId=false) {
       $fieldValue = '';
       if($getId){
           foreach($values as $choice) {
                if (strpos($choice['text'], $default) !== false) {
                   $fieldValue = $choice['id'];
                }
            }
       }
       else if (!$getId && is_array($values) > 0) {
            foreach ($values as $choice) if (isset($choice['id'])) {
                if (is_array($default)) { 
                  if (in_array($choice['id'], $default)) $fieldValue .= $choice['text'];
                } else {
                      if ($default == $choice['id']) $fieldValue .= $choice['text'];
                    }
                }
            }
        return $fieldValue;
    }
}
    
if ( ! function_exists('getPriority')) {
    function getPriority() {
        $priorityArray[] = array("id" => "", "text" => "Select");
        $priorityArray[] = array("id" => 1, "text" => "Low");
        $priorityArray[] = array("id" => 2, "text" => "Medium");
        $priorityArray[] = array("id" => 3, "text" => "High");
    return $priorityArray;
    }
}
    
 
    
if (!function_exists('getTicketStatus')) { 
    function getTicketStatus() {
        $returnArray = array();
        $returnArray[] = array(
            "id"        => 1,
            "text"      => 'open',
        );
        $returnArray[] = array(
            "id"        => 2,
            "text"      => 'waiting',
        );
        $returnArray[] = array(
            "id"        => 3,
            "text"      => 'closed',
        );
        $returnArray[] = array(
            "id"        => 4,
            "text"      => 'unassigned',
        );
            
        return $returnArray;
    }
}

 
 if ( ! function_exists('gen_db_date')) {        
    function gen_db_date($raw_date = '', $separator = '/') {
        global $messageStack;
        if (!$raw_date) return '';
        // handles periods (.), dashes (-), and slashes (/) as date separators
        $error = false;
        $second_separator = $separator;
        if (strpos(DATE_FORMAT, '.') !== false) $separator = '.';
        if (strpos(DATE_FORMAT, '-') !== false) $separator = '-';
        $date_vals = explode($separator, DATE_FORMAT);
        if (strpos($raw_date, '.') !== false) $second_separator = '.';
        if (strpos($raw_date, '-') !== false) $second_separator = '-';
        $parts     = explode($second_separator, $raw_date);
        foreach ($date_vals as $key => $position) {
          switch ($position) {
            case 'Y': $year  = substr('20' . $parts[$key], -4, 4); break;
            case 'm': $month = substr('0'  . $parts[$key], -2, 2); break;
            case 'd': $day   = substr('0'  . $parts[$key], -2, 2); break;
          }
        }
        if ($month < 1    || $month > 12)   $error = true;
        if ($day   < 1    || $day   > 31)   $error = true;
        if ($year  < 1900 || $year  > 2099) $error = true;
        if ($error) {
          return date('Y-m-d');
        }
        return $year . '-' . $month . '-' . $day;
      }
}
    
if ( ! function_exists('gen_locale_date')) {
    function gen_locale_date($raw_date, $long = false) { // from db to display format
        if ($raw_date == '0000-00-00' || $raw_date == '0000-00-00 00:00:00' || !$raw_date) return '';
            global $messageStack;
            $error  = false;
        $year   = substr($raw_date,  0, 4);
        $month  = substr($raw_date,  5, 2);
        $day    = substr($raw_date,  8, 2);
        $hour   = $long ? substr($raw_date, 11, 2) : 0;
        $minute = $long ? substr($raw_date, 14, 2) : 0;
        $second = $long ? substr($raw_date, 17, 2) : 0;
            if ($month < 1   || $month > 12)  $error = true;
            if ($day < 1     || $day > 31)    $error = true;
            if ($year < 1900 || $year > 2099) $error = true;
            if ($error) {
              $date_time = time();
            } else {
              $date_time = mktime($hour, $minute, $second, $month, $day, $year);
            }
            $format = $long ? DATE_TIME_FORMAT : DATE_FORMAT;
        return date($format, $date_time);
    }
}

    
if ( ! function_exists('getPriority')) {
    function getPriority() {
        $priorityArray[] = array("id" => "", "text" => "Select");
        $priorityArray[] = array("id" => 1, "text" => "Low");
        $priorityArray[] = array("id" => 2, "text" => "Medium");
        $priorityArray[] = array("id" => 3, "text" => "High");
    return $priorityArray;
    }
}