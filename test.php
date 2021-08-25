<?php
include("config.php");

$BrowsInf = new BrowserDetection();

echo "\n" . $BrowsInf->getInfo() . "\n";

?>
