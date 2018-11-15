<?php
ignore_user_abort(true);//if caller closes the connection (if initiating with cURL from another PHP, this allows you to end the calling PHP script without ending this one)
set_time_limit(0);

$hLock=fopen(__FILE__.".lock", "w+");
if(!flock($hLock, LOCK_EX | LOCK_NB))
    die("Already running. Exiting...");

while(true)
{
 $handle="";
    try{
    $handle = fopen("http://data.tycg.gov.tw/api/v1/rest/datastore/0daad6e6-0632-44f5-bd25-5e1de1e9146f?format=json","rb");
    if ( !$handle ) {
        throw new Exception('Network failed.');
      }  
      }catch ( Exception $e ) {
      // send error message if you can
    echo 'Network failed.';
    exit(0);
    } 
    $content = "";
    while (!feof($handle)) {
    $content .= fread($handle, 10000);
    }
    fclose($handle);
    $content = json_decode($content,true);

    /*
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parking";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    */
    require("test_sql.php");

    // Opens a connection to a MySQL server
    $connection=mysqli_connect ('localhost', $username, $password);

    if (!$connection) {
    die('Not connected : ' . mysqli_error($connection));
    }

    // Set the active MySQL database
    mysqli_query($connection,"SET NAMES 'UTF8'");
    $db_selected = mysqli_select_db($connection, $database);
    if (!$db_selected) {
    die ('Can\'t use db : ' . mysqli_error($connection));
    }

    //$content = json_decode($content,true);
    foreach ($content['result']['records'] as $key => $value) {

    if($value){

        //mysqli_query($connection,"UPDATE test SET surplusSpace = '".$value."' WHERE _id = '". $key ."'");
        $sql="UPDATE test SET surplusSpace = '".$value['surplusSpace']."' WHERE _id = '". $value['_id'] ."'";
        echo $sql;
       if (mysqli_query($connection, $sql)) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
      }
      }
      }
    mysqli_close($connection);
    
    //avoid CPU exhaustion, adjust as necessary
    usleep(300000000);// 300 seconds
}

flock($hLock, LOCK_UN);
fclose($hLock);
unlink(__FILE__.".lock");
?>