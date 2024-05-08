<?php class Product_group_model extends MY_Model {
	protected $tableName = 'product_groups';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>
