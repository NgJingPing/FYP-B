<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "utf-8">
	<meta name = "author" content = "Ng Jing Ping">
	<link type="text/css" rel="stylesheet" href="style/style.css">
    <title>ANPR - Database</title>
    <style>
        table, th, td, tr {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td, td > span{
            padding: 8px;
        }
    </style>
</head>
<body>

    <h2>Database</h2>

    <section>
        <table>
            <tr>
                <th>License Plate</th>
                <th>Tenant Lot Number</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Colour</th>
                <th>Modify/Delete</th>
            </tr>

            <?php
                
               // set the servername,username and password
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "anprdb";

                // Create connection
                //The mysqli_connect() function attempts to open a connection to the MySQL Server 
                //running on host which can be either a host name or an IP address. 
                $conn = mysqli_connect($servername, $username, $password, $dbname);

                // Check connection
                if (!$conn) {
                    //The die() function is an alias of the exit() function.
                    die("Connection failed: " . mysqli_connect_error()); 
                }
                //echo "Connected successfully </br>";

                $sql = "SELECT * FROM vehicle";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    echo '<script>alert("Empty Result!")</script>';
                }

                $sumprice = 0;

                if (mysqli_num_rows($result)) {
                    // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>".$row["licensePlate"]."</td><td>".$row["tenantLotNumber"]."</td><td>".$row["brand"]."</td><td>".$row["model"].
                        "</td><td>".$row["colour"]."</td><td><span><a href='https://en.wikipedia.org/wiki/Automatic_number-plate_recognition'>Edit</a>
                        </span><span><a href='remove_vehicle.php?vehicle=$row[licensePlate]&tenant=$row[tenantLotNumber]'>Remove</a></span>"."</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo '<script>alert("Empty Result!")</script>';
                }


                mysqli_close($conn);
            
            
            ?>

            </table>

    </section>
   

</body>
</html>