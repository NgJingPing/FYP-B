<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<link type="text/css" rel="stylesheet" href="style/style.css">
    <title>ANPR - Entry Log</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "anprdb";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}

	$myquery = "SELECT entrylog.referenceID, entrylog.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.licensePlate = vehicle.licensePlate ORDER BY referenceID DESC; ";
	$result = $conn->query($myquery);
?>

<body>
    <header>
		<h1>Entry Log</h1>
	</header>

	<div class="log_container">
		<table id="log_table" class="table table-striped table-bordered">  
			<thead>  
                <tr>  
                    <td>Reference ID</td>  
                    <td>Timestamp</td>  
                    <td>License Plate Number</td>  
                    <td>Tenant Lot Number</td>  
                    <td>Actions</td>  
                </tr>  
            </thead>  

			<?php
				while($row = mysqli_fetch_array($result))  
                {  
                    echo '  
                    <tr>  
                        <td>'.$row["referenceID"].'</td>  
                        <td>'.$row["entryTime"].'</td>  
                        <td>'.$row["licensePlate"].'</td>  
                        <td>'.$row["tenantLotNumber"].'</td>  
                        <td><i class="fa fa-external-link"><a href="action.php?referenceID=\"'.$row["referenceID"].'\"></i></td>  
                    </tr>  
                    ';  
                } 
			?>
		</table>
	</div>
</body>
</html>

<script>
    $(document).ready(function(){  
          $('#log_table').DataTable();  
     });  
</script>