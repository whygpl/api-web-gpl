<?php class About_award_model extends MY_Model {
	protected $tableName = 'about_awards';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>
