<?php
    include_once 'includes/dbcon.php';
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="Report.txt"');
    $sql="SELECT * FROM query";
    $result=mysqli_query($con,$sql);
    $tot=$jobassign=$adminapproval=$complete=0;
    while($row=mysqli_fetch_row($result))
    {
        if($row[7]==="Job assign to worker")
            $jobassign++;
        if($row[7]==="Job submitted by worker pending for approval from admin")
        {    
            $jobassign++;
            $adminapproval++;
        }
        elseif($row[7]==="Work done succesfully & query closed")
        {
            $complete++;
            $jobassign++;
        }
        $tot++;
    }
    echo"
         Number of Queries :$tot
         Number of Queries assigned to worker:$jobassign
         Number of Queries completed by worker and pending for approval:$adminapproval
         Number of Queries completed :$complete
    
        ";
?>