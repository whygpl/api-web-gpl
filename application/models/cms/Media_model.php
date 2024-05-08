<?php class Media_model extends MY_Model {
	protected $tableName = 'media';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();

	function updateStatusByType($type,$status){
		$this->db->set(array("status" => $status));
		$this->db->where("type",$type);
		return $this->db->update($this->tableName);
	}

	function DeleteByType($type,$status){
		$this->db->where("type",$type);
		$this->db->where("status",$status);
		return $this->db->delete($this->tableName);
	}
}?>
