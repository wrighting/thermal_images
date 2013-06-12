<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'db.php';
require_once 'gclient.php';
require_once 'selector.php';
require_once 'load_db.php';

$gc = new gclient();
if($gc->openClient($_GET["code"])) {

  $drive = $gc->getDriveService($client);
  $pdo = openDatabase();
//Array of ids of the index files
  $indexFiles = array('','');

  foreach ($indexFiles as $key) {
	$file = printFile($drive, $key);
	updateIndex($drive,$file, $pdo);
  }
//Links expire so need to reload
  $files = retrieveAllFiles($drive,$pdo);

  showSelector();
} 
?>
