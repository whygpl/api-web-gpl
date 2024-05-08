<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_bulan($index, $type = 'long')
{
    $long_bulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $short_bulan = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
    
    if ($index > 0 && $index < 13) {
        if ($type == 'long') {
            return $long_bulan[$index];
        } else if ($type == 'short') {
            return $short_bulan[$index];
        }
    } else {
        return '';
    }
}