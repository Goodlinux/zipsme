<?php
class Stats {
	public $url_name;
	public $total_clicks;
	public $b_opera = 0;
	public $b_webkit = 0;
	public $b_ie = 0;
	public $b_firefox = 0;
	public $b_none = 0;
	public $o_linux = 0;
	public $o_mac = 0;
	public $o_win = 0;
	public $o_none = 0;
	
	function __construct($url_name) {
		$this->url_name = $url_name;
		$query = "SELECT COUNT(url_name) AS urlCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = mysql_query($query,$GLOBALS['DB']);
		$row = mysql_fetch_assoc($result);
		$this->total_clicks = $row['urlCount'];	
		$this->calcBrowsers();
		$this->calcOS();
	}
	
	function __get($name) {
		return $this->$name;
	}
	
	function __set($name, $value){
		$this->$name = $value;
	}	
	
	function showClicks() {
		$query = "SELECT click_time, COUNT(url_name) AS monthCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY EXTRACT(MONTH FROM click_time) ORDER BY click_time DESC";
		$result = mysql_query($query,$GLOBALS['DB']);
		$row = mysql_fetch_assoc($result);
		do {
			$month = strtotime($row['click_time']);
			echo '<tr>' . "\n";
			echo '<td class="border">' . date('F Y', $month) . '</td>' . "\n";
			echo '<td class="border">' . $row['monthCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
		} while ($row = mysql_fetch_assoc($result));
	}
	
	function showReferrers() {
		$query = "SELECT referrer, COUNT(referrer) AS refCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY referrer ORDER BY refCount DESC";
		$result = mysql_query($query,$GLOBALS['DB']);
		$row = mysql_fetch_assoc($result);
		do {
			$referrer = str_replace('http://', '',$row['referrer']);
			$referrer = str_replace('www.','',$referrer);
			if (strlen($referrer)==0) {
				$referrer = 'Direct/Unavailable';
			}
			echo '<tr>' . "\n";
			echo '<td class="border">' . prepOutputText($referrer) . '</td>' . "\n";
			echo '<td class="border">' . $row['refCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
		} while ($row = mysql_fetch_assoc($result));
	}
	
	function calcBrowsers() {
		$query = "SELECT user_agent FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = mysql_query($query,$GLOBALS['DB']);
		$row = mysql_fetch_assoc($result);
		
		do {
			$userAgent = strtolower($row['user_agent']);       
			if (preg_match('/opera/', $userAgent)) {
				$this->b_opera++;
			} else if (preg_match('/webkit/', $userAgent)) {
				$this->b_webkit++;
			} else if (preg_match('/msie/', $userAgent)) {
				$this->b_ie++;
			} else if (preg_match('/mozilla/', $userAgent) && !preg_match('/compatible/', $userAgent)) {
				$this->b_firefox++;
			} else {
				$this->b_none++;
			}
		} while ($row = mysql_fetch_assoc($result));		
	}
	
	function calcOS() {
		$query = "SELECT user_agent FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = mysql_query($query,$GLOBALS['DB']);
		$row = mysql_fetch_assoc($result);
		
		do {
			$userAgent = strtolower($row['user_agent']);       
			if (preg_match('/linux/', $userAgent)) {
				$this->o_linux++;
			} else if (preg_match('/macintosh|mac os x/', $userAgent)) {
				$this->o_mac++;
			} else if (preg_match('/windows|win32/', $userAgent)) {
				$this->o_win++;
			} else {
				$this->o_none++;
			}
		} while ($row = mysql_fetch_assoc($result));		
	}		
}
?>