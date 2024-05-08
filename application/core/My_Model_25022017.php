<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model 
{
    protected $tableName;
    protected $itableName = null;
    protected $selectFields = 'a.*';
    protected $joinFields;
    protected $orderFields;
    protected $groupFields = array();
    protected $limit = NULL;
    protected $offset = 0;
    
    protected $name;
    protected $primaryKey;
    protected $multiPKSeparator = '.';
    protected $resultMode = 'array'; /* [array, object] */
    public $last_query = '';
    public $last_count_query = '';
    public $whereFields;
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

    public function set_variable($var = 'whereFields', $val = null) {
        if (is_array($var)) {
            if (count($var) > 0) {
                foreach($var as $k => $v) $this->{$k} = $v; 
            }
        } else $this->{$var} = $val;
    }

    public function get_variable($var = 'whereFields') {
        if (isset($this->{$var})) return $this->{$var};
    }

    public function create($data = array()) {
        if (count($data) == 0) {
            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->input->post();
        }
        if( count($data) > 0):
            if ($this->db->insert($this->tableName, $data)) 
                return $this->db->insert_id();
        endif;
        return false;
    }
    
    public function create_batch($data = array()) {
        if( count($data) > 0 ):
            $this->db->trans_start(); 
            //set lakukan penghapusan data terlebih dahulu
            if (!empty($this->whereFields) && count($this->whereFields) > 0) {
                foreach ($this->whereFields as $key => $val) 
                    $this->db->where($key, $val);
            }
            
            if ($this->db->delete($this->tableName)) {
                // lakukan insert batch
                $this->db->insert_batch($this->tableName, $data);
                if ($this->db->affected_rows() > 0) {
                    $this->db->trans_complete();
                    return true;
                }
            }
            $this->db->trans_rollback();
        endif;
        return false;
    }

    public function insert_batch($data = array()) {
        if( count($data) > 0 ):
            $this->db->insert_batch($this->tableName, $data);
            return $this->db->affected_rows();
        endif;
        return false;
    }
    
    /* 
    public function read ($p = 0) {	
        $this->db->select($this->selectFields);
        $this->db->from("{$this->tableName} as a");
        
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
                        $this->db->or_like('a.'.$k, $v);
                    }
                } else if ($key == 'not_equal' && count($val_arr) > 0) {
                    foreach ($val_arr as $k => $v){
                        $this->db->where("a.{$k} != ", $v);
                    }
                } else {
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
     */
     
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
                } else if ($key == 'not_in' && count($val_arr) > 0) {
                    foreach ($val_arr as $k => $v){
                        if (isset($fields[$k])) 
                            $this->db->where_not_in("a.{$k}", $v);
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
        
    public function read_master ($p = 0) {
        $this->db->select($this->selectFields);
        $this->db->from("{$this->tableName} as a");
        
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
    
    public function update($p = 0, $data = array()) {
        if( count($this->input->post()) > 0 || count($data) > 0):
            $sts = false;
            //set filter data
            if (count($this->whereFields) > 0) {
                foreach ($this->whereFields as $key => $val) 
                    $this->db->where($key, $val);
                $sts = true;
            }
            if ($p > 0) {
                $this->db->where($this->primaryKey, $p);
                $sts = true;
            }
            
            if (count($data) == 0) {
                $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);			
                if (!empty($this->primaryKey) && !is_null($this->input->post($this->primaryKey)))
                    $this->db->where($this->primaryKey, $this->input->post($this->primaryKey));
                
                $dtPost = $this->input->post();
                $fields = $this->get_field_list();
                foreach($dtPost as $k => $v) {
                    if (isset($fields[$k])) $this->db->set($k , $v);	
                }
                
            } else {
                $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);			
                if ( $p == 0 && !empty($this->primaryKey) && !is_null($data[$this->primaryKey])) {
                    $this->db->where( $this->primaryKey, $data[$this->primaryKey]);
                    $sts = true;
                }
                if ($sts) $this->db->set($data);
            }
            
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

    public function remove($id = 0) {
        $sts = false;
        
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

    public function delete_batch($id = array()) {
        $sts = false;
        
        if (!empty($this->primaryKey) && count($id)) {
            $sts = true;
            $this->db->where_in($this->primaryKey, $id);
        }
        
        if($sts) return $this->db->delete($this->tableName);	
        else return false;
    }

    public function replace($data = array()) {
        if (count($data) == 0) {
            $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = $this->input->post();
        }
        if (count($data) > 0) {
            return $this->db->insert_or_update($this->tableName, $data);
        }
        return false;
    }
    
    public function replace_batch($data = array()) {
        if (count($data) > 0) {
            return $this->db->insert_or_update_batch($this->tableName, $data);
        }
        return false;
    }
    
    public function merge($insert_data = array(), $delete_data = array()) {
        $affected_rows = 0;
        
        if (count($delete_data))
            $affected_rows += $this->delete_batch($delete_data);
        
        if (count($insert_data))
            $affected_rows += $this->replace_batch($insert_data);
        
        return $affected_rows;
    }

    public function find($compact_data = null) {
        extract($compact_data);
        
        if (isset($fields)) {
            $this->db->select($fields);
        }
        
        if (isset($filters)) {
            $this->db->where($filters);
        }
        
        if (isset($joins)) {
            foreach ($joins as $idx => $join) {
                $this->db->join($join);
            }
        }
        
        if (isset($orders)) {
            if (is_array($orders[0])) {
                foreach ($orders as $order) {
                    $this->db->order_by($order[0], @$order[1]);
                }
            } else {
                $this->db->order_by($orders[0], @$orders[1]);
            }
        }
        
        if (isset($limit)) {
            if (isset($offset)) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        $this->db->from($this->tableName);
        
        if ($this->resultMode == 'array') {
            $result = $this->db->get()->result_array();
        } else if ($this->resultMode == 'object') {
            $result = $this->db->get()->result();
        }
        
        return $result;
    }

    public function find_where($filters) {
        return $this->find(array('filters' => $filters));
    }

    public function find_one_where($filters) {
        $search = $this->find_where($filters);
        if (count($search)) {
            return $search[0];
        } else {
            return false;
        }
    }
    
    public function query($sql = NULL, $act = 'select') {	
        if ($sql != NULL && $act == 'select')
            return $this->db->query($sql)->result();
        else if ($sql != NULL && $act != 'select')
            return $this->db->query($sql);
        else return array();
    }


    
}
