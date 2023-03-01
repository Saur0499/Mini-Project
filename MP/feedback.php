<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';
if($_SESSION['type']!=="citizen"&&$_SESSION['type']!=="admin")
{
    exit("You must login as citizen or admin to view this page");
}
$id=$_SESSION["id"];
$sql="";

if(isset($_POST["submit"]))
{
    $feedback=mysqli_real_escape_string($con,$_POST["feedback"]);
    $queryid=mysqli_real_escape_string($con,$_POST["queryid"]);
    $sql="UPDATE query SET feedback='$feedback' WHERE $id=citizenid AND id=$queryid";
    $resultf=mysqli_query($con,$sql);
}
if($_SESSION['type']==="citizen")
{
    $sql="SELECT * FROM query WHERE $id=citizenid AND status='Work done succesfully & query closed'";
    $result=mysqli_query($con,$sql);
}
if($_SESSION['type']==="admin")
{
    $sql="SELECT * FROM query WHERE feedback!=''";
    $result=mysqli_query($con,$sql);
}


?>

<link rel="stylesheet" href="css/trackquery.css" type="text/css">
<link rel="stylesheet" href="css/signuplogin.css" type="text/css">
<div>
<table>
    <tr>
    <td> Query ID </td>
    <td> Description </td>
    <td> Status</td>
    <td> Feedback</td>
    </tr>
    <tr>
        <?php
            while($row=mysqli_fetch_row($result))
            {
                
                echo"
                    <tr>
                    <td> $row[0]</td>
                    <td> $row[4]</td>
                    <td> $row[7]</td>";
                if($row[8]===NULL&&$_SESSION["type"]==="citizen")
                {
                    echo"
                    <td>
                    <form action='feedback.php' method='post'>
                        <input type='hidden' name='queryid'  value='$row[0]'>
                        <input type='text' name='feedback' placeholder='Feedback'>
                        <input type='submit' name='submit' value='Submit Feedback'>
                    </form>
                    </td>";
                }
                else
                {
                    echo"<td> $row[8]</td>";
                }
                echo"</tr>";
            }
        ?>
    </tr>
</div>




<?php
    include_once 'includes/footer.php';
?>