<?php
include "../include/config.php";

$counts = "";

$totalflowquery = "SELECT(SELECT COUNT(*) FROM entrylog WHERE DATE(`entryTime`) = CURDATE()) + (SELECT COUNT(*) FROM exitlog WHERE DATE(`exitTime`) = CURDATE()) AS total";
$result = $conn->query($totalflowquery);
while ($row = mysqli_fetch_array($result)) {
    $counts = $counts . $row['total'];
}

$totalentryquery = "SELECT COUNT(*) AS totalentry FROM entrylog WHERE DATE(`entryTime`) = CURDATE()";
$result = $conn->query($totalentryquery);
while ($row = mysqli_fetch_array($result)) {
    $counts = $counts . ",". $row['totalentry'];
}

$totalexitquery = "SELECT COUNT(*) AS totalexit FROM exitlog WHERE DATE(`exitTime`) = CURDATE()";
$result = $conn->query($totalexitquery);
while ($row = mysqli_fetch_array($result)) {
     $counts = $counts . ",". $row['totalexit'];
}

$totaldeniedquery = "SELECT COUNT(*) AS totaldenied FROM deniedAccess WHERE DATE(`deniedTime`) = CURDATE()";
$result = $conn->query($totaldeniedquery);
while ($row = mysqli_fetch_array($result)) {
    $counts = $counts . ",". $row['totaldenied'];
}

echo $counts;
?>