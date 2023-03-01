<?php

include_once 'includes/header.php';
include_once 'includes/dbcon.php';

if(isset($_POST["sendotp"]))
{
    $type=$_SESSION["type"]=mysqli_real_escape_string($con,$_POST["type"]);
    $name=$_SESSION["name"]=mysqli_real_escape_string($con,$_POST["name"]);
    $address=$_SESSION["address"]=mysqli_real_escape_string($con,$_POST["address"]);
    $number=$_SESSION["mnumber"]=mysqli_real_escape_string($con,$_POST["mnumber"]);
    $username=$_SESSION["uname"]=mysqli_real_escape_string($con,$_POST["uname"]);
    $pwd=$_SESSION["pwd"]=md5($_POST["pwd"]);
    $pwdRepeat=$_SESSION["pwdrepeat"]=md5($_POST["pwdrepeat"]);

    if($type==="admin")
    {
        $sql="SELECT *FROM $type";
        $result=mysqli_query($con,$sql);
        $use =mysqli_fetch_assoc($result);
        if($use)
        {
            $_SESSION["message"]="Admin Already exists";
        }
    }
    else
    {
        $sql="SELECT *FROM $type WHERE username ='$username' OR mnumber='$number' ";
        $result=mysqli_query($con,$sql);
        $use =mysqli_fetch_assoc($result);
    }

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
    elseif($pwd !==$pwdRepeat)
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
        $_SESSION["msg"]="Mobile number verified click on Sign Up button to Sign In";
    }
    else
    {
        $_SESSION["msg"]="Wrong OTP entered";
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
        $pwd=$_SESSION["pwd"];
        $pwdRepeat=$_SESSION["pwdrepeat"];

        $sql="INSERT INTO $type (name,address,mnumber,username,password) VALUES ('$name','$address','$number','$username','$pwd')";
        $result=mysqli_query($con,$sql);
        if($result)
        {
            $_SESSION["verify"]="unverified";
            $_SESSION["msg"]="You have Signed Up!";
        }
        else{$_SESSION["msg"]="Server Error Please try later";}
    }
}

?>
<link rel="stylesheet" href="css/signuplogin.css" type="text/css">
<form  class="sotp" action="" method="post">
    <h2>Sign Up</h2>
    <div class="inf"><?= $_SESSION['message']?></div><br>
    <label for="type" style="color: white;">Sign Up as : </label>
    <input type="radio" name="type" value="admin" required><span>Admin</span>
    <input type="radio" name="type" value="citizen" required><span>Citizen</span>
    <input type="radio" name="type" value="worker" required><span>Worker</span>
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="address" placeholder="Address" required >
    <input type="text" name="mnumber" placeholder="Mobile Number" pattern="[7-9]{1}[0-9]{9}"  title="Please Enter Valid Mobile Number"required>
    <input type="text" name="uname" placeholder="Username" required>
    <input type="password" name="pwd" placeholder="Password" required>
    <input type="password" name="pwdrepeat" placeholder="Repeat Password" required>
    <input type="submit" name="sendotp" value="Send OTP">
</form>
<form  class="votp" action="" method="post">
    <h2>OTP Verification</h2>
    <div class="inf"><?= $_SESSION['msg']?></div><br>
    <input type="text" name="mobileotp" placeholder="Enter OTP">
    <input type="submit" name="verifyotp" value="Verify OTP">
    <input type="submit" name="submit" value="Sign Up">
</form>

<?php
    include_once 'includes/footer.php';
?>