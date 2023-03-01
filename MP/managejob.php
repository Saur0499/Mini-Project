<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';
if($_SESSION['type']!=="worker")
{
    exit("You must login as worker to view this page");
}
if(isset($_POST["submit"]))
{
    $image=mysqli_real_escape_string($con,"images/workeruploads/".uniqid('',true).$_FILES["image"]["name"]);
    $queryid=mysqli_real_escape_string($con,$_POST["queryid"]);
    $uid=$_SESSION["id"];

    $sql="SELECT * FROM query WHERE id='$queryid' AND workerid='$uid' AND status!='Work done succesfully & query closed'";
    $result=mysqli_query($con,$sql);
    $result=mysqli_fetch_assoc($result);
    
    if($result)
    {
        
        if(copy($_FILES["image"]["tmp_name"],$image))
        {

            $sql="UPDATE query SET jobimage='$image',status='Job submitted by worker pending for approval from admin' WHERE id='$queryid' ";
            $result=mysqli_query($con,$sql);
            if($result)
            { 
                $_SESSION["message"]="Work done is submitted to admin!";
        
            }
            else{$_SESSION["message"]="Server error please try after some time";}
        }
        else
        {
        $_SESSION['message']='File upload failed';
        }
    }
    else
    {
        $_SESSION["message"]="Please enter valid QueryId";
    }
}




?>
<link rel="stylesheet" href="css/signuplogin.css" type="text/css">
    
<form  class="sotp" action="managejob.php" method="post" enctype="multipart/form-data">
    <h2> Manage Job</h2>
    <div class="inf"><?= $_SESSION['message'] ?></div><br>
    <label style="color: white;">Select image </label>
    <input type="file" name="image" accept="image/*" style="color: white;"  required><br><br>
    <input type="text" name="queryid" placeholder="Query ID" required >
    <input type="submit" name="submit" value="Job Done" class="btn btn-block btn-primary">
</form>

<?php
    include_once 'includes/footer.php';
?>