<?php
include "../include/config.php";

$result2 = $conn->query("SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID");

$result = $conn->query("SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE DATE(entryTime) = CURDATE() ORDER BY entrylog.referenceID DESC");

if($result2->num_rows > 0) {
	if ($result) {
        echo '<div class="entry_log_container">';
        echo '<h1>Recent Entries</h1>';
        echo '<div class="table-responsive entry" style="overflow-x: hidden;">';
        echo '<div style="display:none;" class="curr_val">'.$result2->num_rows.'</div>';
        echo '<table id="entry_table" class="table table-striped table-bordered display responsive nowrap" style="width:100%;">
                <thead>
                    <tr>
                        <td>Timestamp</td>
                        <td>License Plate Number</td>
                        <td>Tenant Lot Number</td>
                    </tr>
                </thead>';

        while ($row = mysqli_fetch_array($result)) {
            $date = $row['entryTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
                <tr>  
                    <td>' . $format . '</td>  
                    <td>' . $row["licensePlate"] . '</td>  
                    <td>' . $row["tenantLotNumber"] . '</td>  
                </tr>  
                ';
        }
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
}

?>

<script>
$(document).ready(function(){  
    $('#entry_table').DataTable().destroy();

    var entry = $('#entry_table').DataTable({
        order: [ 0, 'desc' ],
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    }); 

  entry.buttons().container().appendTo('.entry')
});  

</script>