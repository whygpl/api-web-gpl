<?php class Regency_model extends MY_Model {
	protected $tableName = 'regency';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id_regency';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields = array('a.id_regency' => 'ASC');
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>