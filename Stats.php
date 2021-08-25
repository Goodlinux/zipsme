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
		//$query = "SELECT click_time, COUNT(url_name) AS monthCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' ROUP BY EXTRACT(MONTH FROM click_time) ORDER BY click_time DESC";
		$query = "SELECT DATE_FORMAT(click_time,'%Y-%m') as date, COUNT(url_name) AS monthCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY EXTRACT(YEAR_MONTH FROM click_time) ORDER BY click_time DESC";
    		$result = $DbConnect->query($query);
		if (! IS_ENV_PRODUCTION) {                                                                                                                                                                  
                        echo "Stats-->showClicks SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                                                                     
                }		
		$tot = 0;
		while ($row = mysqli_fetch_array($result));
		{
			echo '<tr>' . "\n";
      			echo '<td class="border">' . $row['date'] . '</td>' . "\n";
      			echo '<td class="border">' . $row['monthCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot + $row['monthCount'];
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
		$query = "SELECT os, COUNT(*) AS osCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY os ORDER BY os DESC";
		$result = $DbConnect->query($query);
    		if (! IS_ENV_PRODUCTION) {                                                                                                                                                                  
                        echo "Stats-->showClicks SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                                                                     
                }
		$tot=0;
		while ($row = mysqli_fetch_array($result));  {
			echo '<tr>' . "\n";
			echo '<td class="border">' . $row['os'] . '</td>' . "\n";
			echo '<td class="border">' . $row['osCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot+$row['osCount'];
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
		$query = "SELECT browser, COUNT(*) AS browserCount FROM tbl_clicks WHERE url_name = '" . $this->url_name . "' GROUP BY browser ORDER BY browser DESC";
		$result = $DbConnect->query($query);
		if (! IS_ENV_PRODUCTION) {                                                                                                                                                                  
                        echo "Stats-->showClicks SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                                                                     
                }
    		$tot=0;
		while ($row = mysqli_fetch_array($result)) {
			echo '<tr>' . "\n";
			echo '<td class="border">' . $row['browser'] . '</td>' . "\n";
			echo '<td class="border">' . $row['browserCount'] . '</td>' . "\n";
			echo '</tr>' . "\n";
      			$tot=$tot+$row['browserCount'];
		}
		echo '<tr>' . "\n";
    		echo '<td class="border"><strong>Total</td>' . "\n";
    		echo '<td class="border">' . $tot . '</td>' . "\n";
    		echo '</tr>' . "\n";
		mysqli_close($DbConnect);	
	}
	
}
?>
