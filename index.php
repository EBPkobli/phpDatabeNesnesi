<?php
require_once "kobliDB.php";
$kDB = new kobliDB("mysql","localhost","80","mydatabase","root","");
//var_dump($kDB->getValues("myTable","myName"));
//$valueArray = $kDB->valueOf("myTable","myName","Muhammed Furkan");
//echo $valueArray["myTime"];
//print_r ($kDB->getColumns("myTable"));
//$kDB->getTable("myTable");
//$kDB->changeValue("myTable",["mySurname","myName"],["Oldu!","Başarılı"],["myName",""]);
//$kDB->newValue("myTable",["mySurname","myName"],["Soyadim!","Ali Hasan"]);
//$kDB->deleteThis("myTable",["myName"],["Furkan"]);
//$kDB->deleteALL("myTable");
//$kDB->newValue("myTable",["mySurname","myName"],["Soyadim!","Ali Hasan"])->changeValue("myTable",["mySurname"],["Oldu!"],["myName","Ali Hasan"]);
?>