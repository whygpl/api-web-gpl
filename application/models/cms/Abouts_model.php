<?php class Abouts_model extends MY_Model {
	protected $tableName = 'abouts';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>