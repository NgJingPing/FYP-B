<?php
include "../include/config.php";
$getRows = $conn->query("SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID");

echo $getRows->num_rows;

?>