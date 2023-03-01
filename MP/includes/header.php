<?php
    session_start();
    $_SESSION["message"]="";
    $_SESSION["msg"]="";
?>
<html>
    <head>
        <link rel="stylesheet" href="css/header.css" type="text/css">
        <title>
            Mini Project
        </title>
        
    </head>
    <body>
        <nav >
            <div>
                <ul class="nav-menu">
                    <?php
                    if(isset($_SESSION["id"]))
                    {   
                        $type=$_SESSION["type"];
                        echo"<li><a href='$type.php'>Home</a></li>";
                        echo"<li><a href='profile.php'>Profile</a></li>";
                        echo"<li><a href='logout.php'>Log Out</a></li>";
                    }
                    else
                    {
                        echo"<li><a href='index.php'>Home</a></li>";
                        echo"<li><a href='signup.php'>Sign Up</a></li>";
                        echo"<li><a href='login.php'>Log In</a></li>";
                    }
                    ?>
                   <li><a href="contactus.php">Contact Us</a></li>
                </ul>
            </div>
        </nav>