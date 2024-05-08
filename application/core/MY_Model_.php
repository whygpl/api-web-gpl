<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	protected $tableName;
	protected $selectFields = 'a.*';
	protected $joinFields;
	protected $whereFields;
	protected $groupFields = array();
	protected $orderFields;
	protected $limit = NULL;
	protected $offset = 0;
	
	protected $name;
	protected $primaryKey;
	protected $multiPKSeparator = '.';
    protected $resultMode = 'array'; /* [array, object] */
    public $last_query = '';
    public $last_count_query = '';
    protected $fieldsList = array();

	function __construct() {
		parent::__construct();
		$this->name = get_class($this);
		if (empty($this->tableName)) {
			$this->tableName = $this->name;
			$this->primaryKey = $this->db->query("SHOW COLUMNS FROM $this->tableName")->row()->Field;			
		}
		$this->load->database();
	}

	protected function _parse($rResult, $aColumns, $action, $output) {

		if ($rResult) {
			foreach($rResult as $aRow) {
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
					/* General output */
					$field = $this->field_alias($aColumns[$i]);
					$row[] = $this->isDateFormat($aRow->{$field});
				}

				if ($action) {
					$row[] = sprintf($action, $aRow->id);
				}

				$row["DT_RowId"] = trim($aRow->id);
				$output['aaData'][] = $row;
			}
		} else {
			$output['aaData'] = "";
		}

		return json_encode( $output );

	}

	protected function isDateFormat($data, $format="d-m-Y") {
		if (trim($data) == '0000-00-00' || trim($data) == '0000-00-00 00:00' || trim($data) == '0000-00-00 00:00:00') return;
		if (@date('Y-m-d', @strtotime($data)) == trim($data) || @date('Y-m-d H:i:s', @strtotime($data)) == trim($data))
			return @date($format, @strtotime($data));
		else
			return $data;
	}
   
   	protected function field_alias($field) {
		$split = preg_split('/\s[aAsS]+\s|\s|\./', $field);
		return trim($split[count($split) - 1]);
	}

   	protected function field_name($field) {
		$split = preg_split('/\s[aAsS]+\s|\s/', $field);
		return trim($split[0]);
	}

   	public function get_field_list($tableName = NULL) {
		if ($tableName == NULL) $tableName = $this->tableName;
		foreach ($this->db->list_fields($tableName) as $val) {
			$this->fieldsList[$val] = '';
		} 
		return $this->fieldsList;
	}

	public function create() {
		if( count($this->input->post()) > 0):
			$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);			
			return $this->db->insert($this->tableName, $this->input->post());
		endif;
		return false;
	}
	
	public function read ($p = 0) {	
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
			foreach ($this->whereFields as $key => $val) 
				$this->db->where($key, $val);
		}				
		if (!empty($this->primaryKey) && $p > 0)
			$this->db->where('a.'.$this->primaryKey, $p);
			
		// set filter from postdata
        if (count($this->input->post()) > 0) :
            foreach ($this->input->post() as $key => $val_arr) :
				if ($key == 'or_like' && count($val_arr) > 0) {
					foreach ($val_arr as $k => $v){
						if (isset($fields[$k]))
							$this->db->or_like('a.'.$k, $v);
					}
				} else if ($key == 'not_equal' && count($val_arr) > 0) {
					foreach ($val_arr as $k => $v){
						if (isset($fields[$k]))
							$this->db->where("a.{$k} != ", $v);
					}
				} else {
					if (isset($fields[$key]))
						$this->db->where('a.'.$key, $val_arr);
				}                
            endforeach;
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
		
		return ($this->db->get()->result());
	}
    
	public function update($p = 0) {
		if( count($this->input->post()) > 0):
			$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			
			//set filter data
			if (count($this->whereFields) > 0) {
				foreach ($this->whereFields as $key => $val) 
					$this->db->where($key, $val);
			}				
			if (!empty($this->primaryKey) && !is_null($this->input->post($this->primaryKey)))
				$this->db->where($this->primaryKey, $this->input->post($this->primaryKey));
					
			if ($p > 0)
				$this->db->where($this->primaryKey, $p);
			
			$this->db->set($this->input->post());
			return $this->db->update($this->tableName); 
		endif;
		
		return false;
	}
	
	public function delete($id = 0) {	
		$sts = false;
		
		// set filter from postdata
        if (count($this->input->post()) > 0) :
			$sts = true;
            foreach ($this->input->post() as $key => $val)
				$this->db->where($key, $val);
        endif;
		//set filter data from fieldswhere
		if (count($this->whereFields) > 0) {
			$sts = true;
			foreach ($this->whereFields as $key => $val) 
				$this->db->where($key, $val);
		}
		if (!empty($this->primaryKey) && $id != 0){
			$sts = true;
			$this->db->where($this->primaryKey, $id);
		}
		
		if($sts) return $this->db->delete($this->tableName);	
		else return false;
	}
	
	public function query($sql = NULL) {	
		if ($sql != NULL)
			return $this->db->query($sql)->result();
		else return array();
	}
	
}
