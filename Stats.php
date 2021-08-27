<?php
class Stats {
	public $url_name;
	public $total_clicks;
	
	function __construct($url_name) {
		$this->url_name = strtolower($url_name);
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT COUNT(url_name) AS urlCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "'";
		$result = $DbConnect->query($query);
		$row = mysqli_fetch_array($result);
		$this->total_clicks = $row['urlCount'];	
		if (! IS_ENV_PRODUCTION) {
			echo "Stats--> url : " . $this->url_name . " " . $row['urlCount'];
		}
		mysqli_close($DbConnect);
	}
	
	function __get($name) {
		return $this->$name;
	}
	
	function __set($name, $value){
		$this->$name = $value;
	}	
	
	function showClicks() {
		if (! IS_ENV_PRODUCTION) {
			echo "Stats-->showClicks";
		}		
		
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT DATE_FORMAT(click_time,'%Y-%m') as date, COUNT(*) AS ClickCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY DATE_FORMAT(click_time,'%Y-%m') ORDER BY DATE_FORMAT(click_time,'%Y-%m') DESC";
		$result = $DbConnect->query($query);
		if (! IS_ENV_PRODUCTION) {                                                                                                                          
                        echo "Stats-->showBrowsers SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                             
                }
    		$tot=0;
		while ($row = mysqli_fetch_array($result)) {
			echo '<tr>' . "\n";
      			echo '<td class="border">' . $row['date'] . '</td>' . "\n";
      			echo '<td class="border">' . $row['ClickCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot + $row['ClickCount'];
		} 
		echo '<tr>' . "\n";
      		echo '<td class="border"><strong>Total</td>' . "\n";
      		echo '<td class="border">' . $tot . '</td>' . "\n";
    		echo '</tr>' . "\n";
		mysqli_close($DbConnect);
	}

	function showOs() {
		if (! IS_ENV_PRODUCTION) {
			echo "Stats-->showOs";
		}
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT os, COUNT(*) AS ClickCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY os ORDER BY os DESC";
		$result = $DbConnect->query($query);
		if (! IS_ENV_PRODUCTION) {                                                                                                                          
                        echo "Stats-->showOs SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                             
                }
    		$tot=0;
		while ($row = mysqli_fetch_array($result)) {
			echo '<tr>' . "\n";
			echo '<td class="border">' . $row['os'] . '</td>' . "\n";
			echo '<td class="border">' . $row['ClickCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot+$row['ClickCount'];
		}
		echo '<tr>' . "\n";
    		echo '<td class="border"><strong>Total</td>' . "\n";
    		echo '<td class="border">' . $tot . '</td>' . "\n";
    		echo '</tr>' . "\n";
		mysqli_close($DbConnect);
	}

	function showBrowsers() {
		if (! IS_ENV_PRODUCTION) {
			echo "Stats-->showBrowser";
		}
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT browser, COUNT(*) AS ClickCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY browser ORDER BY browser DESC";
		$result = $DbConnect->query($query);
		if (! IS_ENV_PRODUCTION) {                                                                                                                          
                        echo "Stats-->showBrowsers SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                             
                }
    		$tot=0;
		while ($row = mysqli_fetch_array($result)) {
			echo '<tr>' . "\n";
			echo '<td class="border">' . $row['browser'] . '</td>' . "\n";
			echo '<td class="border">' . $row['ClickCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot+$row['ClickCount'];
		}
		echo '<tr>' . "\n";
    		echo '<td class="border"><strong>Total</td>' . "\n";
    		echo '<td class="border">' . $tot . '</td>' . "\n";
    		echo '</tr>' . "\n";
		mysqli_close($DbConnect);	
	}
	
	function showOsBrowsers() {
		if (! IS_ENV_PRODUCTION) {
			echo "Stats-->showBrowser";
		}
		$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "SELECT os, browser, COUNT(*) AS ClickCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY os, browser ORDER BY os, browser DESC";
		$result = $DbConnect->query($query);
		if (! IS_ENV_PRODUCTION) {                                                                                                                          
                        echo "Stats-->showBrowsers SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                             
                }
    		$tot=0;
		while ($row = mysqli_fetch_array($result)) {
			echo '<tr>' . "\n";
			echo '<td class="border">' . $row['os'] . " : " . $row['browser'] . '</td>' . "\n";
			echo '<td class="border">' . $row['ClickCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot+$row['ClickCount'];
		}
		echo '<tr>' . "\n";
    		echo '<td class="border"><strong>Total</td>' . "\n";
    		echo '<td class="border">' . $tot . '</td>' . "\n";
    		echo '</tr>' . "\n";
		mysqli_close($DbConnect);	
	}
	
}
?>
