<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';

if(isset($_POST["viewimage"]))
{
    $image=mysqli_real_escape_string($con,$_POST["image"]);
    $_SESSION["queryid"]=mysqli_real_escape_string($con,$_POST["queryid"]);
}
$type=$_SESSION["type"];
if($type==="citizen")
{
    echo"
        <form action='' method='post' enctype='multipart/form-data'>
            <h2 style='position:fixed; left:10%; top:5%'>Change Image</h2>
            <input type='file' name='image' accept='image/*' style='color: white; position:fixed; left:10%; top:15%;' required><br><br>
            <input type='submit' name='changeimage' style='position:fixed; left:10%; top:20%' value='Change Image'>
    </form>
    ";
}
if(isset($_POST["changeimage"]))
{
    $image=mysqli_real_escape_string($con,"images/citizenuploads/".uniqid('',true).$_FILES["image"]["name"]);
    $queryid=$_SESSION["queryid"];

    if(copy($_FILES["image"]["tmp_name"],$image))
    {
        $sql="UPDATE query SET image='$image' WHERE id=$queryid";
        $result=mysqli_query($con,$sql);
    }
    else
    {
        echo"File Upload Failed";
    }
    
}
?>
<link rel="stylesheet" href="css/signuplogin.css" type="text/css">
<img src="<?php print($image)?>" alt="Couldn't load Image" style="position:fixed; left:30%; right:30%; top:100px; width:40%; height:80%;">


<?php
    include_once 'includes/footer.php';
?>