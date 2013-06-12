<?php
error_reporting(E_ALL ^ E_NOTICE);
set_include_path(get_include_path().PATH_SEPARATOR.'./php-spreadsheetreader');
require_once 'excel_reader2.php';
require_once 'php-spreadsheetreader/SpreadsheetReaderFactory.php';

/**
 * Print a file's metadata.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param string $fileId ID of the file to print metadata for.
 */
function printFile($service, $fileId) {
  try {
    $file = $service->files->get($fileId);
//print_r($file);
//    print "Title: " . $file->title;
 //   print "Description: " . $file->getDescription();
 //   print "MIME type: " . $file->mimeType;
  //  print "modifiedDate: " . $file->modifiedDate;
  } catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
  }
  return ($file);
}

/**
 * Download a file's content.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param File $file Drive File instance.
 * @return String The file's content if successful, null otherwise.
 */
function downloadFile($service, $file) {
	if ($file->mimeType == 'application/vnd.ms-excel') {
		$downloadUrl = $file->getDownloadUrl();
	} else if ($file->mimeType == 'application/vnd.google-apps.spreadsheet') {
		$downloadUrl = $file->exportLinks['application/x-vnd.oasis.opendocument.spreadsheet'];
	}
  if ($downloadUrl) {
    $request = new Google_HttpRequest($downloadUrl, 'GET', null, null);
    $httpRequest = Google_Client::$io->authenticatedRequest($request);
    if ($httpRequest->getResponseHttpCode() == 200) {
      return $httpRequest->getResponseBody();
    } else {
      // An error occurred.
      return null;
    }
  } else {
    // The file doesn't have any content stored on Drive.
    return null;
  }
}

function saveToDatabase($pdo, $photo)
{
$sth = $pdo->prepare('INSERT IGNORE INTO houseInfo (village, street, houseNum) VALUES (?, ?, ?)');
$stp = $pdo->prepare('INSERT IGNORE INTO photoInfo (idhouseInfo, idsurveyInfo, photoId, notes) VALUES (?, ?, ?, ?)');
$sts = $pdo->prepare('INSERT IGNORE INTO surveyInfo (dateOfSurvey, surveyTeam, weather, airTemp, foilTemp) VALUES (?, ?, ?, ?, ?)');
$ret = $sth->execute(array($photo->village, $photo->street, $photo->house));
if ($ret === FALSE) {
	die(print_r($sth->errorInfo(), true));
}
list($month, $day, $year) = explode("/", $photo->date);
$parsedDate = $year."-".$month."-".$day;
$sts->execute(array($parsedDate, $photo->surveyTeam, $photo->weather, $photo->airTemp, $photo->foilTemp));
$surveyId = 1;
 $sql = 'SELECT idhouseInfo FROM houseInfo WHERE village = '.$pdo->quote($photo->village).' AND street = '.$pdo->quote($photo->street).' AND houseNum = '.$pdo->quote($photo->house) ;
    foreach ($pdo->query($sql) as $row) {
	$houseId = $row['idhouseInfo'];
    }
// PDOStatement::closeCursor();
 $sql = 'SELECT idsurveyInfo FROM surveyInfo WHERE dateOfSurvey = '.$pdo->quote($parsedDate).' AND surveyTeam = '.$pdo->quote($photo->surveyTeam);
    foreach ($pdo->query($sql) as $row) {
	$surveyId = $row['idsurveyInfo'];
    }
 //PDOStatement::closeCursor();
$photoInfo = array($houseId, $surveyId, $photo->photoId, $photo->notes);
//print_r($photoInfo);
$stp->execute($photoInfo);
}

function retrieveAllFiles($service, $pdo) {
  $result = array();
  $pageToken = NULL;

$ret = $pdo->query('truncate table fileInfo');
if ($ret === FALSE) {
	die(print_r($sth->errorInfo(), true));
}
  do {
	try {
		$parameters = array();
		if ($pageToken) {
			$parameters['pageToken'] = $pageToken;
		}
		$files = $service->files->listFiles($parameters);
		//print_r($files);
		$items = $files->getItems();
		//$items = $files;
  $lastFile = count($items);

  for ($i = 0;$i < $lastFile;$i++) {
		//print_r($items[$i]);
	saveFileInfoToDatabase($pdo, $items[$i]);
  }
//		$result = array_merge($result,$files->getItems());
		$pageToken = $files->getNextPageToken();
	} catch(Exception $e) {
	//	print_r($e);
  		$pageToken = NULL;
	}
  } while ($pageToken);

  return ($result);
}

function saveFileInfoToDatabase($pdo, $fileInfo)
{
$parsedDate = 'NULL';
$parentId = 'NULL';
if ($fileInfo->imageMediaMetadata && $fileInfo->imageMediaMetadata->date) {
	list($year,$month, $day, $hour,$min,$sec) = preg_split("/[: ]+/", $fileInfo->imageMediaMetadata->date);
	$parsedDate = $year."-".$month."-".$day." ".$hour.":".$min.":".$sec;
}
$parentId = $fileInfo->parents[0]->id;
$sth = $pdo->prepare('INSERT IGNORE INTO fileInfo (title, thumbnailLink, webContentLink,googleDriveId,lastModified,dateTaken, parentId) VALUES (?, ?, ?, ?, ?, ?, ?)');
$params = array($fileInfo->title, $fileInfo->thumbnailLink, $fileInfo->webContentLink, $fileInfo->id, $fileInfo->modifiedDate, $parsedDate, $parentId);
//print_r($params);
$ret = $sth->execute($params);
if ($ret === FALSE) {
	die(print_r($sth->errorInfo(), true));
}
}

//Return true if the index has been updated
function updateIndex($drive,$file, $pdo) {
$ret = false;
  //print_r($files);
 $sql = 'SELECT lastModified FROM fileInfo WHERE googleDriveId = '.$pdo->quote($file->id);
    foreach ($pdo->query($sql) as $row) {
	$modDate = $row['lastModified'];
    }
$modDate='';
 if ($file->modifiedDate != $modDate) {
  $content = downloadFile($drive,$file);
$tmpfname = tempnam("/tmp", "FOO");
	if ($file->mimeType == 'application/vnd.ms-excel') {
		$tmpfname .= '.xls';
	} else if ($file->mimeType == 'application/vnd.google-apps.spreadsheet') {
		$tmpfname .= '.ods';
	}

$handle = fopen($tmpfname, "w");
fwrite($handle, $content);
fclose($handle);
	$processed = false;
	if ($file->mimeType == 'application/vnd.ms-excel') {
		saveExcel($pdo, $tmpfname);
		$processed = true;
	} else if ($file->mimeType == 'application/vnd.google-apps.spreadsheet') {
		saveSpreadsheet($pdo, $tmpfname);
	} else {
		echo $tmpfname;
	}
	//print_r($details);
	if ($processed) {
		unlink($tmpfname);
	}
	$ret = true;
}
return ($ret);
}

function saveSpreadsheet($pdo, $tmpfname) {
	$reader = SpreadsheetReaderFactory::reader($tmpfname);
	$sheets = $reader->read($tmpfname);
	for ($row = 1; $row < count($sheets[0]); $row++) {
		$photo = new stdClass();
		$photo->date = $sheets[0][$row][0];
		$photo->surveyTeam = $sheets[0][$row][1];
		$photo->weather = $sheets[0][$row][2];
		$photo->airTemp = $sheets[0][$row][3];
		$photo->foilTemp = $sheets[0][$row][4];
		$photo->house = $sheets[0][$row][5];
		$photo->street = $sheets[0][$row][6];
		$photo->village = $sheets[0][$row][7];
		$photo->photoId = $sheets[0][$row][9];
		$photo->notes = $sheets[0][$row][13];
		$key = $photo->house.$photo->street.$photo->village;
		saveToDatabase($pdo,$photo);
/*
echo "<pre>";
print_r($photo);
print_r($sheets[0][$row]);
echo "</pre>";
*/
	}
}
function saveExcel($pdo, $tmpfname) {

$data = new Spreadsheet_Excel_Reader($tmpfname);
$sheet = 0;
$lastrow = $data->rowcount($sheet);
//$lastrow = 5;
$details = array();
 for($row=1; $row<=$lastrow; $row++) { 
	$photo = new stdClass();
	$photo->date = $data->val($row,1,$sheet);
	$photo->surveyTeam = $data->val($row,2,$sheet);
	$photo->weather = $data->val($row,3,$sheet);
	$photo->airTemp = $data->val($row,4,$sheet);
	$photo->foilTemp = $data->val($row,5,$sheet);
	$photo->house = $data->val($row,6,$sheet);
	$photo->street = $data->val($row,7,$sheet);
	$photo->village = $data->val($row,8,$sheet);
	$photo->photoId = $data->val($row,10,$sheet);
	$photo->notes = $data->val($row,14,$sheet);
	$key = $photo->house.$photo->street.$photo->village;
//print_r($photo);

saveToDatabase($pdo,$photo);
/*
	$photos = $details[$key]->photos;
	if (!$photos) {
		$photos = ($details[$key]->photos = array());
	}
	$photos[] = $photo;
	$details[$key]->photos = $photos;
*/
 }
}
?>
