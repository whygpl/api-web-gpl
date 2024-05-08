<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function convert_datetime($date, $to, $from = null, $diff = null)
{
    $format = array(
        'user_time' => array('H:i:s', '/^\d{2}\-\d{2}\-\d{4}$/'),
        'user_date' => array('d-m-Y', '/^\d{2}\-\d{2}\-\d{4}$/'),
        'user_date_word' => array('d M Y', '/^\d{2} \w{1,} \d{4}$/'),
        'user_datetime_long' => array('d-m-Y H:i:s', '/^\d{2}\-\d{2}\-\d{4} \d{2}\:\d{2}\:\d{2}$/'),
        'user_datetime_partial' => array('d-m-Y H:i', '/^\d{2}\-\d{2}\-\d{4} \d{2}\:\d{2}$/'),
        'user_datetime_word' => array('d M Y H:i:s', '/^\d{2} \w{1,} \d{4} \d{2}\:\d{2}$/'),
        'system_date' => array('Y-m-d', '/^\d{4}\-\d{2}\-\d{2}$/'),
        'system_datetime' => array('Y-m-d H:i:s', '/^\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}\:\d{2}$/'),
    );
    
    $from_format = '';
    
    if (empty($from)) {
        foreach ($format as $key => $row) {
            if (preg_match($row[1], $date)) {
                $from_format = $key;
            }
        }
    } else {
        $from_format = $from;
    }
    
    $to_format = $to;
    
    $originalDate = DateTime::createFromFormat($format[$from_format][0], $date);
    
    $newDate = $originalDate;
    
    if (isset($diff)) {
        if (preg_match('/^\-[A-Za-z0-9]*$/', $diff)) {
            $diff = substr($diff, 1);
            $newDate->sub(new DateInterval($diff));
        } else {
            $newDate->add(new DateInterval($diff));
        }
    }
    
    $retval = $newDate->format($format[$to_format][0]);
    
    return $retval;
}