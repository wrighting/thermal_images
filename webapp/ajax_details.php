<?php
include('db.php');
if($_POST['id'])
{
$pdo = openDatabase();
$id=$_POST['id'];
 $sql = 'SELECT DISTINCT idhouseInfo,houseNum,dateOfsurvey,surveyTeam,weather,airTemp,foilTemp,photoId,webContentLink,thumbnailLink,notes FROM houseInfo '.
		"LEFT JOIN photoInfo USING(idhouseInfo) ".
		"LEFT JOIN surveyInfo USING(idsurveyInfo) ".
		"LEFT JOIN fileInfo ON fileInfo.title = CONCAT('IR_',photoId,'.jpg') ".
		"WHERE idhouseInfo = ".$pdo->quote($id);
    $header = true;
    foreach ($pdo->query($sql) as $row) {
	if ($header) {
		echo $row["dateOfsurvey"]." ".$row["surveyTeam"]." ".$row["weather"];
    $header = false;
	}
		echo '<br/>'.$row["notes"];
		echo "<br/>".$row["photoId"];
		echo '<img src="'.$row["thumbnailLink"].'"/>';
//		echo $row["thumbnailLink"];
//print_r($row);
		echo '<br/><a href="'.$row["webContentLink"].'">Download</a>';
}
}
?>
