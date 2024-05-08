<?php class Product_model extends MY_Model {
	protected $tableName = 'products';
	protected $resultMode = 'object';	  
	public $primaryKey = 'id';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,b.product_type_id";
	protected $joinFields = array(
		array('product_categorys b', 'a.product_category_id = b.id', 'LEFT'),
	);
}?>
