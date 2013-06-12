<?php
include('db.php');
echo '<option value="">-- Select a house number</option>';
if($_POST['id'])
{
$pdo = openDatabase();
$id=$_POST['id'];
 $sql = 'SELECT idhouseInfo,houseNum FROM houseInfo WHERE street = '.$pdo->quote($id).' ORDER BY 0 + houseNum';
    foreach ($pdo->query($sql) as $row) {
$id=$row['idhouseInfo'];
$data=$row['houseNum'];
echo '<option value="'.$id.'">'.$data.'</option>';
}
}
?>
