<?php
function openDatabase()
{

$dbhost = "localhost";
$dbname = "";
$dbuser = "";
$dbpwd = "";
$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpwd);

return $pdo;
}
?>
