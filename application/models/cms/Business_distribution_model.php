<?php class Business_distribution_model extends MY_Model {
	protected $tableName = 'business_distribution_network';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();
	
	public function checkingLogLat($lat,$long){
		$this->db->select("*");
		$this->db->where("latitude",$lat);
		$this->db->where("longitude",$long);
		$this->db->from($this->tableName);
		$result = $this->db->get()->result_array();
		return $result;
	}
}?>
