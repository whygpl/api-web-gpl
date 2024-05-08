<?php

// GANTI 1 Golongan_model -> nama model 
class Ketersedian_kartu_model extends MY_Model {
	
	protected $tableName = 'fa_trx_ketersediaan_kartu';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id_trx_ketersediaan_kartu';	
	protected $selectFields = "a.*, aa.kd_mst_katalog, aa.nm_mst_katalog, c.kd_mst_kemasan kd_kemasan_kecil";
	protected $joinFields = array(		
		array('fa_mst_depo d', "a.id_mst_depo=d.id_mst_depo", "INNER"),
		array('fa_mst_katalog aa', "a.id_mst_katalog=aa.id_mst_katalog", "INNER"),
		array('fa_mst_kemasan c', "aa.id_kemasan_kecil=c.id_mst_kemasan", "LEFT"),
	);
    protected $orderFields = array(
		// 'tgl_trx_ketersediaan' => 'ASC',
		'id_trx_ketersediaan_kartu' => 'ASC',
	);
	
	public function read ($p = 0, $post = true) {	
		
		$this->db->select($this->selectFields);
		$this->db->from("{$this->tableName} as a");
		$fields = $this->get_field_list();
		
		// set join fields
		if(!empty($this->joinFields) && count($this->joinFields) > 0) {
			foreach($this->joinFields as $j) {
				if (!isset($j[2]))
					$this->db->join($j[0], $j[1]);
				else $this->db->join($j[0], $j[1], $j[2]);
			}
		}
		
		//set filter data
		if (!empty($this->whereFields) && count($this->whereFields) > 0) {
			foreach ($this->whereFields as $key => $val) {
				if ( $val != '' && $val != null && $val != 'undefined') $this->db->where($key, $val);
			}
		}				
		if (!empty($this->primaryKey) && $p != 0)
			$this->db->where('a.'.$this->primaryKey, $p);
		
		// set filter from postdata
		$dtPost = $this->input->post();
        if ( $post && count($dtPost) > 0 ) :
            foreach ($this->input->post() as $key => $val_arr) :
				if ($key == 'like' && count($val_arr) > 0) {
					foreach ($val_arr as $k => $v){
						if (isset($fields[$k]) && $v != '')
							$this->db->like('a.'.$k, $v);
					}
				} else if ($key == 'or_like' && count($val_arr) > 0) {
					foreach ($val_arr as $k => $v){
						if (isset($fields[$k]) && $v != '')
							$this->db->or_like('a.'.$k, $v);
					}
				} else if ($key == 'not_equal' && count($val_arr) > 0) {
					foreach ($val_arr as $k => $v){
						if (isset($fields[$k]) && $v != '')
							$this->db->where("a.{$k} != ", $v);
					}
				} else if ($key == 'not_in' && count($val_arr) > 0) {
					foreach ($val_arr as $k => $v){
						if (isset($fields[$k]) && $v != '') 
							$this->db->where_not_in("a.{$k}", $v);
					}
				} else {
					if (isset($fields[$key]) && $val_arr != '')
						$this->db->where('a.'.$key, $val_arr);
				}                
            endforeach;
			
			if ( isset($dtPost['date_start']) && $dtPost['date_start'] != '__-__-____' ) 
				$this->db->where( 'a.tgl_trx_ketersediaan >= ', date('Y-m-d', strtotime($dtPost['date_start'])) );
			if ( isset($dtPost['date_end']) && $dtPost['date_end'] != '__-__-____' ) 
				$this->db->where( 'a.tgl_trx_ketersediaan <= ', date('Y-m-d', strtotime($dtPost['date_end'])) );
        endif;
		
		if (count($this->groupFields) > 0)
			$this->db->group_by($this->groupFields); 
			
		// set order fields
		if(!empty($this->orderFields) && count($this->orderFields) > 0) {
			foreach ($this->orderFields as $key => $val) 
				$this->db->order_by($key, $val);
		} else if (!empty($this->primaryKey))
			$this->db->order_by($this->primaryKey, "DESC"); 
			
		//set limit and offset
		$this->db->limit($this->limit); 
		$this->db->offset($this->offset); 
		
		// if (count($this->joinFields) > 0){
			// $result = $this->db->get();
			// var_dump(count($result));
			// var_dump($this->db->last_query());
			// die();
		// }
		return ($this->db->get()->result());
	}
    	
}
?>