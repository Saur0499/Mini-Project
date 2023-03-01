<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';
if($_SESSION['type']!=="citizen")
{
    exit("You must login as citizen to view this page");
}
$id=$_SESSION["id"];
$sql="SELECT id,image,description,location,status FROM query WHERE $id=citizenid ";
$result=mysqli_query($con,$sql);

?>

<link rel="stylesheet" href="css/trackquery.css" type="text/css">
<link rel="stylesheet" href="css/signuplogin.css" type="text/css">

<table>
    <tr>
    <td> Query ID </td>
    <td> Image </td>
    <td> Description </td>
    <td> Location </td>
    <td> Status</td>
    </tr>
    <tr>
        <?php
            while($row=mysqli_fetch_row($result))
            {
                echo"
                    <tr>
                    <td> $row[0]</td>
                    <td>
                    <form action='image.php' method='post'> 
                        <input type='hidden' name='queryid' value='$row[0]'>
                        <input type='hidden' name='image' value='$row[1]'>
                        <input type='submit' value='View Image' name='viewimage'>
                    </form>
                    </td>
                    <td> $row[2]</td>
                    <td> 
                    <form action='location.php' method='post'> 
                        <input type='hidden' name='location' value='$row[3]'>
                        <input type='submit' value='View Location' name='submitlocation'>
                    </form>
                    </td>
                    <td> $row[4]</td>
                    </tr>";
            }
        ?>
    </tr>
</table>

<?php
    include_once 'includes/footer.php';
?>