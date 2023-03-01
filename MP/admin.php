<?php
    include_once 'includes/header.php';
    if($_SESSION['type']!=="admin")
    {
        exit(" Error:You must login as admin to view this page.");
    }

?>

<link rel="stylesheet" href="css/opt.css" type="text/css">
<ul class="copt">
    <li><a href='queryinfo.php'>View Queries</a></li><br><br>
    <li><a href='generatereport.php'>Generate Report</a></li><br><br>
    <li><a href='feedback.php'> View Feedback</a></li>
</ul>

<?php
    include_once 'includes/footer.php';
?>