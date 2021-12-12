#!/usr/bin/php

<?php


$db_host ="localhost";
$db_user ="admin";
$db_password ="12345";
$db_name= "it490db";
try{
  $db = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_password);
  $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  
}
catch(PDOEXCEPTION $e){
  echo "eroor in connection" .$e->getMessage();
}
?>
