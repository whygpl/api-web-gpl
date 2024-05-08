<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends MY_Model
{
    protected $tableName = 'users';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();

    /**
     * Use Registration
     * @param: {array} User Data
     */
    public function insert_user(array $data) {
        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
    }
    public function update_user($id,array $data) {
        $this->db->where($this->primaryKey, $id);
        $this->db->update($this->tableName, $data);
        if ($this->db->trans_status() === false) {
            return $this->db->error();
        } else {
            return $id;
        }
    }
    public function delete_user($id) {
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->tableName);
        $this->db->affected_rows();
        if ($this->db->trans_status() === false) {
            return $this->db->error();
        } else {
            return $id;
        }
    }
}
