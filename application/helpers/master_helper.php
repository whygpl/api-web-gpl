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

if ( !function_exists('get_data_counter') ) {  
	function get_data_counter($data = array()) { 
		$ci = & get_instance();		
		$model = 'Datahelper_model';
		$ci->load->model($model);
				
		if (count($data) == 0) $data = $this->input->post();
		$rData = array();
		
		if (count($data) > 0) {
			$ci->$model->set_to_counter();
			$rData = $ci->$model->read(0, $data);
		}
		return $rData;
	}
}

if ( !function_exists('get_data_master') ) {  //b => filter
	function get_data_master($m = 'sdm/golongan', $f = 'read_master', $a = null, $b = null) { 
		$ci = & get_instance();	
		$temp = explode("/", $m);
		$model = $temp[count($temp) - 1];
		$ci->load->model($m.'_model', $model);
		unset ($_POST);
		if ($b != NULL && is_array($b) && count($b) > 0) $ci->$model->set_variable($b);
		
		$rData = $ci->$model->$f($a);
		
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

if ( !function_exists('generate_dinamic_column') ) {  
	function generate_dinamic_column($data, $fn = 'COLUMN_CREATE', $f = 'json_katalog') { 
		$add = '';
		if ($fn == 'COLUMN_ADD') $add = $f.", ";
		
		$json_katalog = array();
		foreach ($data as $f => $d) {
			array_push($json_katalog, "'{$f}', '{$d}'");			
		}	
		$data_json =  "{$fn}({$add} ". IMPLODE(",", $json_katalog) ." )";
		return $data_json;
	}
}

function cara_pulang($no){
	switch ($no) {
		case '1':
			return 'Atas Persetujuan Dokter';
			break;
		case '2':
			return 'Dirujuk';
			break;
		case '3':
			return 'Atas Permintaan Sendiri';
			break;
		case '4':
			return 'Meninggal';
			break;
		case '5':
			return 'Lain - Lain';
			break;
		default:
			return 'Lain - Lain';
			break;
	}
}



?>
