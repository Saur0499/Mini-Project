<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';
if($_SESSION['type']!=="admin")
{
    exit("You must login as admin to view this page");
}
$sql="SELECT id,name,address,mnumber FROM worker";
$result=mysqli_query($con,$sql);
?>
<link rel="stylesheet" href="css/trackquery.css" type="text/css">
<div>
<table>
    <tr>
    <td> Worker ID</td>
    <td> Name</td>
    <td> Address </td>
    <td> Mobile Number </td>
    </tr>
    <tr>
        <?php
            while($row=mysqli_fetch_row($result))
            {
                $img=$row[3];
                echo"
                    <tr>
                    <td> $row[0]</td>
                    <td> $row[1]</td>
                    <td> $row[2]</td>
                    <td> $row[3]</td>
                    </tr>";
            }
        ?>
    </tr>
</div>

<?php
    include_once 'includes/footer.php';
?>