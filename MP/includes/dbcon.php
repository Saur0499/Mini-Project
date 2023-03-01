<?php

$serverName="sql304.epizy.com";
$dbUsername="epiz_27193186";
$dbPassword="nPyr8vUBo9QoFqL";
$dbName="epiz_27193186_syst";

$con=mysqli_connect($serverName,$dbUsername,$dbPassword,$dbName);

if(!$con)
{
    die("Connection Failed ".mysqli_connect_error());
}
