<?php
$session_email = $ssession_type = "";
// Resume the session 
session_start();
// If $_SESSION['email'] not set, force redirect to login page 
if (!isset($_SESSION['email']) && !isset($_SESSION['type'])) {
    header("Location: ../login.php");
} else { // Otherwise, assign the values into $session_email & $ssession_type
    $session_email = $_SESSION['email'];
    $session_type = $_SESSION['type'];
    if ($session_type != "Admin" && $session_type != "Super Admin") {
        header("Location: ../login.php");
    }
}
?>

<?php
include "../include/config.php";
$referenceID = "";

?>

<div class="widget_group">
    <div class="widget_container">
        <div class="widget_name">
            <p>Total Flow Today</p>
        </div>
        <i class="fa-solid fa-right-left"></i>
        <div class="widget_value">
            <!-- Returns the number of entries and exits and of the current day/date and displays the sum -->
            <?php
            $totalflowquery = "SELECT(SELECT COUNT(*) FROM entrylog WHERE DATE(`entryTime`) = CURDATE()) + (SELECT COUNT(*) FROM exitlog WHERE DATE(`exitTime`) = CURDATE()) AS total";
            $result = $conn->query($totalflowquery);
            while ($row = mysqli_fetch_array($result)) {
                echo "<p>" . $row['total'] . "</p>";
            }
            ?>
        </div>


    </div>
    <div class="widget_container">
        <div class="widget_name">
            <p>Entries Today</p>
        </div>
        <i class="fa-solid fa-arrow-right-to-bracket"></i>
        <div class="widget_value">
            <!-- Returns the number of entries of the current day/date from database-->
            <?php
            $totalentryquery = "SELECT COUNT(*) AS totalentry FROM entrylog WHERE DATE(`entryTime`) = CURDATE()";
            $result = $conn->query($totalentryquery);
            while ($row = mysqli_fetch_array($result)) {
                echo "<p>" . $row['totalentry'] . "</p>";
            }
            ?>
        </div>


    </div>
    <div class="widget_container">
        <div class="widget_name">
            <p>Exits Today</p>
        </div>
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <div class="widget_value">
            <!-- Returns the number of exits of the current day/date from database -->
            <?php
            $totalexitquery = "SELECT COUNT(*) AS totalexit FROM exitlog WHERE DATE(`exitTime`) = CURDATE()";
            $result = $conn->query($totalexitquery);
            while ($row = mysqli_fetch_array($result)) {
                echo "<p>" . $row['totalexit'] . "</p>";
            }
            ?>
        </div>


    </div>
    <div class="widget_container">
        <div class="widget_name">
            <p>Denied Entries</p>
        </div>
        <i class="fa-solid fa-ban"></i>
        <div class="widget_value">
            <!-- Returns the number of denied access of the current day/date from database -->
            <?php
            $totaldeniedquery = "SELECT COUNT(*) AS totaldenied FROM deniedaccess WHERE DATE(`deniedTime`) = CURDATE()";
            $result = $conn->query($totaldeniedquery);
            while ($row = mysqli_fetch_array($result)) {
                echo "<p>" . $row['totaldenied'] . "</p>";
            }
            ?>
        </div>


    </div>
</div>
<!-- Returns the last 10 vehicles that has entered on the current day/date from database -->
<?php
$entrylogquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE DATE(entryTime) = CURDATE() ORDER BY entrylog.referenceID DESC LIMIT 10";
$result = $conn->query($entrylogquery);
?>
<div class="dashboard_logs">
    <div class="dashboard_logs_container">
        <h1>Recent Entries</h1>
        <table id="entry_log_table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Timestamp</td>
                    <td>License Plate Number</td>
                    <td>Tenant Lot Number</td>
                </tr>
            </thead>
            <!-- Display the queried data into table form -->
            <?php
            if ($result) {
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
            }
            ?>
        </table>
    </div>
    <!-- Returns the last 10 vehicles that has exited on the current day/date from database -->        
    <?php
    $exitlogquery = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE DATE(exitTime) = CURDATE() ORDER BY exitlog.referenceID DESC LIMIT 10";
    $result = $conn->query($exitlogquery);
    ?>
    <div class="dashboard_logs_container">
        <h1>Recent Exits</h1>
        <table id="exit_log_table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Timestamp</td>
                    <td>License Plate Number</td>
                    <td>Tenant Lot Number</td>
                </tr>
            </thead>
            <!-- Display the queried data into table form -->
            <?php
            if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                    $date = $row['exitTime'];
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
            }
            ?>
        </table>
    </div>
</div>