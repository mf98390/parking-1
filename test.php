<?php
    $handle = fopen("http://data.tycg.gov.tw/api/v1/rest/datastore/0daad6e6-0632-44f5-bd25-5e1de1e9146f?format=json","rb");
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
    foreach ($content['result']['records'] as $value) {

    $sql = "INSERT INTO test (parkName, areaId, lng, totalSpace, introduction, lat, parkId, address, payGuide, _id, surplusSpace, areaName) VALUES ('".$value['parkName']."', ".$value['areaId'].", ".$value['wgsX'].", ".$value['totalSpace'].", '".$value['introduction']."', ".$value['wgsY'].", '".$value['parkId']."', '".$value['address']."', '".$value['payGuide']."', ".$value['_id'].", '".$value['surplusSpace']."', '".$value['areaName']."')";
    if (mysqli_query($connection, $sql)) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
    }
    mysqli_close($connection);
?>