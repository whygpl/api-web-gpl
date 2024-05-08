<?php class District_model extends MY_Model {
	protected $tableName = 'district';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id_district';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields = array('a.id_district' => 'ASC');
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>