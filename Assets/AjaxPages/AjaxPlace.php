<option value="">Select Place</option>
<?php
include('../Connection/Connection.php');

$did = $_GET['did'];

$qry = "SELECT * FROM tbl_place WHERE district_id = $did";
$result = mysqli_query($conn, $qry);

while($row = mysqli_fetch_assoc($result)){
    ?>
    <option value="<?php echo $row['place_id']; ?>"><?php echo $row['place_name']; ?></option>
    <?php
}


?>