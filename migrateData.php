<?php                                                                                                                                                               
                                                                                                                                                                    
include("config.php");                                                                                                                                              
                                                                                                                                                                    
$DbConnect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);                                                                                              
$query = "SELECT click_id, user_agent FROM tbl_clicks";                                                                                                             
$result = $DbConnect->query($query);                                                                                                                                
echo "SQL : " . $DbConnect->errno . " " . $DbConnect->error;                                                                                                        
echo "\n";                                                                                                                                                          
while ($row = mysqli_fetch_array($result)) {                                                                                                                        
        $br = new BrowserDetection($row["user_agent"]);                                                                                                             
        $connect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);                                                                                        
        $updtquery = "UPDATE tbl_clicks SET os = '" . $br->detect()->getplatform() . "', browser = '" . $br->detect()->getBrowser() . "' WHERE click_id = '" . $row["click_id"] ."'";                                                                                                                                                   
        $update = $connect->query($updtquery);                                                                                                                      
                                                                                                                                                                    
        mysqli_close($connect);                                                                                                                                     
}                                                                                                                                                                   
mysqli_close($DbConnect);                                                                                                                                           
                                                                                                                                                                    
?> 
