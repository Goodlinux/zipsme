<?php
class Info {
	public $url_name;
	public $url;
	public $type;
	function __construct($url_name) {
		$this->url_name = strtolower($url_name);
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT * FROM tbl_links WHERE url_name = '" . $this->url_name . "' LIMIT 1";
		$result = $DbConnect->query($query);
		$row = mysqli_fetch_array($result); 
		$this->url = $row['url'];
		$this->type = $row['type'];
		mysqli_close($DbConnect);
	}

	function __get($name) {
		return $this->$name;
	}
	
	function __set($name, $value){
		$this->$name = $value;
	}		
}
?> 
