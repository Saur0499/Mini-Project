<?php
    include_once 'includes/header.php';
    if($_SESSION['type']!=="worker")
    {
        exit("You must login as worker to view this page.");
    }
?>

<link rel="stylesheet" href="css/opt.css" type="text/css">
<div>
    <ul class="copt">
        <li><a href="viewjob.php">View Job</a></li><br><br>
        <li><a href='managejob.php'>Manage Job</a></li>
    </ul>
</div>

<?
    include_once 'includes/footer.php';
?>