<?php
error_reporting(E_ALL ^ E_NOTICE);
set_include_path(get_include_path().PATH_SEPARATOR.'./php-spreadsheetreader');
require_once 'google-api-php-client/src/Google_Client.php';
//require_once 'google-api-php-client/src/contrib/Google_PlusService.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';
require_once 'excel_reader2.php';
require_once 'db.php';
require_once 'php-spreadsheetreader/SpreadsheetReaderFactory.php';

class gclient {

  protected $drive;	
// Set your cached access token. Remember to replace $_SESSION with a
// real database or memcached.
  function openClient($code) {

	session_start();

//Setup is done via the local_config.php in the same directory as Google_Client.php
	$client = new Google_Client();
//$plus = new Google_PlusService($client);

	//Get an error about missing required parameter scope if this is missing
	$this->drive = new Google_DriveService($client);
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	if (isset($code)) {
	  $client->authenticate();
	  $_SESSION['token'] = $client->getAccessToken();
	  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	}

	if (isset($_SESSION['token'])) {
	  $client->setAccessToken($_SESSION['token']);
	}

	if ($client->getAccessToken()) {
	  // We're not done yet. Remember to update the cached access token.
	  // Remember to replace $_SESSION with a real database or memcached.
	  $_SESSION['token'] = $client->getAccessToken();
	  $client->setUseObjects(true);
	  return (true);
	} else {
	  $authUrl = $client->createAuthUrl();
	  print "<a href='$authUrl'>Connect Me!</a>";
	  return (false);
	}
  }

  function getDriveService() {
	return $this->drive;
  }
}
?>
