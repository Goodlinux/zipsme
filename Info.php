<?php
class Info {
	public $url_name;
	public $url;
	public $type;
	
	function __construct($url_name) {
		$this->url_name = $url_name;
		$query = "SELECT * FROM tbl_links WHERE url_name = '" . $this->url_name . "' LIMIT 1";
		$result = mysql_query($query,$GLOBALS['DB']);
		$row = mysql_fetch_assoc($result);
		$this->url = $row['url'];
		$this->type = $row['type'];
		
	}
	
	function __get($name) {
		return $this->$name;
	}
	
	function __set($name, $value){
		$this->$name = $value;
	}	
	
}
?>