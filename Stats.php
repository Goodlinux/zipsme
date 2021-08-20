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
		$this->url_name = strtolower($url_name);
		$DbConnect = sqlConnect();
		$query = "SELECT COUNT(url_name) AS urlCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = $DbConnect->query($query);
		$row = mysqli_fetch_array($result);
		$this->total_clicks = $row['urlCount'];	
		$this->calcBrowsers();
		$this->calcOS();
		mysqli_close($DbConnect);
	}
	
	function __get($name) {
		return $this->$name;
	}
	
	function __set($name, $value){
		$this->$name = $value;
	}	
	
	function showClicks() {
		$DbConnect = sqlConnect();
		$query = "SELECT click_time, COUNT(url_name) AS monthCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY EXTRACT(MONTH FROM click_time) ORDER BY click_time DESC";
		$result = $DbConnect->query($query);
		while ($row = mysqli_fetch_array($result));
		{
			$month = strtotime($row['click_time']);
			echo '<tr>' . "\n";
			echo '<td class="border">' . date('F Y', $month) . '</td>' . "\n";
			echo '<td class="border">' . $row['monthCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
		} 
		mysqli_close($DbConnect);
	}

	function showReferrers() {
		$DbConnect = sqlConnect();
		$query = "SELECT referrer, COUNT(referrer) AS refCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY referrer ORDER BY refCount DESC";
		$result = $DbConnect->query($query);
		while ($row = mysqli_fetch_array($result));
		{
			$referrer = str_replace('http://', '',$row['referrer']);
			$referrer = str_replace('www.','',$referrer);
			if (strlen($referrer)==0) {
				$referrer = 'Direct/Unavailable';
			}
			echo '<tr>' . "\n";
			echo '<td class="border">' . prepOutputText($referrer) . '</td>' . "\n";
			echo '<td class="border">' . $row['refCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
		}
		mysqli_close($DbConnect);
	}

	function calcBrowsers() {
		$DbConnect = sqlConnect();
		$query = "SELECT user_agent FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = $DbConnect->query($query);
		$row = mysqli_fetch_array($result);
		
		while ($row = mysqli_fetch_array($result))
		{
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
		}	
		mysqli_close($DbConnect);	
	}

	function calcOS() {
		$DbConnect = sqlConnect();
		$query = "SELECT user_agent FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = $DbConnect->query($query);
		while ($row = mysqli_fetch_array($result));
		{
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
		}
		mysqli_close($DbConnect);
	}	
}
?>
