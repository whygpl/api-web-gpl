<?php class Village_model extends MY_Model {
	protected $tableName = 'village';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id_village';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields = array('a.id_village' => 'ASC');
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>