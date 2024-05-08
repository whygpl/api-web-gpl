<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function convert_money($money, $to, $from = null)
{
    $format = array(
        'rupiah' => array(array('thousand_separator' => '.', 'decimal_separator' => ','), '/^(?:(\d{1,3}))+(\,\d{1,}?)?$/'),
        'rupiah' => array(array('thousand_separator' => '.', 'decimal_separator' => ','), '/^(?:(\d{1,3}))+(\,\d{1,}?)?$/'),
        'normal' => array('d-m-Y H:i:s', '/^\d{}$/'),
    );
    
    if (!isset($from)) {
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
    $newDate = $originalDate->format($format[$to_format][0]);
    
    return $newDate;
}