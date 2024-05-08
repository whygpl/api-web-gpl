<?php class Email_server_model extends MY_Model {
	protected $tableName = 'smtpserver';
	protected $resultMode = 'object';	  
	public $primaryKey = 'status';	
	public $whereFields;
	public $limit;
	public $offset;
	protected $orderFields;
	protected $selectFields = "a.*,";
	protected $joinFields = array();
}?>
