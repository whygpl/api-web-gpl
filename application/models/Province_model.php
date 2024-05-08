<?php class Province_model extends MY_Model {
	protected $tableName = 'province';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id_province';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields = array('a.id_province' => 'ASC');
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>