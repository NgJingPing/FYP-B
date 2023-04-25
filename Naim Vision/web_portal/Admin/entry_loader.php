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
        "order": [ 0, 'desc' ],
        "buttons": [
          {
              extend: 'copy',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'csv',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'excel',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'pdfHtml5',
              exportOptions: {
                  columns: ':visible'
              },
              customize: function (doc) {
                  // Set page margins
                  doc.pageMargins = [30, 30, 30, 30];

                  // Set table width to 100%
                  doc.content[1].layout = 'fullWidth';

                  // Add padding to table cells
                  doc.content[1].table.body.forEach(function(row) {
                    row.forEach(function(cell) {
                      cell.margin = [5, 5, 5, 5];
                      cell.style = 'cellPadding';
                    });
                  });

                  // Define the 'cellPadding' style
                  doc.styles.cellPadding = {
                    fillColor: '#f3f3f3',
                    halign: 'left',
                    padding: 6
                  };

                  doc.content[1].table.body.forEach(function(row, i) {
                    if (i === 0) {
                        // set header row styles
                        row.forEach(function(cell) {
                            cell.fillColor = '#061C17';
                            cell.color = '#C5E5CC';
                            cell.bold = true;
                        });
                    }
                });
              }
          },
          {
              extend: 'print',
              exportOptions: {
                  columns: ':visible'
              }
          }
      ],
    "processing": true
    }); 

  entry.buttons().container().appendTo('.entry')
});  

</script>