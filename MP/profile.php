<?php
include_once 'includes/header.php';
include_once 'includes/dbcon.php';

$id=$_SESSION["id"];
$type=$_SESSION["type"];
$sql="SELECT *FROM $type WHERE id ='$id'";
$result=mysqli_query($con,$sql);
$user =mysqli_fetch_assoc($result);
if(isset($_POST["sendotp"]))
{
    if($_POST["name"]===""){$name=$user["name"];}
    else {$name=mysqli_real_escape_string($con,$_POST["name"]);}
    
    if($_POST["address"]===""){$address=$user["address"];}
    else{$address=mysqli_real_escape_string($con,$_POST["address"]);}

    if($_POST["mnumber"]===""){$number=$user["mnumber"];}
    else{$number=mysqli_real_escape_string($con,$_POST["mnumber"]);}

    if($_POST["uname"]===""){$username=$user["username"];} 
    else{$username=mysqli_real_escape_string($con,$_POST["uname"]);}
    
    if($_POST["npwd"]===""&&$_POST["npwdrepeat"]===""){$npwd=$npwdRepeat=$user["password"];}
    else
    {
        $npwd=md5($_POST["npwd"]);
        $npwdRepeat=md5($_POST["npwdrepeat"]);
    }
    $_SESSION["type"]=$type;
    $_SESSION["name"]=$name;
    $_SESSION["address"]=$address;
    $_SESSION["mnumber"]=$number;
    $_SESSION["uname"]=$username;
    $_SESSION["npwd"]=$npwd;

    
    $pwd=md5($_POST["pwd"]);

 
    $sql="SELECT *FROM $type WHERE (username ='$username' OR mnumber='$number') AND id!=$id";
    $result=mysqli_query($con,$sql);
    $use =mysqli_fetch_assoc($result);

    if($user["password"]===$pwd)
    {
        if($use)
        {
            if($use["username"] ===$username)
            {
                $_SESSION["message"]="Username already exits";
                
            }
            elseif($use["mnumber"] ===$number)
            {
                $_SESSION["message"]="Mobile Number has linked to another account";
            }
        
        }
        elseif($npwd !==$npwdRepeat)
        {
            $_SESSION["message"]="Password do not match";
        }
        else
        {
            $_SESSION["mobileotpsent"]=$otp=mt_rand(10000,99999);
        
            $field = array(
                "sender_id" => "FSTSMS",
                "language" => "english",
                "route" => "qt",
                "numbers" => "$number",
                "message" => "40895",
                "variables" => "{#AA#}",
                "variables_values" => "$otp"
            );
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://www.fast2sms.com/dev/bulk",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_SSL_VERIFYHOST => 0,
              CURLOPT_SSL_VERIFYPEER => 0,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode($field),
              CURLOPT_HTTPHEADER => array(
                "authorization: yi761af2YrtmpDFv9HIoSPXxk4NzEljOhZC8eTcgV5wbGWs3JQLRJSF0OQTepxG7WNuahnoZkzl14yEd",
                "cache-control: no-cache",
                "accept: */*",
                "content-type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            $_SESSION["verify"]="unverified";
            $_SESSION['message']="Please check your mobile for OTP";
    
        }
    }
    else
    {
        $_SESSION["message"]="Please enter correct Password";
    }

}
elseif(isset($_POST["verifyotp"]))
{
    $mobileopt=mysqli_real_escape_string($con,$_POST["mobileotp"]);
    if(!isset($_SESSION['mobileotpsent']))
    {
        $_SESSION["msg"]="Please send OTP for verification";
    }
    elseif($mobileopt==$_SESSION['mobileotpsent'])
    {
        $_SESSION["verify"]="verified";
        $_SESSION["msg"]="Mobile number verified click on Change Profile to save your changes";
    }
    else
    {
        $_SESSION["msg"]="Wrong OTP Entered";
    }
}
elseif(isset($_POST["submit"]))
{
    if(!isset($_SESSION["verify"]))
    {
        $_SESSION["msg"]="Please verify mobile number";
    }
    elseif($_SESSION["verify"]==="verified")
    {
        
        $type=$_SESSION["type"];
        $name=$_SESSION["name"];
        $address=$_SESSION["address"];
        $number=$_SESSION["mnumber"];
        $username=$_SESSION["uname"];
        $npwd=$_SESSION["npwd"];

        $sql="UPDATE $type SET name='$name',address='$address',mnumber='$number',username='$username',password='$npwd' WHERE id=$id ";
        $result=mysqli_query($con,$sql);
        if($result)
        {
            $_SESSION["verify"]="unverified";
            $_SESSION["msg"]="You have change your profile details";
        }
        else{$_SESSION["msg"]="Server Error Please try later";}
    }
}


$sql="SELECT *FROM $type WHERE id ='$id'";
$result=mysqli_query($con,$sql);
$user =mysqli_fetch_assoc($result);
 
?>
<link rel="stylesheet" href="css/signuplogin.css" type="text/css">
<form class="sotp" action="profile.php" method="post">
    <h2>Change Profile</h2>
    <div class="inf"><?= $_SESSION['message'] ?></div>
    <input type="text" name="name" placeholder="<?php print($user['name'])?>">
    <input type="text" name="address" placeholder="<?php print($user['address'])?>" >
    <input type="text" name="mnumber" placeholder="<?php print($user['mnumber'])?>"  pattern="[7-9]{1}[0-9]{9}" >
    <input type="text" name="uname" placeholder="<?php print($user['username'])?>">
    <input type="password" name="npwd" placeholder="New Password">
    <input type="password" name="npwdrepeat" placeholder="Repeat New Password">
    <input type="password" name="pwd" placeholder="Password" required>
    <input type="submit" name="sendotp" value="Send OTP">
</form>
<form class="votp" action="" method="post">
    <h2>OTP Verification</h2>
    <div class="inf"><?= $_SESSION['msg']?></div><br>
    <input type="text" name="mobileotp" placeholder="Enter Mobile OTP" >
    <input type="submit" name="verifyotp" value="Verify OTP">
    <input type="submit" name="submit" value="Change Profile">
</form>

<?php
    include_once 'includes/footer.php';
?>