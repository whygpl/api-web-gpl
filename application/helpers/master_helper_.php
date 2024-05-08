<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('get_data_helper') ) {  
	function get_data_helper($v = 'status_perkawinan', $f = array()) { 
		$ci = & get_instance();		
		$model = 'Datahelper_model';
		
		$ci->load->model($model);
		$f['nm_variable'] = $v;		
		$rData = $ci->$model->read(0, $f);
		
		return $rData;
	}
}

if ( !function_exists('get_data_master') ) {  
	function get_data_master($m = 'sdm/golongan', $f = array()) { 
		$ci = & get_instance();		
		$ci->load->model($m.'_model', 'model');
		
		$rData = $ci->model->read();
		
		return $rData;
	}
}

if ( !function_exists('get_data_bulan') ) {  
	function get_data_bulan($n = 0) { 
		$bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	
		$rsData = array();
		for ($i = 1; $i <= 12; $i++):
			$rsData[$i] = array(
				'nama' => $bulan[$i-1],
				'name' => date('F', mktime(0, 0, 0, $i)),
				'nm' => date('M', mktime(0, 0, 0, $i)),
			);
		endfor;
		
		if ($n == 0) return $rsData;
		else return $rsData[$n]; 
	}
}

?>
