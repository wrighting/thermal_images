<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
$(".country").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",
url: "ajax_number.php",
data: dataString,
cache: false,
success: function(html)
{
$(".house").html(html);
}
});

});

$(".house").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",
url: "ajax_details.php",
data: dataString,
cache: false,
success: function(html)
{
$(".detail").html(html);
}
});

});
});
</script>
Street :
<select name="country" class="country">
<option selected="selected">--Select Street--</option>
<?php
require_once('db.php');
 $sql = 'SELECT DISTINCT street FROM houseInfo ORDER BY street';
$pdo = openDatabase();
    foreach ($pdo->query($sql) as $row) {
$id=$row['street'];
$data=$row['street'];
echo '<option value="'.$id.'">'.$data.'</option>';
} ?>
</select> 


<select name="house" class="house">
<option selected="selected">--Select House--</option>
</select>

<div class="detail">
</div>
