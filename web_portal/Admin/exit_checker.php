<?php
include "../include/config.php";
$getRows = $conn->query("SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID");

echo $getRows->num_rows;

?>